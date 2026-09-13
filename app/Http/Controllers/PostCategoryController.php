<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostCategory;
use Illuminate\Support\Str;
class PostCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postCategories = PostCategory::query()
            ->withCount('posts')
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $statusSummary = PostCategory::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalPostCategories = (int) $statusSummary->sum();
        $activePostCategories = (int) $statusSummary->get('active', 0);
        $inactivePostCategories = (int) $statusSummary->get('inactive', 0);
        $categoriesWithPosts = (int) PostCategory::query()->has('posts')->count();
        $categoriesWithoutPosts = $totalPostCategories - $categoriesWithPosts;
        $categoriesWithSlug = (int) PostCategory::query()
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->count();
        $newPostCategoriesThisMonth = (int) PostCategory::query()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        $latestPostCategory = PostCategory::query()
            ->withCount('posts')
            ->latest('created_at')
            ->first();
        $topPostCategory = PostCategory::query()
            ->withCount('posts')
            ->orderByDesc('posts_count')
            ->orderByDesc('id')
            ->first();

        return view('backend.postcategory.index', compact(
            'postCategories',
            'statusSummary',
            'totalPostCategories',
            'activePostCategories',
            'inactivePostCategories',
            'categoriesWithPosts',
            'categoriesWithoutPosts',
            'categoriesWithSlug',
            'newPostCategoriesThisMonth',
            'latestPostCategory',
            'topPostCategory'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.postcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request,[
            'title'=>'string|required',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        $slug=Str::slug($request->title);
        $count=PostCategory::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $status=PostCategory::create($data);
        if($status){
            request()->session()->flash('success','Thêm danh mục bài viết thành công');
        }
        else{
            request()->session()->flash('error','Vui lòng thử lại!!');
        }
        return redirect()->route('post-category.index');
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
        $postCategory=PostCategory::findOrFail($id);
        return view('backend.postcategory.edit')->with('postCategory',$postCategory);
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
        $postCategory=PostCategory::findOrFail($id);
         // return $request->all();
         $this->validate($request,[
            'title'=>'string|required',
            'status'=>'required|in:active,inactive'
        ]);
        $data=$request->all();
        $status=$postCategory->fill($data)->save();
        if($status){
            request()->session()->flash('success','Cập nhật thành công');
        }
        else{
            request()->session()->flash('error','Vui lòng thử lại!!');
        }
        return redirect()->route('post-category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $postCategory=PostCategory::findOrFail($id);

        $status=$postCategory->delete();

        if($status){
            request()->session()->flash('success','Xóa danh mục bài viết thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi xảy ra, vui lòng thử lại !!!');
        }
        return redirect()->route('post-category.index');
    }
}
