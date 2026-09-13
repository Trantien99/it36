<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Notification;
use App\Notifications\StatusNotification;
use App\User;
use App\Models\ProductReview;
class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reviews = ProductReview::query()
            ->with(['user_info', 'product'])
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $statusSummary = ProductReview::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalReviews = (int) $statusSummary->sum();
        $activeReviews = (int) $statusSummary->get('active', 0);
        $inactiveReviews = (int) $statusSummary->get('inactive', 0);
        $averageRate = (float) ProductReview::query()->avg('rate');
        $highRatedReviews = (int) ProductReview::query()
            ->where('rate', '>=', 4)
            ->count();
        $lowRatedReviews = (int) ProductReview::query()
            ->where('rate', '<=', 2)
            ->count();
        $reviewsWithProduct = (int) ProductReview::query()
            ->whereHas('product')
            ->count();
        $orphanReviews = $totalReviews - $reviewsWithProduct;
        $reviewsToday = (int) ProductReview::query()
            ->whereDate('created_at', today())
            ->count();
        $latestReview = ProductReview::query()
            ->with(['user_info', 'product'])
            ->latest('created_at')
            ->first();

        return view('backend.review.index', compact(
            'reviews',
            'statusSummary',
            'totalReviews',
            'activeReviews',
            'inactiveReviews',
            'averageRate',
            'highRatedReviews',
            'lowRatedReviews',
            'reviewsWithProduct',
            'orphanReviews',
            'reviewsToday',
            'latestReview'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'rate'=>'required|numeric|min:1'
        ]);
        $product_info=Product::getProductBySlug($request->slug);
        //  return $product_info;
        // return $request->all();
        $data=$request->all();
        $data['product_id']=$product_info->id;
        $data['user_id']=$request->user()->id;
        $data['status']='active';
        // dd($data);
        $status=ProductReview::create($data);

        $user=User::where('role','admin')->get();
        $details=[
            'title'=>'Có đánh giá sản phẩm mới!',
            'actionURL'=>route('product-detail',$product_info->slug),
            'fas'=>'fa-star'
        ];
        Notification::send($user,new StatusNotification($details));
        if($status){
            request()->session()->flash('success','Cảm ơn bạn đã đánh giá !');
        }
        else{
            request()->session()->flash('error','Có lỗi xảy ra, vui lòng thử lại!!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review=ProductReview::find($id);
        // return $review;
        return view('backend.review.edit')->with('review',$review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review=ProductReview::find($id);
        if($review){
            // $product_info=Product::getProductBySlug($request->slug);
            //  return $product_info;
            // return $request->all();
            $data=$request->all();
            $status=$review->fill($data)->update();

            // $user=User::where('role','admin')->get();
            // return $user;
            // $details=[
            //     'title'=>'Update Product Rating!',
            //     'actionURL'=>route('product-detail',$product_info->id),
            //     'fas'=>'fa-star'
            // ];
            // Notification::send($user,new StatusNotification($details));
            if($status){
                request()->session()->flash('success','Cập nhật thành công đánh giá');
            }
            else{
                request()->session()->flash('error','Có lỗi xảy ra ! Vui lòng thử lại!!');
            }
        }
        else{
            request()->session()->flash('error','Không có đánh giá nào!!');
        }

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review=ProductReview::find($id);
        $status=$review->delete();
        if($status){
            request()->session()->flash('success','Xóa đánh giá thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi xảy ra ! Vui lòng thử lại');
        }
        return redirect()->route('review.index');
    }
}
