<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    protected $product = null;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function wishlist(Request $request)
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

        $already_wishlist = Wishlist::where('user_id', auth()->id())
            ->whereNull('cart_id')
            ->where('product_id', $product->id)
            ->first();

        if ($already_wishlist) {
            request()->session()->flash('error', 'Bạn đã thêm sản phẩm này vào danh sách yêu thích rồi !!!');
            return back();
        }

        $wishlist = new Wishlist();
        $wishlist->user_id = auth()->id();
        $wishlist->product_id = $product->id;
        $wishlist->price = $product->price - (($product->price * $product->discount) / 100);
        $wishlist->quantity = 1;
        $wishlist->amount = $wishlist->price * $wishlist->quantity;

        if ($wishlist->product->stock < $wishlist->quantity || $wishlist->product->stock <= 0) {
            return back()->with('error', 'Hàng không đủ!.');
        }

        $wishlist->save();

        request()->session()->flash('success', 'Sản phẩm đã thêm vào danh sách yêu thích');
        return back();
    }

    public function wishlistDelete(Request $request)
    {
        $wishlist = Wishlist::query()
            ->where('user_id', auth()->id())
            ->whereNull('cart_id')
            ->whereKey($request->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            request()->session()->flash('success', 'Xóa sản phẩm yêu thích thành công');
            return back();
        }

        request()->session()->flash('error', 'Có lỗi, vui lòng thử lại');
        return back();
    }
}
