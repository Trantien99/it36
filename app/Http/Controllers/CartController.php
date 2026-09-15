<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $product = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function addToCart(Request $request)
    {
        $slug = $request->route('slug') ?: $request->input('slug');

        if (empty($slug)) {
            request()->session()->flash('error', 'Sản phẩm không hợp lệ');
            return back();
        }

        $product = Product::where('slug', $slug)->first();
        if (empty($product)) {
            request()->session()->flash('error', 'Sản phẩm không hợp lệ');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->id())
            ->whereNull('order_id')
            ->where('product_id', $product->id)
            ->first();

        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + 1;
            $already_cart->amount = $product->price + $already_cart->amount;

            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Stock not sufficient!.');
            }

            $already_cart->save();
        } else {
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $product->id;
            $cart->price = $product->price - (($product->price * $product->discount) / 100);
            $cart->quantity = 1;
            $cart->amount = $cart->price * $cart->quantity;

            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Không đủ hàng trong kho!.');
            }

            $cart->save();

            Wishlist::where('user_id', auth()->id())
                ->whereNull('cart_id')
                ->update(['cart_id' => $cart->id]);
        }

        request()->session()->flash('success', 'Sản phẩm đã được thêm vào giỏ hàng');
        return back();
    }

    public function singleAddToCart(Request $request)
    {
        $request->validate([
            'slug' => 'required',
            'quant' => 'required',
        ]);

        $product = Product::where('slug', $request->slug)->first();
        if ($product->stock < $request->quant[1]) {
            return back()->with('error', 'Hết hàng, bạn có thể thêm sản phẩm khác.');
        }

        if (($request->quant[1] < 1) || empty($product)) {
            request()->session()->flash('error', 'Sản phẩm không hợp lệ');
            return back();
        }

        $already_cart = Cart::where('user_id', auth()->id())
            ->whereNull('order_id')
            ->where('product_id', $product->id)
            ->first();

        if ($already_cart) {
            $already_cart->quantity = $already_cart->quantity + $request->quant[1];
            $already_cart->amount = ($product->price * $request->quant[1]) + $already_cart->amount;

            if ($already_cart->product->stock < $already_cart->quantity || $already_cart->product->stock <= 0) {
                return back()->with('error', 'Không đủ hàng trong kho!.');
            }

            $already_cart->save();
        } else {
            $cart = new Cart();
            $cart->user_id = auth()->id();
            $cart->product_id = $product->id;
            $cart->price = $product->price - (($product->price * $product->discount) / 100);
            $cart->quantity = $request->quant[1];
            $cart->amount = $product->price * $request->quant[1];

            if ($cart->product->stock < $cart->quantity || $cart->product->stock <= 0) {
                return back()->with('error', 'Không đủ hàng trong kho!.');
            }

            $cart->save();
        }

        request()->session()->flash('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
        return back();
    }

    public function cartDelete(Request $request)
    {
        $cart = Cart::query()
            ->where('user_id', auth()->id())
            ->whereNull('order_id')
            ->whereKey($request->id)
            ->first();

        if ($cart) {
            $cart->delete();
            request()->session()->flash('success', 'Xóa sản phẩm trong giỏ hàng thành công');
            return back();
        }

        request()->session()->flash('error', 'Có lỗi, vui lòng thử lại');
        return back();
    }

    public function cartUpdate(Request $request)
    {
        if ($request->quant) {
            $error = [];
            $success = '';

            foreach ($request->quant as $k => $quant) {
                $id = $request->qty_id[$k];
                $cart = Cart::query()
                    ->where('user_id', auth()->id())
                    ->whereNull('order_id')
                    ->whereKey($id)
                    ->first();

                if ($quant > 0 && $cart) {
                    if ($cart->product->stock < $quant) {
                        request()->session()->flash('error', 'Hết hàng');
                        return back();
                    }

                    $cart->quantity = ($cart->product->stock > $quant) ? $quant : $cart->product->stock;

                    if ($cart->product->stock <= 0) {
                        continue;
                    }

                    $after_price = $cart->product->price - (($cart->product->price * $cart->product->discount) / 100);
                    $cart->amount = $after_price * $quant;
                    $cart->save();
                    $success = 'Cập nhật giỏ hàng thành công!';
                } else {
                    $error[] = 'Giỏ hàng không hợp lệ!';
                }
            }

            if (session()->has('coupon')) {
                $coupon = session('coupon');
                $newTotal = \Helper::totalCartPrice();

                if (($coupon['type'] ?? null) === 'percent') {
                    $coupon['value'] = ($coupon['configured_value'] / 100) * $newTotal;
                } elseif (($coupon['type'] ?? null) === 'fixed') {
                    $coupon['value'] = min((float) ($coupon['configured_value'] ?? 0), $newTotal);
                } else {
                    $coupon['value'] = 0;
                }

                session()->put('coupon', $coupon);
            }

            if ($request->boolean('redirect_to_checkout')) {
                return redirect()->route('checkout');
            }

            return back()->with($error)->with('success', $success);
        }

        if ($request->boolean('redirect_to_checkout')) {
            return redirect()->route('checkout');
        }

        return back()->with('Giỏ hàng không hợp lệ!');
    }

    public function checkout(Request $request)
    {
        return view('frontend.pages.checkout');
    }
}
