<?php

namespace App\Http\Controllers;

use App\Models\InventoryReceipt;
use App\Models\InventoryReceiptItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InventoryReceiptController extends Controller
{
    public function index()
    {
        $receipts = InventoryReceipt::with('receivedBy')
            ->withCount('items')
            ->orderByDesc('id')
            ->paginate(15);

        return view('backend.inventory-receipts.index', compact('receipts'));
    }

    public function create()
    {
        $products = Product::query()->orderBy('title')->get(['id', 'title', 'stock']);

        if ($products->isEmpty()) {
            return redirect()->route('product.create')
                ->with('error', 'Chưa có sản phẩm nào trong hệ thống. Vui lòng thêm sản phẩm trước khi lập phiếu nhập kho.');
        }

        return view('backend.inventory-receipts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'supplier_name' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string|max:5000',
            'items' => 'required|array|min:1|max:100',
            'items.*.product_id' => 'required|integer|distinct|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000000',
            'items.*.unit_cost' => 'nullable|numeric|min:0|max:9999999999.99',
        ]);

        $totalQuantity = collect($validated['items'])->sum(function ($item) {
            return (int) $item['quantity'];
        });

        $receipt = DB::transaction(function () use ($validated, $totalQuantity) {
            $receipt = InventoryReceipt::create([
                'receipt_number' => 'GR-' . date('YmdHis') . '-' . strtoupper(Str::random(5)),
                'supplier_name' => $validated['supplier_name'] ?? null,
                'reference_number' => $validated['reference_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'total_quantity' => $totalQuantity,
                'received_by' => auth()->id(),
            ]);

            foreach ($validated['items'] as $item) {
                $product = Product::query()
                    ->whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();
                $quantity = (int) $item['quantity'];
                $unitCost = (float) ($item['unit_cost'] ?? 0);

                if ((int) $product->stock + $quantity > 2147483647) {
                    abort(422, 'Số lượng nhập vượt giới hạn tồn kho của sản phẩm ' . $product->title . '.');
                }

                InventoryReceiptItem::create([
                    'inventory_receipt_id' => $receipt->id,
                    'product_id' => $product->id,
                    'product_name' => $product->title,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'line_total' => round($unitCost * $quantity, 2),
                ]);

                $product->stock = (int) $product->stock + $quantity;
                $product->save();
            }

            return $receipt;
        });

        return redirect()->route('inventory-receipts.show', $receipt->id)
            ->with('success', 'Đã tạo phiếu nhập và cập nhật tồn kho.');
    }

    public function show($id)
    {
        $receipt = InventoryReceipt::with(['items.product', 'receivedBy'])->findOrFail($id);

        return view('backend.inventory-receipts.show', compact('receipt'));
    }
}