<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\BuildsListingMedia;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

use Illuminate\Support\Str;

class ProductController extends Controller
{
    use BuildsListingMedia;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with(['cat_info', 'sub_cat_info', 'brand'])
            ->orderBy('id', 'DESC')
            ->paginate(10);

        $statusSummary = Product::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $conditionSummary = Product::query()
            ->selectRaw('`condition` as product_condition, COUNT(*) as total')
            ->groupBy('condition')
            ->pluck('total', 'product_condition');

        $totalProducts = (int) $statusSummary->sum();
        $activeProducts = (int) $statusSummary->get('active', 0);
        $inactiveProducts = (int) $statusSummary->get('inactive', 0);
        $featuredProducts = (int) Product::query()->where('is_featured', 1)->count();
        $totalStock = (int) Product::query()->sum('stock');
        $outOfStockProducts = (int) Product::query()->where('stock', '<=', 0)->count();
        $lowStockProducts = (int) Product::query()->whereBetween('stock', [1, 5])->count();
        $averagePrice = (float) Product::query()->avg('price');
        $latestProduct = Product::with(['cat_info', 'brand'])
            ->latest('created_at')
            ->first();

        return view('backend.product.index', compact(
            'products',
            'statusSummary',
            'conditionSummary',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'featuredProducts',
            'totalStock',
            'outOfStockProducts',
            'lowStockProducts',
            'averagePrice',
            'latestProduct'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand=Brand::get();
        $category=Category::where('is_parent',1)->get();
        // return $category;
        return view('backend.product.create')->with('categories',$category)->with('brands',$brand);
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
            'summary'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'nullable|string|required_without:photo_file',
            'photo_file'=>'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'brand_id'=>'nullable|exists:brands,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'status'=>'required|in:active,inactive',
            'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            'discount'=>'nullable|numeric'
        ]);

        $data=$request->all();

        $data = $this->storeProductPhotoFile($request, $data);

        $slug=Str::slug($request->title);
        $count=Product::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
        $data['slug']=$slug;
        $data['is_featured']=$request->input('is_featured',0);
        $size=$request->input('size');
        if($size){
            $data['size']=implode(',',$size);
        }
        else{
            $data['size']='';
        }
        // return $size;
        // return $data;
        $status=Product::create($data);
        if($status){
            $this->ensureGeneratedProductDetailImages($data['photo'] ?? '');
            request()->session()->flash('success','Thêm sản phâm thành công');
        }
        else{
            request()->session()->flash('error','Vui lòng thử lại!!');
        }
        return redirect()->route('product.index');

    }

    protected function storeProductPhotoFile(Request $request, array $data)
    {
        if (!$request->hasFile('photo_file')) {
            return $data;
        }

        $file = $request->file('photo_file');
        $yearMonth = date('Y/m');
        $directory = public_path('storage/photos/' . $yearMonth);

        if (!is_dir($directory) && !mkdir($directory, 0775, true) && !is_dir($directory)) {
            return $data;
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($directory, $filename);

        $data['photo'] = '/storage/photos/' . $yearMonth . '/' . $filename;

        return $data;
    }

    protected function ensureGeneratedProductDetailImages($photoList)
    {
        $photos = array_values(array_filter(array_map('trim', explode(',', (string) $photoList))));

        foreach ($photos as $photo) {
            $meta = $this->getPublicImageMeta($photo);
            if (!$meta) {
                continue;
            }

            if ($this->shouldEnhanceGalleryImage($meta['width'], $meta['height'])) {
                $this->createEnhancedGalleryImage($meta);
            }
        }
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
        $brand=Brand::get();
        $product=Product::findOrFail($id);
        $category=Category::where('is_parent',1)->get();
        $items=Product::where('id',$id)->get();
        // return $items;
        return view('backend.product.edit')->with('product',$product)
                    ->with('brands',$brand)
                    ->with('categories',$category)->with('items',$items);
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
        $product=Product::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required',
            'summary'=>'string|required',
            'description'=>'string|nullable',
            'photo'=>'nullable|string|required_without:photo_file',
            'photo_file'=>'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'size'=>'nullable',
            'stock'=>"required|numeric",
            'cat_id'=>'required|exists:categories,id',
            'child_cat_id'=>'nullable|exists:categories,id',
            'is_featured'=>'sometimes|in:1',
            'brand_id'=>'nullable|exists:brands,id',
            'status'=>'required|in:active,inactive',
            'condition'=>'required|in:default,new,hot',
            'price'=>'required|numeric',
            'discount'=>'nullable|numeric'
        ]);

        $data=$request->all();

        $data = $this->storeProductPhotoFile($request, $data);

        $data['is_featured']=$request->input('is_featured',0);
        $size=$request->input('size');
        if($size){
            $data['size']=implode(',',$size);
        }
        else{
            $data['size']='';
        }
        // return $data;
        $status=$product->fill($data)->save();
        if($status){
            $this->ensureGeneratedProductDetailImages($data['photo'] ?? '');
            request()->session()->flash('success','Cập nhật sản phẩm thành công');
        }
        else{
            request()->session()->flash('error','Vui lòng thử lại!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $status=$product->delete();

        if($status){
            request()->session()->flash('success','Xóa sản phẩm thành công');
        }
        else{
            request()->session()->flash('error','Có lỗi trong quá trình xóa sản phẩm');
        }
        return redirect()->route('product.index');
    }
}
