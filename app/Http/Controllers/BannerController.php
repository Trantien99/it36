<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Illuminate\Support\Str;
class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::query()
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $statusSummary = Banner::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalBanners = (int) $statusSummary->sum();
        $activeBanners = (int) $statusSummary->get('active', 0);
        $inactiveBanners = (int) $statusSummary->get('inactive', 0);
        $bannersWithPhoto = (int) Banner::query()
            ->whereNotNull('photo')
            ->where('photo', '!=', '')
            ->count();
        $bannersWithoutPhoto = $totalBanners - $bannersWithPhoto;
        $bannersWithDescription = (int) Banner::query()
            ->whereNotNull('description')
            ->where('description', '!=', '')
            ->count();
        $readyBanners = (int) Banner::query()
            ->where('status', 'active')
            ->whereNotNull('photo')
            ->where('photo', '!=', '')
            ->count();
        $latestBanner = Banner::query()
            ->latest('created_at')
            ->first();

        return view('backend.banner.index', compact(
            'banners',
            'statusSummary',
            'totalBanners',
            'activeBanners',
            'inactiveBanners',
            'bannersWithPhoto',
            'bannersWithoutPhoto',
            'bannersWithDescription',
            'readyBanners',
            'latestBanner'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.banner.create');
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
            'title'=>'string|required|max:50',
            'description'=>'string|nullable',
            'photo'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        $slug=Str::slug($request->title);
        $count=Banner::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        // return $slug;
        $status=Banner::create($data);
        if($status){
            request()->session()->flash('success','Thêm Banner thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi xảy ra trong quá trình thêm Banner');
        }
        return redirect()->route('banner.index');
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
        $banner=Banner::findOrFail($id);
        return view('backend.banner.edit')->with('banner',$banner);
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
        $banner=Banner::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required|max:50',
            'description'=>'string|nullable',
            'photo'=>'string|required',
            'status'=>'required|in:active,inactive',
        ]);
        $data=$request->all();
        // $slug=Str::slug($request->title);
        // $count=Banner::where('slug',$slug)->count();
        // if($count>0){
        //     $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        // }
        // $data['slug']=$slug;
        // return $slug;
        $status=$banner->fill($data)->save();
        if($status){
            request()->session()->flash('success','Banner cập nhật thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi trong quá trình cập nhật Banner');
        }
        return redirect()->route('banner.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner=Banner::findOrFail($id);
        $status=$banner->delete();
        if($status){
            request()->session()->flash('success','Xóa Banner thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi xảy ra trong quá trình xóa Banner');
        }
        return redirect()->route('banner.index');
    }
}
