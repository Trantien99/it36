<?php

namespace App\Http\Controllers;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\PostTag;
use App\Models\PostCategory;
use App\Models\Post;
use App\Models\Cart;
use App\Models\Brand;
use App\User;
use Auth;
use Session;
use Newsletter;
use DB;
use Hash;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
class FrontendController extends Controller
{

    public function index(Request $request){
        return redirect()->route($request->user()->role);
    }

    public function home(){
        $featured=Product::where('status','active')->where('is_featured',1)->orderBy('price','DESC')->limit(2)->get();
        $posts=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $banners=Banner::where('status','active')->limit(3)->orderBy('id','DESC')->get();
        // return $banner;
        $products=Product::where('status','active')->orderBy('id','DESC')->limit(8)->get();
        $latestHomeProducts = Product::where('status','active')->orderBy('id','DESC')->limit(6)->get();
        $category=Category::where('status','active')->where('is_parent',1)->orderBy('title','ASC')->get();
        $featured = $this->decorateProductsWithListingMedia($featured);
        $products = $this->decorateProductsWithListingMedia($products);
        $latestHomeProducts = $this->decorateProductsWithListingMedia($latestHomeProducts);
        // return $category;
        return view('frontend.index')
                ->with('featured',$featured)
                ->with('posts',$posts)
                ->with('banners',$banners)
                ->with('product_lists',$products)
                ->with('latest_home_products', $latestHomeProducts)
                ->with('category_lists',$category);
    }

    public function aboutUs(){
        $settings = DB::table('settings')->first();
        $featuredProducts = Product::with('brand')
            ->where('status', 'active')
            ->orderBy('is_featured', 'DESC')
            ->orderBy('id', 'DESC')
            ->limit(3)
            ->get();
        $aboutStats = [
            'product_count' => Product::where('status', 'active')->count(),
            'brand_count' => Brand::where('status', 'active')->count(),
            'review_count' => DB::table('product_reviews')->where('status', 'active')->count(),
            'stock_count' => Product::where('status', 'active')->sum('stock'),
            'average_rating' => round((float) DB::table('product_reviews')->where('status', 'active')->avg('rate'), 1),
        ];

        return view('frontend.pages.about-us', compact('settings', 'featuredProducts', 'aboutStats'));
    }

    public function contact(){
        return view('frontend.pages.contact');
    }

    public function productDetail($slug){
        $product_detail= Product::getProductBySlug($slug);
        // dd($product_detail);
        $galleryMedia = $this->buildProductGalleryMedia($product_detail ? $product_detail->photo : '');

        return view('frontend.pages.product_detail')
            ->with('product_detail',$product_detail)
            ->with('galleryMedia', $galleryMedia);
    }

    private function buildProductGalleryMedia($photoList){
        $galleryMedia = [];
        $photos = array_values(array_filter(array_map('trim', explode(',', (string) $photoList))));

        foreach($photos as $photoUrl){
            $meta = $this->getPublicImageMeta($photoUrl);
            $displayUrl = $photoUrl;
            $displayWidth = null;
            $isLowResolution = false;

            if($meta){
                $displayWidth = $meta['width'];

                if($this->shouldEnhanceGalleryImage($meta['width'], $meta['height'])){
                    $isLowResolution = true;
                    $displayWidth = $this->getRecommendedGalleryDisplayWidth($meta['width'], $meta['height']);

                    $enhancedUrl = $this->createEnhancedGalleryImage($meta);
                    if(!empty($enhancedUrl)){
                        $displayUrl = $enhancedUrl;
                    }
                }
            }

            $galleryMedia[] = [
                'original_url' => $photoUrl,
                'display_url' => $displayUrl,
                'thumb_url' => $displayUrl,
                'width' => $meta['width'] ?? null,
                'height' => $meta['height'] ?? null,
                'display_width' => $displayWidth,
                'is_low_resolution' => $isLowResolution,
            ];
        }

        return $galleryMedia;
    }

    private function decorateProductsWithListingMedia($products){
        if($products instanceof \Illuminate\Pagination\AbstractPaginator){
            $products->setCollection(
                $products->getCollection()->map(function ($product) {
                    return $this->attachListingMediaToProduct($product);
                })
            );

            return $products;
        }

        if($products instanceof \Illuminate\Support\Collection){
            return $products->map(function ($product) {
                return $this->attachListingMediaToProduct($product);
            });
        }

        return $products;
    }

    private function attachListingMediaToProduct($product){
        if(!$product){
            return $product;
        }

        $product->listing_media = $this->buildListingMedia($product->photo);

        return $product;
    }

    private function buildListingMedia($photoList){
        $photos = array_values(array_filter(array_map('trim', explode(',', (string) $photoList))));
        $primaryPhoto = $photos[0] ?? '';
        $media = [
            'original_url' => $primaryPhoto,
            'display_url' => $primaryPhoto,
            'width' => null,
            'height' => null,
            'display_width' => null,
            'is_low_resolution' => false,
        ];

        if(empty($primaryPhoto)){
            return $media;
        }

        $meta = $this->getPublicImageMeta($primaryPhoto);
        if(!$meta){
            return $media;
        }

        $media['width'] = $meta['width'];
        $media['height'] = $meta['height'];

        if($this->shouldEnhanceGalleryImage($meta['width'], $meta['height'])){
            $media['is_low_resolution'] = true;
            $media['display_width'] = $this->getRecommendedListingDisplayWidth($meta['width'], $meta['height']);

            $enhancedUrl = $this->createEnhancedGalleryImage($meta);
            if(!empty($enhancedUrl)){
                $media['display_url'] = $enhancedUrl;
            }

            return $media;
        }

        $media['display_width'] = min(260, max(180, (int) round($meta['width'] * 0.9)));

        return $media;
    }

    private function getPublicImageMeta($imageUrl){
        if(empty($imageUrl) || Str::startsWith($imageUrl, ['http://', 'https://', '//'])){
            return null;
        }

        $parsedPath = parse_url($imageUrl, PHP_URL_PATH);
        $relativePath = ltrim($parsedPath ?: $imageUrl, '/');
        $absolutePath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $relativePath));

        if(!is_file($absolutePath)){
            return null;
        }

        $info = @getimagesize($absolutePath);
        if($info === false){
            return null;
        }

        return [
            'relative_path' => $relativePath,
            'absolute_path' => $absolutePath,
            'width' => (int) $info[0],
            'height' => (int) $info[1],
            'mime' => $info['mime'] ?? null,
            'extension' => strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION)),
        ];
    }

    private function shouldEnhanceGalleryImage($width, $height){
        return $width > 0 && $height > 0 && max($width, $height) < 700;
    }

    private function getRecommendedGalleryDisplayWidth($width, $height){
        $maxDimension = max((int) $width, (int) $height);

        if($maxDimension <= 260){
            return min(240, max(180, (int) round($width * 1.12)));
        }

        if($maxDimension <= 360){
            return min(280, max(220, (int) round($width * 1.16)));
        }

        if($maxDimension <= 520){
            return min(340, max(260, (int) round($width * 1.22)));
        }

        return min(420, max(320, (int) round($width * 1.26)));
    }

    private function getRecommendedListingDisplayWidth($width, $height){
        $maxDimension = max((int) $width, (int) $height);

        if($maxDimension <= 260){
            return min(170, max(132, (int) round($width * 0.82)));
        }

        if($maxDimension <= 360){
            return min(188, max(150, (int) round($width * 0.84)));
        }

        if($maxDimension <= 520){
            return min(220, max(180, (int) round($width * 0.88)));
        }

        return min(250, max(190, (int) round($width * 0.92)));
    }

    private function createEnhancedGalleryImage(array $imageMeta){
        $extension = $this->normalizeOutputExtension($imageMeta['extension'] ?? '');
        if(empty($extension) || empty($imageMeta['absolute_path']) || !is_file($imageMeta['absolute_path'])){
            return null;
        }

        $cacheDirectory = public_path('storage/generated/product-detail');
        if(!is_dir($cacheDirectory) && !mkdir($cacheDirectory, 0755, true) && !is_dir($cacheDirectory)){
            return null;
        }

        $signature = md5($imageMeta['relative_path'] . '|' . $imageMeta['width'] . 'x' . $imageMeta['height'] . '|gallery-v2');
        $outputRelativePath = 'storage/generated/product-detail/' . $signature . '.' . $extension;
        $outputAbsolutePath = public_path($outputRelativePath);
        $sourceModifiedAt = @filemtime($imageMeta['absolute_path']);
        $outputModifiedAt = @filemtime($outputAbsolutePath);

        if($outputModifiedAt !== false && $sourceModifiedAt !== false && $outputModifiedAt >= $sourceModifiedAt){
            return '/' . $outputRelativePath;
        }

        $sourceImage = $this->createImageResource($imageMeta['absolute_path'], $extension);
        if(!$sourceImage){
            return null;
        }

        $scale = max(2, (int) ceil(780 / max(1, $imageMeta['width'])));
        $scale = min($scale, 4);
        $targetWidth = (int) max($imageMeta['width'], $imageMeta['width'] * $scale);
        $targetHeight = (int) max($imageMeta['height'], $imageMeta['height'] * $scale);
        $enhancedImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if(!$enhancedImage){
            imagedestroy($sourceImage);
            return null;
        }

        if(in_array($extension, ['png', 'webp'], true)){
            imagealphablending($enhancedImage, false);
            imagesavealpha($enhancedImage, true);
            $transparent = imagecolorallocatealpha($enhancedImage, 0, 0, 0, 127);
            imagefilledrectangle($enhancedImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }else{
            $background = imagecolorallocate($enhancedImage, 255, 255, 255);
            imagefilledrectangle($enhancedImage, 0, 0, $targetWidth, $targetHeight, $background);
        }

        imagecopyresampled(
            $enhancedImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            imagesx($sourceImage),
            imagesy($sourceImage)
        );

        @imagefilter($enhancedImage, IMG_FILTER_GAUSSIAN_BLUR);
        $sharpenMatrix = [
            [-1, -1, -1],
            [-1, 24, -1],
            [-1, -1, -1],
        ];
        @imageconvolution($enhancedImage, $sharpenMatrix, 16, 0);
        @imagefilter($enhancedImage, IMG_FILTER_CONTRAST, -6);
        @imagefilter($enhancedImage, IMG_FILTER_COLORIZE, 0, 0, 0, -6);

        $saved = $this->saveImageResource($enhancedImage, $outputAbsolutePath, $extension);

        imagedestroy($sourceImage);
        imagedestroy($enhancedImage);

        return $saved ? '/' . $outputRelativePath : null;
    }

    private function normalizeOutputExtension($extension){
        $extension = strtolower((string) $extension);

        if($extension === 'jpeg'){
            return 'jpg';
        }

        if(in_array($extension, ['jpg', 'png', 'webp'], true)){
            return $extension;
        }

        return null;
    }

    private function createImageResource($absolutePath, $extension){
        if($extension === 'jpg'){
            return @imagecreatefromjpeg($absolutePath);
        }

        if($extension === 'png'){
            return @imagecreatefrompng($absolutePath);
        }

        if($extension === 'webp' && function_exists('imagecreatefromwebp')){
            return @imagecreatefromwebp($absolutePath);
        }

        return null;
    }

    private function saveImageResource($image, $absolutePath, $extension){
        if($extension === 'jpg'){
            imageinterlace($image, true);
            return imagejpeg($image, $absolutePath, 90);
        }

        if($extension === 'png'){
            return imagepng($image, $absolutePath, 4);
        }

        if($extension === 'webp' && function_exists('imagewebp')){
            return imagewebp($image, $absolutePath, 90);
        }

        return false;
    }

    public function productGrids(){
        $products=Product::query();

        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            // dd($slug);
            $cat_ids=Category::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id',$cat_ids);
            // return $products;
        }
        if(!empty($_GET['brand'])){
            $slugs=explode(',',$_GET['brand']);
            $brand_ids=Brand::select('id')->whereIn('slug',$slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id',$brand_ids);
        }
        if(!empty($_GET['sortBy'])){
            if($_GET['sortBy']=='title'){
                $products=$products->where('status','active')->orderBy('title','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products=$products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price=explode('-',$_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price',$price);
        }

        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products=$products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products=$products->where('status','active')->paginate(9);
        }
        // Sort by name , price, category


        $products = $this->decorateProductsWithListingMedia($products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        return view('frontend.pages.product-grids')->with('products',$products)->with('recent_products',$recent_products);
    }
    public function productLists(){
        $products=Product::query();

        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            // dd($slug);
            $cat_ids=Category::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            // dd($cat_ids);
            $products->whereIn('cat_id',$cat_ids)->paginate;
            // return $products;
        }
        if(!empty($_GET['brand'])){
            $slugs=explode(',',$_GET['brand']);
            $brand_ids=Brand::select('id')->whereIn('slug',$slugs)->pluck('id')->toArray();
            return $brand_ids;
            $products->whereIn('brand_id',$brand_ids);
        }
        if(!empty($_GET['sortBy'])){
            if($_GET['sortBy']=='title'){
                $products=$products->where('status','active')->orderBy('title','ASC');
            }
            if($_GET['sortBy']=='price'){
                $products=$products->orderBy('price','ASC');
            }
        }

        if(!empty($_GET['price'])){
            $price=explode('-',$_GET['price']);
            // return $price;
            // if(isset($price[0]) && is_numeric($price[0])) $price[0]=floor(Helper::base_amount($price[0]));
            // if(isset($price[1]) && is_numeric($price[1])) $price[1]=ceil(Helper::base_amount($price[1]));

            $products->whereBetween('price',$price);
        }

        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // Sort by number
        if(!empty($_GET['show'])){
            $products=$products->where('status','active')->paginate($_GET['show']);
        }
        else{
            $products=$products->where('status','active')->paginate(6);
        }
        // Sort by name , price, category


        $products = $this->decorateProductsWithListingMedia($products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        return view('frontend.pages.product-lists')->with('products',$products)->with('recent_products',$recent_products);
    }
    public function productFilter(Request $request){
            $data= $request->all();
            // return $data;
            $showURL="";
            if(!empty($data['show'])){
                $showURL .='&show='.$data['show'];
            }

            $sortByURL='';
            if(!empty($data['sortBy'])){
                $sortByURL .='&sortBy='.$data['sortBy'];
            }

            $catURL="";
            if(!empty($data['category'])){
                foreach($data['category'] as $category){
                    if(empty($catURL)){
                        $catURL .='&category='.$category;
                    }
                    else{
                        $catURL .=','.$category;
                    }
                }
            }

            $brandURL="";
            if(!empty($data['brand'])){
                foreach($data['brand'] as $brand){
                    if(empty($brandURL)){
                        $brandURL .='&brand='.$brand;
                    }
                    else{
                        $brandURL .=','.$brand;
                    }
                }
            }
            // return $brandURL;

            $priceRangeURL="";
            if(!empty($data['price_range'])){
                $priceRangeURL .='&price='.$data['price_range'];
            }
            if(request()->is('e-shop.loc/product-grids')){
                return redirect()->route('product-grids',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
            else{
                return redirect()->route('product-lists',$catURL.$brandURL.$priceRangeURL.$showURL.$sortByURL);
            }
    }
    public function productSearch(Request $request){
        $search = trim((string) $request->input('search'));

        if($request->isMethod('post')){
            if($search === ''){
                return redirect()->route('product-grids');
            }

            return redirect()->route('product.search', array_filter([
                'search' => $search,
                'show' => $request->input('show'),
                'sortBy' => $request->input('sortBy'),
                'price' => $request->input('price'),
            ], function ($value) {
                return $value !== null && $value !== '';
            }));
        }

        if($search === ''){
            return redirect()->route('product-grids');
        }

        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $products = $this->searchActiveProducts($search, [
            'per_page' => $request->input('show', 9),
            'sort_by' => $request->input('sortBy'),
            'price' => $request->input('price'),
        ]);
        $products = $this->decorateProductsWithListingMedia($products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        return view('frontend.pages.product-grids')
            ->with('products',$products)
            ->with('recent_products',$recent_products)
            ->with('searchKeyword',$search);
    }

    protected function searchActiveProducts($search, array $options = []){
        $context = $this->buildProductSearchContext($search, $options);

        if($context['normalized'] === '' || $context['price_conflict']){
            return $this->paginateProductSearchResults(collect(), $context['per_page']);
        }

        $products = Product::with(['brand', 'cat_info', 'sub_cat_info'])
            ->where('status','active');

        if(!empty($context['brand_ids'])){
            $products->whereIn('brand_id', $context['brand_ids']);
        }

        if(!empty($context['category_ids'])){
            $products->where(function ($query) use ($context) {
                $query->whereIn('cat_id', $context['category_ids'])
                    ->orWhereIn('child_cat_id', $context['category_ids']);
            });
        }

        if($context['price_min'] !== null){
            $products->where('price','>=',$context['price_min']);
        }

        if($context['price_max'] !== null){
            $products->where('price','<=',$context['price_max']);
        }

        $candidates = $products->orderBy('id','DESC')->get();

        if($context['requires_keyword_match']){
            $candidates = $candidates->filter(function ($product) use ($context) {
                return $this->matchesProductSearchCriteria($product, $context);
            })->values();
        }

        $rankedProducts = $this->rankProductSearchResults($candidates, $context);

        return $this->paginateProductSearchResults($rankedProducts, $context['per_page']);
    }

    protected function buildProductSearchContext($search, array $options = []){
        $normalizedSearch = $this->normalizeProductSearchText($search);
        $terms = $this->extractProductSearchTerms($normalizedSearch);
        $expandedTerms = $this->expandProductSearchTerms($normalizedSearch, $terms);
        $matchedBrands = $this->findMatchedProductSearchBrands($normalizedSearch, $expandedTerms);
        $matchedCategories = $this->findMatchedProductSearchCategories($normalizedSearch, $expandedTerms);
        $matchedFeatures = $this->detectMatchedProductSearchFeatures($normalizedSearch);
        $requiredTerms = $this->resolveProductSearchRequiredTerms($terms, $matchedBrands, $matchedCategories, $matchedFeatures);
        $detectedPriceRange = $this->detectProductSearchPriceRange($search);
        $selectedPriceRange = $this->parseProductSearchPriceFilter(isset($options['price']) ? $options['price'] : null);
        $priceRange = $this->mergeProductSearchPriceRanges($detectedPriceRange, $selectedPriceRange);

        return [
            'original' => trim((string) $search),
            'normalized' => $normalizedSearch,
            'slug' => Str::slug((string) $search),
            'terms' => $terms,
            'expanded_terms' => $expandedTerms,
            'brand_ids' => $matchedBrands->pluck('id')->values()->all(),
            'category_ids' => $matchedCategories->pluck('id')->values()->all(),
            'matched_features' => $matchedFeatures,
            'required_terms' => $requiredTerms,
            'price_min' => $priceRange['min'],
            'price_max' => $priceRange['max'],
            'price_conflict' => !empty($priceRange['conflict']),
            'requires_keyword_match' => !empty($requiredTerms) || !empty($matchedFeatures) || $matchedBrands->isNotEmpty() || $matchedCategories->isNotEmpty(),
            'sort_by' => $this->normalizeProductSearchSort(isset($options['sort_by']) ? $options['sort_by'] : null),
            'per_page' => $this->resolveProductSearchPerPage(isset($options['per_page']) ? $options['per_page'] : null),
        ];
    }

    protected function rankProductSearchResults(Collection $products, array $context){
        $scoredProducts = $products->map(function ($product) use ($context) {
            return [
                'product' => $product,
                'score' => $this->scoreProductSearchMatch($product, $context),
            ];
        })->filter(function ($item) {
            return $item['score'] > 0;
        })->values();

        $sortedProducts = $scoredProducts->sort(function ($left, $right) use ($context) {
            return $this->compareProductSearchItems($left, $right, $context['sort_by']);
        })->values();

        return $sortedProducts->pluck('product')->values();
    }

    protected function scoreProductSearchMatch(Product $product, array $context){
        $normalizedSearch = isset($context['normalized']) ? $context['normalized'] : '';
        $terms = isset($context['terms']) ? $context['terms'] : [];
        $expandedTerms = isset($context['expanded_terms']) ? $context['expanded_terms'] : [];
        $matchedFeatures = isset($context['matched_features']) ? $context['matched_features'] : [];
        $title = $this->normalizeProductSearchText($product->title);
        $slug = $this->normalizeProductSearchText(str_replace('-', ' ', (string) $product->slug));
        $summary = $this->normalizeProductSearchText($product->summary);
        $description = $this->normalizeProductSearchText($product->description);
        $brand = $this->normalizeProductSearchText(optional($product->brand)->title);
        $category = $this->normalizeProductSearchText(optional($product->cat_info)->title);
        $subCategory = $this->normalizeProductSearchText(optional($product->sub_cat_info)->title);
        $haystack = $this->buildProductSearchHaystack($product);

        if($haystack === ''){
            return 0;
        }

        $score = 0;
        $matchedTerms = 0;

        if($normalizedSearch !== ''){
            if($title === $normalizedSearch){
                $score += 160;
            }
            elseif(strpos($title, $normalizedSearch) !== false){
                $score += 120;
            }
            elseif(strpos($slug, $normalizedSearch) !== false){
                $score += 105;
            }
            elseif(strpos($haystack, $normalizedSearch) !== false){
                $score += 60;
            }
        }

        foreach($terms as $term){
            if($term === ''){
                continue;
            }

            if($this->productSearchTermExists($title, $term)){
                $score += 24;
                $matchedTerms++;
                continue;
            }

            if($this->productSearchTermExists($brand, $term) || $this->productSearchTermExists($category, $term) || $this->productSearchTermExists($subCategory, $term)){
                $score += 18;
                $matchedTerms++;
                continue;
            }

            if($this->productSearchTermExists($summary, $term) || $this->productSearchTermExists($description, $term) || $this->productSearchTermExists($slug, $term)){
                $score += 11;
                $matchedTerms++;
            }
        }

        foreach($expandedTerms as $term){
            if($term === '' || in_array($term, $terms, true)){
                continue;
            }

            if($this->productSearchTermExists($title, $term)){
                $score += 12;
                continue;
            }

            if($this->productSearchTermExists($haystack, $term)){
                $score += 6;
            }
        }

        foreach($matchedFeatures as $feature){
            $featureTerms = array_values(array_unique(array_merge($feature['keywords'], $feature['search_terms'])));

            if($this->containsProductSearchKeyword($haystack, $featureTerms)){
                $score += 20;
            }
        }

        if(!empty($terms) && $matchedTerms === count($terms)){
            $score += 36;
        }
        elseif($matchedTerms > 1){
            $score += 14;
        }

        if(!empty($context['brand_ids']) && in_array((int) $product->brand_id, $context['brand_ids'], true)){
            $score += 28;
        }

        if(!empty($context['category_ids']) && (in_array((int) $product->cat_id, $context['category_ids'], true) || in_array((int) $product->child_cat_id, $context['category_ids'], true))){
            $score += 22;
        }

        if((isset($context['price_min']) && $context['price_min'] !== null) || (isset($context['price_max']) && $context['price_max'] !== null)){
            $score += 8;
        }

        if((float) $product->discount > 0){
            $score += 2;
        }

        if((int) $product->stock > 0){
            $score += 3;
        }

        if(in_array($product->condition, ['new','hot'], true)){
            $score += 1;
        }

        return $score;
    }

    protected function buildProductSearchHaystack(Product $product){
        $title = $this->normalizeProductSearchText($product->title);
        $slug = $this->normalizeProductSearchText(str_replace('-', ' ', (string) $product->slug));
        $summary = $this->normalizeProductSearchText($product->summary);
        $description = $this->normalizeProductSearchText($product->description);
        $brand = $this->normalizeProductSearchText(optional($product->brand)->title);
        $category = $this->normalizeProductSearchText(optional($product->cat_info)->title);
        $subCategory = $this->normalizeProductSearchText(optional($product->sub_cat_info)->title);

        return trim(implode(' ', array_filter([$title, $slug, $summary, $description, $brand, $category, $subCategory])));
    }

    protected function matchesProductSearchCriteria(Product $product, array $context){
        if(!empty($context['brand_ids']) && !in_array((int) $product->brand_id, $context['brand_ids'], true)){
            return false;
        }

        if(!empty($context['category_ids']) && !in_array((int) $product->cat_id, $context['category_ids'], true) && !in_array((int) $product->child_cat_id, $context['category_ids'], true)){
            return false;
        }

        $haystack = $this->buildProductSearchHaystack($product);
        if($haystack === ''){
            return false;
        }

        foreach(isset($context['matched_features']) ? $context['matched_features'] : [] as $feature){
            $featureTerms = array_values(array_unique(array_merge($feature['keywords'], $feature['search_terms'])));

            if(!$this->containsProductSearchKeyword($haystack, $featureTerms)){
                return false;
            }
        }

        foreach(isset($context['required_terms']) ? $context['required_terms'] : [] as $term){
            if(!$this->productSearchTermExists($haystack, $term)){
                return false;
            }
        }

        return true;
    }

    protected function productSearchTermExists($haystack, $term){
        $haystack = trim((string) $haystack);
        $term = trim((string) $term);

        if($haystack === '' || $term === '' || strpos($haystack, $term) === false){
            return false;
        }

        if(strlen($term) <= 3 || preg_match('/\d/', $term)){
            return true;
        }

        return preg_match('/(?:^|\s)'.preg_quote($term, '/').'(?:\s|$)/', $haystack) === 1;
    }

    protected function detectMatchedProductSearchFeatures($normalizedSearch){
        $matchedFeatures = [];

        foreach($this->buildProductSearchFeatureMap() as $feature){
            if($this->containsProductSearchKeyword($normalizedSearch, $feature['keywords'])){
                $matchedFeatures[] = $feature;
            }
        }

        return array_values($matchedFeatures);
    }

    protected function resolveProductSearchRequiredTerms(array $terms, Collection $matchedBrands, Collection $matchedCategories, array $matchedFeatures){
        $handledTerms = [];

        foreach($matchedBrands as $brand){
            $handledTerms = array_merge(
                $handledTerms,
                $this->extractProductSearchTerms($this->normalizeProductSearchText($brand->title))
            );
        }

        foreach($matchedCategories as $category){
            $handledTerms = array_merge(
                $handledTerms,
                $this->extractProductSearchTerms($this->normalizeProductSearchText($category->title)),
                $this->extractProductSearchTerms($this->normalizeProductSearchText(str_replace('-', ' ', (string) $category->slug)))
            );
        }

        foreach($matchedFeatures as $feature){
            foreach(array_merge($feature['keywords'], $feature['search_terms']) as $featureTerm){
                $handledTerms[] = $featureTerm;
                $handledTerms = array_merge(
                    $handledTerms,
                    $this->extractProductSearchTerms($this->normalizeProductSearchText($featureTerm))
                );
            }
        }

        $handledTerms = array_values(array_unique(array_filter($handledTerms)));

        return array_values(array_filter($terms, function ($term) use ($handledTerms) {
            foreach($handledTerms as $handledTerm){
                if($handledTerm === ''){
                    continue;
                }

                if($term === $handledTerm || strpos($handledTerm, $term) !== false || strpos($term, $handledTerm) !== false){
                    return false;
                }
            }

            return true;
        }));
    }

    protected function compareProductSearchItems(array $left, array $right, $sortBy = 'relevance'){
        $leftProduct = $left['product'];
        $rightProduct = $right['product'];

        if($sortBy === 'title'){
            $comparison = strcmp(
                $this->normalizeProductSearchText($leftProduct->title),
                $this->normalizeProductSearchText($rightProduct->title)
            );

            if($comparison !== 0){
                return $comparison;
            }
        }

        if($sortBy === 'price'){
            $comparison = $this->compareNumbers((float) $leftProduct->price, (float) $rightProduct->price);

            if($comparison !== 0){
                return $comparison;
            }
        }

        if($sortBy === 'brand'){
            $comparison = strcmp(
                $this->normalizeProductSearchText(optional($leftProduct->brand)->title),
                $this->normalizeProductSearchText(optional($rightProduct->brand)->title)
            );

            if($comparison !== 0){
                return $comparison;
            }
        }

        if($sortBy === 'category'){
            $comparison = strcmp(
                $this->normalizeProductSearchText(optional($leftProduct->cat_info)->title),
                $this->normalizeProductSearchText(optional($rightProduct->cat_info)->title)
            );

            if($comparison !== 0){
                return $comparison;
            }
        }

        $comparison = $this->compareNumbers($right['score'], $left['score']);
        if($comparison !== 0){
            return $comparison;
        }

        $comparison = $this->compareNumbers((int) $rightProduct->stock, (int) $leftProduct->stock);
        if($comparison !== 0){
            return $comparison;
        }

        $comparison = $this->compareNumbers((float) $rightProduct->discount, (float) $leftProduct->discount);
        if($comparison !== 0){
            return $comparison;
        }

        return $this->compareNumbers((int) $rightProduct->id, (int) $leftProduct->id);
    }

    protected function compareNumbers($left, $right){
        if($left == $right){
            return 0;
        }

        return $left < $right ? -1 : 1;
    }

    protected function paginateProductSearchResults(Collection $products, $perPage){
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->resolveProductSearchPerPage($perPage);
        $items = $products->forPage($page, $perPage)->values();

        return new LengthAwarePaginator($items, $products->count(), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    }

    protected function findMatchedProductSearchBrands($normalizedSearch, array $terms){
        $brands = Brand::where('status','active')->orderBy('title','ASC')->get();
        $matches = [];

        foreach($brands as $brand){
            $normalizedTitle = $this->normalizeProductSearchText($brand->title);

            if($normalizedTitle === ''){
                continue;
            }

            if(strpos($normalizedSearch, $normalizedTitle) !== false){
                $matches[] = $brand;
                continue;
            }

            foreach($terms as $term){
                if($term !== '' && (strpos($normalizedTitle, $term) !== false || strpos($term, $normalizedTitle) !== false)){
                    $matches[] = $brand;
                    break;
                }
            }
        }

        return collect($matches)->unique('id')->values();
    }

    protected function findMatchedProductSearchCategories($normalizedSearch, array $terms){
        $categories = Category::where('status','active')->orderBy('title','ASC')->get();
        $matches = [];

        foreach($categories as $category){
            $normalizedTitle = $this->normalizeProductSearchText($category->title);
            $normalizedSlug = $this->normalizeProductSearchText(str_replace('-', ' ', $category->slug));

            if($normalizedTitle !== '' && strpos($normalizedSearch, $normalizedTitle) !== false){
                $matches[] = $category;
                continue;
            }

            if($normalizedSlug !== '' && strpos($normalizedSearch, $normalizedSlug) !== false){
                $matches[] = $category;
                continue;
            }

            foreach($terms as $term){
                if($term !== '' && (strpos($normalizedTitle, $term) !== false || strpos($normalizedSlug, $term) !== false)){
                    $matches[] = $category;
                    break;
                }
            }
        }

        foreach($this->buildProductSearchCategoryAliasMap() as $alias){
            if(!$this->containsProductSearchKeyword($normalizedSearch, $alias['keywords'])){
                continue;
            }

            foreach($categories as $category){
                $normalizedCategory = $this->normalizeProductSearchText($category->title.' '.str_replace('-', ' ', $category->slug));

                if($this->containsProductSearchKeyword($normalizedCategory, $alias['category_terms'])){
                    $matches[] = $category;
                }
            }
        }

        return collect($matches)->unique('id')->values();
    }

    protected function buildProductSearchCategoryAliasMap(){
        return [
            [
                'keywords' => ['true wireless', 'tws', 'khong day', 'bluetooth', 'in ear'],
                'category_terms' => ['true wireless', 'khong day', 'bluetooth', 'in ear'],
            ],
            [
                'keywords' => ['chup tai', 'over ear', 'on ear'],
                'category_terms' => ['chup tai', 'over ear', 'on ear'],
            ],
            [
                'keywords' => ['phu kien', 'dac', 'usb c', 'adapter'],
                'category_terms' => ['phu kien', 'dac', 'usb c', 'adapter'],
            ],
        ];
    }

    protected function expandProductSearchTerms($normalizedSearch, array $terms){
        $expandedTerms = $terms;

        foreach($this->buildProductSearchFeatureMap() as $feature){
            if($this->containsProductSearchKeyword($normalizedSearch, $feature['keywords'])){
                $expandedTerms = array_merge($expandedTerms, $feature['search_terms']);
            }
        }

        return array_values(array_unique(array_slice(array_filter($expandedTerms), 0, 10)));
    }

    protected function buildProductSearchFeatureMap(){
        return [
            [
                'keywords' => ['bluetooth', 'khong day', 'wireless'],
                'search_terms' => ['bluetooth', 'khong day', 'wireless'],
            ],
            [
                'keywords' => ['true wireless', 'tws', 'in ear'],
                'search_terms' => ['true wireless', 'tws', 'in ear'],
            ],
            [
                'keywords' => ['gaming', 'choi game', 'game'],
                'search_terms' => ['gaming', 'game', 'do tre thap'],
            ],
            [
                'keywords' => ['chong on', 'anc', 'xuyen am'],
                'search_terms' => ['chong on', 'anc', 'xuyen am'],
            ],
            [
                'keywords' => ['mic', 'micro', 'dam thoai'],
                'search_terms' => ['mic', 'micro'],
            ],
            [
                'keywords' => ['bass', 'vocal', 'am thanh'],
                'search_terms' => ['bass', 'vocal', 'am thanh'],
            ],
        ];
    }

    protected function detectProductSearchPriceRange($text){
        $text = $this->normalizeProductSearchLooseText($text);
        $result = [
            'min' => null,
            'max' => null,
        ];

        if(preg_match('/(?:tu\s+)?(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?\s*(?:den|toi|-)\s*(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)){
            $minUnit = !empty($matches[2]) ? $matches[2] : (isset($matches[4]) ? $matches[4] : null);
            $maxUnit = !empty($matches[4]) ? $matches[4] : (isset($matches[2]) ? $matches[2] : null);
            $min = $this->convertProductSearchPriceToNumber($matches[1], $minUnit);
            $max = $this->convertProductSearchPriceToNumber($matches[3], $maxUnit);

            if($min !== null && $max !== null){
                $result['min'] = min($min, $max);
                $result['max'] = max($min, $max);

                return $result;
            }
        }

        if(preg_match('/(?:duoi|toi da|khong qua)\s+(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)){
            $result['max'] = $this->convertProductSearchPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
        }

        if(preg_match('/(?:tren|tu|hon)\s+(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)){
            $result['min'] = $this->convertProductSearchPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
        }

        if($result['max'] === null && $this->containsProductSearchKeyword($this->normalizeProductSearchText($text), ['re', 'gia mem', 'binh dan', 'pho thong'])){
            $result['max'] = 1500000;
        }

        if($result['min'] === null && $this->containsProductSearchKeyword($this->normalizeProductSearchText($text), ['cao cap', 'premium', 'xin'])){
            $result['min'] = 2500000;
        }

        return $result;
    }

    protected function parseProductSearchPriceFilter($priceRange){
        $priceRange = trim((string) $priceRange);
        if($priceRange === ''){
            return ['min' => null, 'max' => null];
        }

        $parts = array_map('trim', explode('-', $priceRange));
        if(count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])){
            return ['min' => null, 'max' => null];
        }

        return [
            'min' => (int) min($parts[0], $parts[1]),
            'max' => (int) max($parts[0], $parts[1]),
        ];
    }

    protected function mergeProductSearchPriceRanges(array $detectedRange, array $selectedRange){
        $min = isset($detectedRange['min']) ? $detectedRange['min'] : null;
        $max = isset($detectedRange['max']) ? $detectedRange['max'] : null;

        if(isset($selectedRange['min']) && $selectedRange['min'] !== null){
            $min = $min !== null ? max($min, $selectedRange['min']) : $selectedRange['min'];
        }

        if(isset($selectedRange['max']) && $selectedRange['max'] !== null){
            $max = $max !== null ? min($max, $selectedRange['max']) : $selectedRange['max'];
        }

        return [
            'min' => $min,
            'max' => $max,
            'conflict' => $min !== null && $max !== null && $min > $max,
        ];
    }

    protected function resolveProductSearchPerPage($perPage){
        $perPage = (int) $perPage;
        $allowedValues = [9, 15, 21, 30];

        return in_array($perPage, $allowedValues, true) ? $perPage : 9;
    }

    protected function normalizeProductSearchSort($sortBy){
        $sortBy = trim((string) $sortBy);
        $allowedValues = ['relevance', 'title', 'price', 'brand', 'category'];

        return in_array($sortBy, $allowedValues, true) ? $sortBy : 'relevance';
    }

    protected function containsProductSearchKeyword($text, array $keywords){
        foreach($keywords as $keyword){
            if($keyword !== '' && strpos($text, $keyword) !== false){
                return true;
            }
        }

        return false;
    }

    protected function extractProductSearchTerms($text){
        $stopWords = [
            'toi', 'minh', 'muon', 'can', 'hoi', 'shop', 'giup', 'voi', 'nhe', 'nha',
            'la', 've', 'co', 'khong', 'cho', 'xin', 'tu', 'van', 'mot', 'nhung',
            'cua', 'va', 'dang', 'tim', 'xem', 'mau', 'san', 'pham', 'tai', 'nghe',
            'nao', 'nen', 'chon', 'gi', 'theo', 'yeu', 'cau', 'loai', 'hang',
            'duoi', 'tren', 'toi', 'den', 'trieu', 'nghin', 'ngan', 'gia', 'tam', 'khoang',
        ];

        $terms = array_filter(explode(' ', $text), function ($term) use ($stopWords) {
            return strlen($term) >= 2
                && !preg_match('/^\d+$/', $term)
                && !in_array($term, $stopWords, true);
        });

        return array_values(array_unique(array_slice($terms, 0, 6)));
    }

    protected function normalizeProductSearchText($text){
        $normalizedText = Str::lower(Str::ascii((string) $text));
        $normalizedText = preg_replace('/[^a-z0-9\s]/', ' ', $normalizedText);

        return trim(preg_replace('/\s+/', ' ', $normalizedText));
    }

    protected function normalizeProductSearchLooseText($text){
        $normalizedText = Str::lower(Str::ascii((string) $text));
        $normalizedText = preg_replace('/[^a-z0-9\s\.,\-]/', ' ', $normalizedText);

        return trim(preg_replace('/\s+/', ' ', $normalizedText));
    }

    protected function convertProductSearchPriceToNumber($value, $unit = null){
        $normalizedValue = str_replace(',', '.', (string) $value);
        $amount = (float) $normalizedValue;
        $normalizedUnit = trim((string) $unit);

        if($normalizedUnit === '' && $amount < 1000){
            return null;
        }

        if(in_array($normalizedUnit, ['tr', 'trieu', 'cu'], true)){
            return (int) round($amount * 1000000);
        }

        if(in_array($normalizedUnit, ['k', 'nghin', 'ngan'], true)){
            return (int) round($amount * 1000);
        }

        return (int) round($amount);
    }

    public function productBrand(Request $request){
        $products=Brand::getProductByBrand($request->slug);
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $brandProducts = $this->decorateProductsWithListingMedia($products->products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$brandProducts)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$brandProducts)->with('recent_products',$recent_products);
        }

    }
    public function productCat(Request $request){
        $products=Category::getProductByCat($request->slug);
        // return $request->slug;
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        $categoryProducts = $this->decorateProductsWithListingMedia($products->products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$categoryProducts)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$categoryProducts)->with('recent_products',$recent_products);
        }

    }
    public function productSubCat(Request $request){
        $products=Category::getProductBySubCat($request->sub_slug);
        // return $products;
        $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

        $subCategoryProducts = $this->decorateProductsWithListingMedia($products->sub_products);
        $recent_products = $this->decorateProductsWithListingMedia($recent_products);

        if(request()->is('e-shop.loc/product-grids')){
            return view('frontend.pages.product-grids')->with('products',$subCategoryProducts)->with('recent_products',$recent_products);
        }
        else{
            return view('frontend.pages.product-lists')->with('products',$subCategoryProducts)->with('recent_products',$recent_products);
        }

    }

    public function blog(){
        $post=Post::query();

        if(!empty($_GET['category'])){
            $slug=explode(',',$_GET['category']);
            // dd($slug);
            $cat_ids=PostCategory::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            return $cat_ids;
            $post->whereIn('post_cat_id',$cat_ids);
            // return $post;
        }
        if(!empty($_GET['tag'])){
            $slug=explode(',',$_GET['tag']);
            // dd($slug);
            $tag_ids=PostTag::select('id')->whereIn('slug',$slug)->pluck('id')->toArray();
            // return $tag_ids;
            $post->where('post_tag_id',$tag_ids);
            // return $post;
        }

        if(!empty($_GET['show'])){
            $post=$post->where('status','active')->orderBy('id','DESC')->paginate($_GET['show']);
        }
        else{
            $post=$post->where('status','active')->orderBy('id','DESC')->paginate(9);
        }
        // $post=Post::where('status','active')->paginate(8);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post)->with('recent_posts',$rcnt_post);
    }

    public function blogDetail($slug){
        $post=Post::getPostBySlug($slug);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        // return $post;
        return view('frontend.pages.blog-detail')->with('post',$post)->with('recent_posts',$rcnt_post);
    }

    public function blogSearch(Request $request){
        // return $request->all();
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        $posts=Post::orwhere('title','like','%'.$request->search.'%')
            ->orwhere('quote','like','%'.$request->search.'%')
            ->orwhere('summary','like','%'.$request->search.'%')
            ->orwhere('description','like','%'.$request->search.'%')
            ->orwhere('slug','like','%'.$request->search.'%')
            ->orderBy('id','DESC')
            ->paginate(8);
        return view('frontend.pages.blog')->with('posts',$posts)->with('recent_posts',$rcnt_post);
    }

    public function blogFilter(Request $request){
        $data=$request->all();
        // return $data;
        $catURL="";
        if(!empty($data['category'])){
            foreach($data['category'] as $category){
                if(empty($catURL)){
                    $catURL .='&category='.$category;
                }
                else{
                    $catURL .=','.$category;
                }
            }
        }

        $tagURL="";
        if(!empty($data['tag'])){
            foreach($data['tag'] as $tag){
                if(empty($tagURL)){
                    $tagURL .='&tag='.$tag;
                }
                else{
                    $tagURL .=','.$tag;
                }
            }
        }
        // return $tagURL;
            // return $catURL;
        return redirect()->route('blog',$catURL.$tagURL);
    }

    public function blogByCategory(Request $request){
        $post=PostCategory::getBlogByCategory($request->slug);
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post->post)->with('recent_posts',$rcnt_post);
    }

    public function blogByTag(Request $request){
        // dd($request->slug);
        $post=Post::getBlogByTag($request->slug);
        // return $post;
        $rcnt_post=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();
        return view('frontend.pages.blog')->with('posts',$post)->with('recent_posts',$rcnt_post);
    }

    // Login
    public function login(){
        return view('frontend.pages.login');
    }
    public function loginSubmit(Request $request){
        $data= $request->all();
        if(Auth::attempt(['email' => $data['email'], 'password' => $data['password'],'status'=>'active'])){
            Session::put('user',$data['email']);
            request()->session()->flash('success','Đăng nhập thành công');
            if(Auth::user() && Auth::user()->role=='admin'){
                return redirect('/admin?range=365');
            }
            return redirect()->route('home');
        }
        else{
            request()->session()->flash('error','Email hoặc mật khẩu không đúng!');
            return redirect()->back();
        }
    }

    public function logout(){
        Session::forget('user');
        Auth::logout();
        request()->session()->flash('success','Đăng xuất thành công');
        return back();
    }

    public function register(){
        return view('frontend.pages.register');
    }
    public function registerSubmit(Request $request){
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required|min:2',
            'email'=>'string|required|unique:users,email',
            'password'=>'required|min:6|confirmed',
        ]);
        $data=$request->all();
        // dd($data);
        $check=$this->create($data);
        if($check){
            request()->session()->flash('success','Đăng ký thành công. Vui lòng đăng nhập.');
            return redirect()->route('login.form');
        }
        else{
            request()->session()->flash('error','Vui lòng thử lại!');
            return back();
        }
    }
    public function create(array $data){
        return User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'status'=>'active'
            ]);
    }
    // Reset password
    public function showResetForm(){
        return view('auth.passwords.old-reset');
    }

    public function subscribe(Request $request){
        if(! Newsletter::isSubscribed($request->email)){
                Newsletter::subscribePending($request->email);
                if(Newsletter::lastActionSucceeded()){
                    request()->session()->flash('success','Đã đăng ký! Vui lòng kiểm tra Email của bạn');
                    return redirect()->route('home');
                }
                else{
                    Newsletter::getLastError();
                    return back()->with('error','Có lỗi xảy ra ! Vui lòng thử lại');
                }
            }
            else{
                request()->session()->flash('error','Bạn đã đăng ký rồi !!!');
                return back();
            }
    }

}
