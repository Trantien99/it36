<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ChatbotFaq;
use App\Models\Post;
use App\Models\Product;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    const CONTEXT_SESSION_KEY = 'chatbot.context';

    public function reply(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $message = trim((string) $request->message);
            $normalizedMessage = $this->normalizeText($message);
            $terms = $this->extractTerms($normalizedMessage);
            $context = $this->getConversationContext($request);

            if ($this->isGreetingMessage($normalizedMessage)) {
                $this->clearConversationContext($request);

                return response()->json($this->buildGreetingReply());
            }

            if ($this->containsAny($normalizedMessage, ['gioi thieu', 've shop', 'thong tin shop', 'about'])) {
                $this->clearConversationContext($request);

                return response()->json($this->buildAboutReply());
            }

            if ($this->containsAny($normalizedMessage, ['lien he', 'ho tro', 'so dien thoai', 'email', 'dia chi'])) {
                $this->clearConversationContext($request);

                return response()->json($this->buildContactReply());
            }

            if ($this->containsAny($normalizedMessage, ['theo doi don', 'don hang', 'kiem tra don', 'track order'])) {
                $this->clearConversationContext($request);

                return response()->json($this->buildOrderReply());
            }

            if ($this->containsAny($normalizedMessage, ['gio hang', 'cart', 'checkout', 'thanh toan', 'cach mua', 'dat hang'])) {
                $this->clearConversationContext($request);

                return response()->json($this->buildCartReply());
            }

            if ($consultationReply = $this->buildConsultationReply($request, $message, $normalizedMessage, $terms, $context)) {
                return response()->json($consultationReply);
            }

            if ($faqReply = $this->matchFaq($normalizedMessage, $terms)) {
                $this->clearConversationContext($request);

                return response()->json($faqReply);
            }

            if ($brandReply = $this->matchBrands($normalizedMessage)) {
                $this->clearConversationContext($request);

                return response()->json($brandReply);
            }

            if ($productReply = $this->matchProducts($request, $message, $normalizedMessage, $terms)) {
                return response()->json($productReply);
            }

            if ($categoryReply = $this->matchCategories($request, $message, $normalizedMessage, $terms)) {
                return response()->json($categoryReply);
            }

            if ($articleReply = $this->matchPosts($normalizedMessage, $terms)) {
                $this->clearConversationContext($request);

                return response()->json($articleReply);
            }

            if ($this->containsAny($normalizedMessage, ['san pham', 'mau nao', 'xem san pham', 'danh muc'])) {
                $products = Product::with(['brand', 'cat_info'])
                    ->where('status', 'active')
                    ->orderBy('id', 'DESC')
                    ->limit(4)
                    ->get();

                $reply = [
                    'html' => $this->buildProductsListHtml(
                        $products,
                        'Đây là một vài sản phẩm nổi bật đang hiển thị trên shop để bạn bắt đầu nhanh hơn:',
                        'Mở danh sách sản phẩm',
                        route('product-grids')
                    ),
                    'actions' => $this->defaultActions(),
                ];

                $this->rememberProductResults($request, $products, [], 'featured-products');

                return response()->json($reply);
            }

            $this->clearConversationContext($request);

            return response()->json($this->fallbackReply());
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Chatbot Controller Error: ' . $e->getMessage(), [
                'exception' => $e,
                'message' => $request->input('message'),
            ]);

            return response()->json([
                'html' => '<p>Hệ thống chatbot đang gặp gián đoạn tạm thời. Bạn hãy thử lại hoặc xem <a href="' . route('product-grids') . '">danh sách sản phẩm</a> nhé.</p>',
                'actions' => $this->defaultActions(),
            ]);
        }
    }

    protected function buildConsultationReply(Request $request, $message, $normalizedMessage, array $terms, array $context)
    {
        $filters = $this->extractConsultationFilters($message, $normalizedMessage, $terms);

        if ($this->shouldPreferFaqReply($normalizedMessage, $filters)) {
            return null;
        }

        $isFollowUp = $this->isFollowUpMessage($normalizedMessage, $filters, $context)
            && !$this->shouldStartFreshSearch($filters);

        if (!$filters['should_consult'] && !$isFollowUp) {
            return null;
        }

        $filters = $this->mergeFiltersWithContext($filters, $context, $isFollowUp);

        if (!$this->hasSearchableFilters($filters)) {
            $this->rememberConversationContext($request, [
                'filters' => [],
                'product_ids' => [],
                'price_anchor' => null,
                'reply_type' => 'consultation-prompt',
            ]);

            return $this->buildConsultationPromptReply();
        }

        $products = $this->searchProductsForConsultation($filters);

        if ($products->isEmpty()) {
            $alternatives = $this->searchAlternativeProducts($filters);
            $reply = $this->buildNoResultConsultationReply($filters, $alternatives['products'], $alternatives['reason']);

            if ($alternatives['products']->isNotEmpty()) {
                $this->rememberProductResults($request, $alternatives['products'], $filters, 'consultation-alternative');
            } else {
                $this->rememberConversationContext($request, [
                    'filters' => $this->sanitizeFiltersForSession($filters),
                    'product_ids' => [],
                    'price_anchor' => isset($context['price_anchor']) ? $context['price_anchor'] : null,
                    'reply_type' => 'consultation-no-match',
                ]);
            }

            return $reply;
        }

        $reply = $this->buildConsultationProductsReply($filters, $products, $isFollowUp);
        $this->rememberProductResults($request, $products, $filters, 'consultation');

        return $reply;
    }

    protected function extractConsultationFilters($message, $normalizedMessage, array $terms)
    {
        $matchedBrands = $this->findMatchedBrands($normalizedMessage, $terms);
        $matchedCategories = $this->findMatchedCategories($normalizedMessage, $terms);
        $featurePreferences = $this->detectFeaturePreferences($normalizedMessage);
        $priceRange = $this->parsePriceRange($message);

        $stockStatus = null;
        if ($this->containsAny($normalizedMessage, ['het hang', 'tam het'])) {
            $stockStatus = 'out';
        } elseif ($this->containsAny($normalizedMessage, ['con hang', 'co hang', 'ton kho'])) {
            $stockStatus = 'in';
        }

        $condition = null;
        $conditionLabel = null;
        if ($this->containsAny($normalizedMessage, ['mau moi', 'san pham moi', 'hang moi', 'moi nhat'])) {
            $condition = 'new';
            $conditionLabel = 'mẫu mới';
        } elseif ($this->containsAny($normalizedMessage, ['ban chay', 'hot', 'noi bat'])) {
            $condition = 'hot';
            $conditionLabel = 'bán chạy';
        }

        $onlyDiscounted = $this->containsAny($normalizedMessage, ['khuyen mai', 'giam gia', 'sale', 'uu dai']);
        $asksForConsultation = $this->isConsultationMessage($normalizedMessage);
        $wantsCheaper = $this->containsAny($normalizedMessage, ['re hon', 'mau re hon', 'gia mem hon', 'thap hon']);
        $wantsPremium = $this->containsAny($normalizedMessage, ['cao cap hon', 'xin hon', 'tot hon']);

        $brandIds = [];
        $brandLabels = [];
        foreach ($matchedBrands as $brand) {
            $brandIds[] = $brand->id;
            $brandLabels[] = $brand->title;
        }

        $categoryIds = [];
        $categoryLabels = [];
        foreach ($matchedCategories as $category) {
            $categoryIds[] = $category->id;
            $categoryLabels[] = $category->title;
        }

        $featureTerms = [];
        $featureLabels = [];
        foreach ($featurePreferences as $feature) {
            foreach ($feature['search_terms'] as $searchTerm) {
                $featureTerms[] = $searchTerm;
            }
            $featureLabels[] = $feature['label'];
        }

        $hasSpecificFilter = !empty($brandIds)
            || !empty($categoryIds)
            || !empty($featureTerms)
            || $priceRange['min'] !== null
            || $priceRange['max'] !== null
            || $stockStatus !== null
            || $condition !== null
            || $onlyDiscounted;

        return [
            'brand_ids' => array_values(array_unique($brandIds)),
            'brand_labels' => array_values(array_unique($brandLabels)),
            'category_ids' => array_values(array_unique($categoryIds)),
            'category_labels' => array_values(array_unique($categoryLabels)),
            'feature_terms' => array_values(array_unique($featureTerms)),
            'feature_labels' => array_values(array_unique($featureLabels)),
            'price_min' => $priceRange['min'],
            'price_max' => $priceRange['max'],
            'stock_status' => $stockStatus,
            'only_discounted' => $onlyDiscounted,
            'condition' => $condition,
            'condition_label' => $conditionLabel,
            'asks_for_consultation' => $asksForConsultation,
            'wants_cheaper' => $wantsCheaper,
            'wants_premium' => $wantsPremium,
            'normalized_message' => $normalizedMessage,
            'terms' => $terms,
            'has_explicit_brand' => !empty($brandIds),
            'has_explicit_category' => !empty($categoryIds),
            'has_explicit_feature' => !empty($featureTerms),
            'has_explicit_price' => $priceRange['min'] !== null || $priceRange['max'] !== null,
            'has_explicit_stock' => $stockStatus !== null,
            'has_explicit_discount' => $onlyDiscounted,
            'has_explicit_condition' => $condition !== null,
            'has_specific_filter' => $hasSpecificFilter,
            'should_consult' => $asksForConsultation || $hasSpecificFilter,
        ];
    }

    protected function findMatchedBrands($normalizedMessage, array $terms)
    {
        $brands = Brand::where('status', 'active')->orderBy('title', 'ASC')->get();
        $matches = [];

        foreach ($brands as $brand) {
            $normalizedTitle = $this->normalizeText($brand->title);

            if ($normalizedTitle === '') {
                continue;
            }

            if (strpos($normalizedMessage, $normalizedTitle) !== false || in_array($normalizedTitle, $terms, true)) {
                $matches[] = $brand;
            }
        }

        return collect($matches)->unique('id')->values();
    }

    protected function findMatchedCategories($normalizedMessage, array $terms)
    {
        $categories = Category::where('status', 'active')->orderBy('title', 'ASC')->get();
        $matches = [];

        foreach ($categories as $category) {
            $normalizedTitle = $this->normalizeText($category->title);
            $normalizedSlug = $this->normalizeText(str_replace('-', ' ', $category->slug));

            if ($normalizedTitle !== '' && strpos($normalizedMessage, $normalizedTitle) !== false) {
                $matches[] = $category;
                continue;
            }

            if ($normalizedSlug !== '' && strpos($normalizedMessage, $normalizedSlug) !== false) {
                $matches[] = $category;
                continue;
            }

            foreach ($terms as $term) {
                if ($term !== '' && (strpos($normalizedTitle, $term) !== false || strpos($normalizedSlug, $term) !== false)) {
                    $matches[] = $category;
                    break;
                }
            }
        }

        foreach ($this->buildCategoryAliasMap() as $slug => $keywords) {
            if (!$this->containsAny($normalizedMessage, $keywords)) {
                continue;
            }

            $category = $categories->first(function ($item) use ($slug) {
                return $item->slug === $slug;
            });

            if ($category) {
                $matches[] = $category;
            }
        }

        return collect($matches)->unique('id')->values();
    }

    protected function buildCategoryAliasMap()
    {
        return [
            'tai-nghe-true-wireless' => ['true wireless', 'tws', 'in ear', 'earbuds'],
            'tai-nghe-chup-tai' => ['chup tai', 'over ear', 'on ear'],
            'phu-kien-am-thanh' => ['phu kien', 'dac', 'usb c', 'hop dung', 'adapter'],
        ];
    }

    protected function detectFeaturePreferences($normalizedMessage)
    {
        $matches = [];

        foreach ($this->buildFeatureMap() as $key => $feature) {
            if ($this->containsAny($normalizedMessage, $feature['keywords'])) {
                $matches[$key] = $feature;
            }
        }

        return array_values($matches);
    }

    protected function buildFeatureMap()
    {
        return [
            'bluetooth' => [
                'keywords' => ['bluetooth', 'khong day', 'wireless'],
                'search_terms' => ['bluetooth', 'wireless'],
                'label' => 'Bluetooth/không dây',
            ],
            'true_wireless' => [
                'keywords' => ['true wireless', 'tws', 'in ear'],
                'search_terms' => ['true wireless', 'tws', 'in ear'],
                'label' => 'true wireless',
            ],
            'gaming' => [
                'keywords' => ['gaming', 'choi game', 'game'],
                'search_terms' => ['gaming', 'game', 'do tre thap'],
                'label' => 'chơi game',
            ],
            'work' => [
                'keywords' => ['lam viec', 'hoc online', 'hop online', 'goi dien'],
                'search_terms' => ['lam viec', 'hoc online', 'mic', 'micro'],
                'label' => 'làm việc/học online',
            ],
            'noise_cancel' => [
                'keywords' => ['chong on', 'anc', 'tap trung', 'xuyen am'],
                'search_terms' => ['chong on', 'anc', 'xuyen am'],
                'label' => 'chống ồn',
            ],
            'music' => [
                'keywords' => ['nghe nhac', 'am hay', 'chi tiet', 'bass', 'vocal'],
                'search_terms' => ['chi tiet', 'bass', 'vocal', 'am thanh'],
                'label' => 'nghe nhạc',
            ],
            'light' => [
                'keywords' => ['gon nhe', 'di lai', 'di chuyen', 'thoang tai'],
                'search_terms' => ['gon', 'nhe', 'thoang'],
                'label' => 'gọn nhẹ/di chuyển',
            ],
            'battery' => [
                'keywords' => ['pin trau', 'pin lau', 'pin tot', 'thoi luong pin'],
                'search_terms' => ['pin', 'gio'],
                'label' => 'pin lâu',
            ],
            'mic' => [
                'keywords' => ['mic', 'micro'],
                'search_terms' => ['mic', 'micro'],
                'label' => 'mic rõ',
            ],
            'accessory' => [
                'keywords' => ['phu kien', 'dac', 'usb c'],
                'search_terms' => ['phu kien', 'dac', 'usb c'],
                'label' => 'phụ kiện/DAC',
            ],
        ];
    }

    protected function parsePriceRange($message)
    {
        $text = $this->normalizeLooseText($message);
        $result = [
            'min' => null,
            'max' => null,
        ];

        if (preg_match('/(?:tu\s+)?(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?\s*(?:den|toi|-)\s*(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)) {
            $min = $this->convertPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
            $max = $this->convertPriceToNumber($matches[3], isset($matches[4]) ? $matches[4] : null);

            if ($min !== null && $max !== null) {
                $result['min'] = min($min, $max);
                $result['max'] = max($min, $max);

                return $result;
            }
        }

        if (preg_match('/duoi\s+(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)) {
            $result['max'] = $this->convertPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
        }

        if (preg_match('/(?:tren|hon)\s+(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)?/', $text, $matches)) {
            $result['min'] = $this->convertPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
        }

        if ($result['max'] === null && $this->containsAny($this->normalizeText($text), ['re', 'gia mem', 'binh dan', 'pho thong'])) {
            $result['max'] = 1500000;
        }

        if ($result['min'] === null && $result['max'] === null) {
            if (preg_match('/(\d+(?:[.,]\d+)?)\s*(tr|trieu|cu|k|nghin|ngan)/', $text, $matches)) {
                $exactPrice = $this->convertPriceToNumber($matches[1], isset($matches[2]) ? $matches[2] : null);
                if ($exactPrice !== null) {
                    $result['max'] = (int) round($exactPrice * 1.2);
                    $result['min'] = (int) max(0, round($exactPrice * 0.8));
                }
            }
        }

        return $result;
    }

    protected function normalizeLooseText($text)
    {
        $normalizedText = Str::lower(Str::ascii((string) $text));
        $normalizedText = preg_replace('/(\d+)\s*(tr|trieu|cu|k|nghin|ngan)\s*(\d+)\b/i', '$1.$3$2', $normalizedText);
        $normalizedText = preg_replace('/[^a-z0-9\s\.,\-]/', ' ', $normalizedText);

        return trim(preg_replace('/\s+/', ' ', $normalizedText));
    }

    protected function convertPriceToNumber($value, $unit = null)
    {
        $normalizedValue = str_replace(',', '.', (string) $value);
        $amount = (float) $normalizedValue;
        $normalizedUnit = trim((string) $unit);

        if ($normalizedUnit === '' && $amount < 1000) {
            return null;
        }

        if (in_array($normalizedUnit, ['tr', 'trieu', 'cu'], true)) {
            return (int) round($amount * 1000000);
        }

        if (in_array($normalizedUnit, ['k', 'nghin', 'ngan'], true)) {
            return (int) round($amount * 1000);
        }

        return (int) round($amount);
    }

    protected function isConsultationMessage($normalizedMessage)
    {
        return $this->containsAny($normalizedMessage, [
            'tu van',
            'goi y',
            'nen chon',
            'chon gi',
            'phu hop',
            'mau nao',
            'tim tai nghe',
            'tai nghe nao',
            'ngan sach',
            'tam gia',
        ]);
    }

    protected function shouldPreferFaqReply($normalizedMessage, array $filters)
    {
        return $this->isSupportTopicMessage($normalizedMessage)
            && !$filters['asks_for_consultation'];
    }

    protected function isSupportTopicMessage($normalizedMessage)
    {
        return $this->containsAny($normalizedMessage, [
            'bao hanh',
            'doi tra',
            'hoan tra',
            'doi hang',
            'giao hang',
            'ship',
            'shipping',
            'van chuyen',
            'phi ship',
            'freeship',
            'chinh sach',
        ]);
    }

    protected function isFollowUpMessage($normalizedMessage, array $filters, array $context)
    {
        if (empty($context['filters']) || !is_array($context['filters'])) {
            return false;
        }

        if ($this->isSupportTopicMessage($normalizedMessage)) {
            return false;
        }

        if ($filters['has_specific_filter']) {
            return true;
        }

        if ($filters['wants_cheaper'] || $filters['wants_premium']) {
            return true;
        }

        if ($this->containsAny($normalizedMessage, [
            're hon',
            'cao cap hon',
            'khac',
            'them',
            'co mau nao',
            'con hang',
            'het hang',
            'bluetooth',
            'gaming',
            'chong on',
            'duoi',
            'tren',
        ])) {
            return true;
        }

        return false;
    }

    protected function mergeFiltersWithContext(array $filters, array $context, $isFollowUp)
    {
        if (!$isFollowUp || empty($context['filters']) || !is_array($context['filters'])) {
            return $filters;
        }

        if ($this->shouldStartFreshSearch($filters)) {
            return $filters;
        }

        $contextFilters = $context['filters'];

        if (!$filters['has_explicit_brand']) {
            $filters['brand_ids'] = isset($contextFilters['brand_ids']) ? $contextFilters['brand_ids'] : [];
            $filters['brand_labels'] = isset($contextFilters['brand_labels']) ? $contextFilters['brand_labels'] : [];
        }

        if (!$filters['has_explicit_category']) {
            $filters['category_ids'] = isset($contextFilters['category_ids']) ? $contextFilters['category_ids'] : [];
            $filters['category_labels'] = isset($contextFilters['category_labels']) ? $contextFilters['category_labels'] : [];
        }

        if (!$filters['has_explicit_feature']) {
            $filters['feature_terms'] = isset($contextFilters['feature_terms']) ? $contextFilters['feature_terms'] : [];
            $filters['feature_labels'] = isset($contextFilters['feature_labels']) ? $contextFilters['feature_labels'] : [];
        }

        if (!$filters['has_explicit_price']) {
            $filters['price_min'] = isset($contextFilters['price_min']) ? $contextFilters['price_min'] : null;
            $filters['price_max'] = isset($contextFilters['price_max']) ? $contextFilters['price_max'] : null;
        }

        if (!$filters['has_explicit_stock']) {
            $filters['stock_status'] = isset($contextFilters['stock_status']) ? $contextFilters['stock_status'] : null;
        }

        if (!$filters['has_explicit_discount']) {
            $filters['only_discounted'] = !empty($contextFilters['only_discounted']);
        }

        if (!$filters['has_explicit_condition']) {
            $filters['condition'] = isset($contextFilters['condition']) ? $contextFilters['condition'] : null;
            $filters['condition_label'] = isset($contextFilters['condition_label']) ? $contextFilters['condition_label'] : null;
        }

        if ($filters['wants_cheaper'] && !$filters['has_explicit_price']) {
            $anchorPrice = isset($context['price_anchor']) ? (int) $context['price_anchor'] : 0;

            if ($anchorPrice > 0) {
                $filters['price_max'] = (int) max(300000, floor($anchorPrice * 0.85));
                $filters['price_min'] = null;
            }
        }

        if ($filters['wants_premium'] && !$filters['has_explicit_price']) {
            $anchorPrice = isset($context['price_anchor']) ? (int) $context['price_anchor'] : 0;

            if ($anchorPrice > 0) {
                $filters['price_min'] = (int) ceil($anchorPrice * 1.1);
            }
        }

        $filters['brand_ids'] = array_values(array_unique($filters['brand_ids']));
        $filters['brand_labels'] = array_values(array_unique($filters['brand_labels']));
        $filters['category_ids'] = array_values(array_unique($filters['category_ids']));
        $filters['category_labels'] = array_values(array_unique($filters['category_labels']));
        $filters['feature_terms'] = array_values(array_unique($filters['feature_terms']));
        $filters['feature_labels'] = array_values(array_unique($filters['feature_labels']));
        $filters['has_specific_filter'] = $this->hasSearchableFilters($filters);

        return $filters;
    }

    protected function shouldStartFreshSearch(array $filters)
    {
        return ($filters['has_explicit_brand'] || $filters['has_explicit_category'])
            && !$filters['has_explicit_price']
            && !$filters['has_explicit_feature']
            && !$filters['has_explicit_stock']
            && !$filters['has_explicit_discount']
            && !$filters['has_explicit_condition']
            && !$filters['wants_cheaper']
            && !$filters['wants_premium'];
    }

    protected function hasSearchableFilters(array $filters)
    {
        return !empty($filters['brand_ids'])
            || !empty($filters['category_ids'])
            || !empty($filters['feature_terms'])
            || $filters['price_min'] !== null
            || $filters['price_max'] !== null
            || !empty($filters['stock_status'])
            || !empty($filters['condition'])
            || !empty($filters['only_discounted']);
    }

    protected function searchProductsForConsultation(array $filters)
    {
        $query = Product::with(['brand', 'cat_info'])
            ->where('status', 'active');

        if (!empty($filters['brand_ids'])) {
            $query->whereIn('brand_id', $filters['brand_ids']);
        }

        if (!empty($filters['category_ids'])) {
            $query->whereIn('cat_id', $filters['category_ids']);
        }

        if ($filters['stock_status'] === 'in') {
            $query->where('stock', '>', 0);
        }

        if ($filters['stock_status'] === 'out') {
            $query->where('stock', '<=', 0);
        }

        if (!empty($filters['only_discounted'])) {
            $query->where('discount', '>', 0);
        }

        if (!empty($filters['condition'])) {
            $query->where('condition', $filters['condition']);
        }

        $products = $query->orderBy('id', 'DESC')->get();
        $rankedProducts = [];

        foreach ($products as $product) {
            $finalPrice = $this->calculateFinalPrice($product);

            if ($filters['price_min'] !== null && $finalPrice < $filters['price_min']) {
                continue;
            }

            if ($filters['price_max'] !== null && $finalPrice > $filters['price_max']) {
                continue;
            }

            $rankedProducts[] = [
                'product' => $product,
                'score' => $this->scoreConsultationProduct($product, $filters),
                'final_price' => $finalPrice,
            ];
        }

        if (empty($rankedProducts)) {
            return collect();
        }

        usort($rankedProducts, function ($left, $right) {
            if ($left['score'] === $right['score']) {
                if ($left['product']->stock === $right['product']->stock) {
                    if ((float) $left['product']->discount === (float) $right['product']->discount) {
                        return $right['product']->id <=> $left['product']->id;
                    }

                    return (float) $right['product']->discount <=> (float) $left['product']->discount;
                }

                return (int) $right['product']->stock <=> (int) $left['product']->stock;
            }

            return $right['score'] <=> $left['score'];
        });

        $topScore = $rankedProducts[0]['score'];
        $minimumScore = max(2, $topScore - 3);
        $filteredProducts = [];

        foreach ($rankedProducts as $item) {
            if ($item['score'] < $minimumScore && count($filteredProducts) >= 2) {
                continue;
            }

            $filteredProducts[] = $item['product'];

            if (count($filteredProducts) >= 4) {
                break;
            }
        }

        return collect($filteredProducts);
    }

    protected function scoreConsultationProduct(Product $product, array $filters)
    {
        $score = 0;
        $haystack = $this->getProductSearchHaystack($product);
        $finalPrice = $this->calculateFinalPrice($product);

        if (!empty($filters['brand_ids']) && in_array($product->brand_id, $filters['brand_ids'], true)) {
            $score += 8;
        }

        if (!empty($filters['category_ids']) && in_array($product->cat_id, $filters['category_ids'], true)) {
            $score += 6;
        }

        if ($filters['stock_status'] === 'in' && $product->stock > 0) {
            $score += 4;
        }

        if ($filters['stock_status'] === 'out' && $product->stock <= 0) {
            $score += 4;
        }

        if (!empty($filters['only_discounted']) && (float) $product->discount > 0) {
            $score += 4;
        }

        if (!empty($filters['condition']) && $product->condition === $filters['condition']) {
            $score += 4;
        }

        if ($filters['price_min'] !== null || $filters['price_max'] !== null) {
            $score += 3;

            if ($filters['price_min'] !== null && $filters['price_max'] !== null) {
                $targetPrice = ($filters['price_min'] + $filters['price_max']) / 2;
                $distance = abs($finalPrice - $targetPrice);
                $score += max(0, 3 - (int) floor($distance / 700000));
            }
        }

        foreach ($filters['feature_terms'] as $featureTerm) {
            if ($featureTerm !== '' && strpos($haystack, $this->normalizeText($featureTerm)) !== false) {
                $score += 3;
            }
        }

        foreach ($filters['terms'] as $term) {
            if ($term !== '' && strpos($haystack, $term) !== false) {
                $score += 1;
            }
        }

        if ((float) $product->discount > 0) {
            $score += 1;
        }

        if ($product->stock > 0) {
            $score += 1;
        }

        if ($product->condition === 'new' || $product->condition === 'hot') {
            $score += 1;
        }

        return $score;
    }

    protected function getProductSearchHaystack(Product $product)
    {
        $pieces = [
            $product->title,
            $product->summary,
            $product->description,
            optional($product->brand)->title,
            optional($product->cat_info)->title,
        ];

        return $this->normalizeText(implode(' ', $pieces));
    }

    protected function searchAlternativeProducts(array $filters)
    {
        $attempts = [];
        $attempts[] = [
            'reason' => 'Mình đã nới bớt yêu cầu về tính năng để lấy các mẫu gần nhất.',
            'filters' => array_merge($filters, [
                'feature_terms' => [],
                'feature_labels' => [],
            ]),
        ];

        if ($filters['price_max'] !== null || $filters['price_min'] !== null) {
            $attempts[] = [
                'reason' => 'Mình đã nới nhẹ mức giá để tìm thêm lựa chọn lân cận.',
                'filters' => array_merge($filters, [
                    'price_min' => $filters['price_min'] !== null ? (int) max(0, floor($filters['price_min'] * 0.85)) : null,
                    'price_max' => $filters['price_max'] !== null ? (int) ceil($filters['price_max'] * 1.2) : null,
                ]),
            ];
        }

        if (!empty($filters['brand_ids'])) {
            $attempts[] = [
                'reason' => 'Mình tạm bỏ lọc thương hiệu để lấy các mẫu sát nhu cầu hơn.',
                'filters' => array_merge($filters, [
                    'brand_ids' => [],
                    'brand_labels' => [],
                ]),
            ];
        }

        foreach ($attempts as $attempt) {
            $products = $this->searchProductsForConsultation($attempt['filters']);

            if ($products->isNotEmpty()) {
                return [
                    'products' => $products,
                    'reason' => $attempt['reason'],
                ];
            }
        }

        return [
            'products' => collect(),
            'reason' => null,
        ];
    }

    protected function buildConsultationProductsReply(array $filters, Collection $products, $isFollowUp)
    {
        $summary = $this->buildFilterSummary($filters);
        $html = '';

        if ($summary !== '') {
            $html .= '<p>' . e($isFollowUp ? 'Mình lọc tiếp theo ngữ cảnh bạn vừa hỏi' : 'Mình đã lọc theo nhu cầu của bạn') . ': <strong>' . e($summary) . '</strong>.</p>';
        } else {
            $html .= '<p>Mình đã chọn ra một vài mẫu phù hợp nhất với nội dung bạn vừa hỏi.</p>';
        }

        $html .= '<ul>';

        foreach ($products as $product) {
            $reasons = $this->buildProductReasonList($product, $filters);
            $html .= '<li><a href="' . e(route('product-detail', $product->slug)) . '">' . e($product->title) . '</a> - ' . e($this->formatCurrency($this->calculateFinalPrice($product)));

            if (!empty($reasons)) {
                $html .= '<br><small>Phù hợp vì: ' . e(implode(', ', $reasons)) . '</small>';
            }

            $html .= '<br><small>' . e($this->buildProductMeta($product)) . '</small></li>';
        }

        $html .= '</ul>';
        $html .= '<p>Bạn có thể nhắn tiếp theo kiểu "rẻ hơn", "dưới 2 triệu", "Sony", "Bluetooth", "chống ồn" hoặc "còn hàng" để mình lọc sâu thêm.</p>';

        return [
            'html' => $html,
            'actions' => $this->buildConsultationActions($filters, $products),
        ];
    }

    protected function buildProductReasonList(Product $product, array $filters)
    {
        $reasons = [];
        $haystack = $this->getProductSearchHaystack($product);
        $finalPrice = $this->calculateFinalPrice($product);

        if (!empty($filters['brand_labels']) && optional($product->brand)->title) {
            $reasons[] = 'đúng thương hiệu ' . optional($product->brand)->title;
        }

        if (!empty($filters['category_labels']) && optional($product->cat_info)->title) {
            $reasons[] = 'thuộc nhóm ' . optional($product->cat_info)->title;
        }

        foreach ($filters['feature_labels'] as $index => $label) {
            $searchTerm = isset($filters['feature_terms'][$index]) ? $filters['feature_terms'][$index] : null;

            if ($searchTerm && strpos($haystack, $this->normalizeText($searchTerm)) !== false) {
                $reasons[] = $label;
            }
        }

        if ($filters['price_min'] !== null || $filters['price_max'] !== null) {
            $reasons[] = 'nằm trong tầm giá ' . $this->formatCurrency($finalPrice);
        }

        if (!empty($filters['only_discounted']) && (float) $product->discount > 0) {
            $reasons[] = 'đang giảm ' . (int) $product->discount . '%';
        }

        if ($filters['stock_status'] === 'in' && $product->stock > 0) {
            $reasons[] = 'còn ' . (int) $product->stock . ' sản phẩm';
        }

        if (!empty($filters['condition_label'])) {
            $reasons[] = $filters['condition_label'];
        }

        return array_values(array_unique(array_slice($reasons, 0, 3)));
    }

    protected function buildConsultationPromptReply()
    {
        return [
            'html' => '<p>Mình có thể tư vấn sâu hơn nếu bạn cho mình 1 đến 2 tiêu chí chính.</p><p>Bạn thử nhắn theo một trong các kiểu này nhé: "tai nghe dưới 1 triệu", "Bluetooth chống ồn", "Sony để làm việc", "mẫu chơi game" hoặc "còn hàng".</p>',
            'actions' => [
                ['label' => 'Dưới 1 triệu', 'value' => 'tai nghe dưới 1 triệu'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'],
                ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'],
                ['label' => 'Sony', 'value' => 'tai nghe Sony'],
            ],
        ];
    }

    protected function buildNoResultConsultationReply(array $filters, Collection $products, $reason = null)
    {
        $summary = $this->buildFilterSummary($filters);
        $html = '<p>Mình chưa thấy mẫu khớp hoàn toàn';

        if ($summary !== '') {
            $html .= ' với tiêu chí <strong>' . e($summary) . '</strong>';
        }

        $html .= '.</p>';

        if ($reason) {
            $html .= '<p>' . e($reason) . '</p>';
        }

        if ($products->isNotEmpty()) {
            $html .= '<ul>';

            foreach ($products as $product) {
                $html .= '<li><a href="' . e(route('product-detail', $product->slug)) . '">' . e($product->title) . '</a> - ' . e($this->formatCurrency($this->calculateFinalPrice($product))) . '<br><small>' . e($this->buildProductMeta($product)) . '</small></li>';
            }

            $html .= '</ul>';
            $html .= '<p>Nếu muốn, bạn cứ nhắn lại tiêu chí ưu tiên nhất như ngân sách, thương hiệu hoặc nhu cầu sử dụng để mình siết lọc theo đúng thứ bạn quan tâm nhất.</p>';
        } else {
            $html .= '<p>Bạn thử nới ngân sách, bỏ bớt một tiêu chí, hoặc đổi sang thương hiệu khác để mình tìm tiếp cho sát hơn.</p>';
        }

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Rẻ hơn', 'value' => 'rẻ hơn'],
                ['label' => 'Khuyến mãi', 'value' => 'khuyến mãi'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ shop'],
            ],
        ];
    }

    protected function buildConsultationActions(array $filters, Collection $products)
    {
        $actions = [];

        if ($products->isNotEmpty()) {
            $actions[] = ['label' => 'Rẻ hơn', 'value' => 'rẻ hơn'];
        }

        if ($filters['stock_status'] !== 'in') {
            $actions[] = ['label' => 'Còn hàng', 'value' => 'còn hàng'];
        }

        if (empty($filters['only_discounted'])) {
            $actions[] = ['label' => 'Khuyến mãi', 'value' => 'khuyến mãi'];
        }

        if (!in_array('Bluetooth/không dây', $filters['feature_labels'], true)) {
            $actions[] = ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'];
        }

        if (!in_array('chơi game', $filters['feature_labels'], true)) {
            $actions[] = ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'];
        }

        if (!in_array('chống ồn', $filters['feature_labels'], true)) {
            $actions[] = ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'];
        }

        foreach ($this->defaultActions() as $defaultAction) {
            $actions[] = $defaultAction;
        }

        $uniqueActions = [];
        $seenValues = [];

        foreach ($actions as $action) {
            $value = isset($action['value']) ? $action['value'] : $action['label'];

            if (in_array($value, $seenValues, true)) {
                continue;
            }

            $seenValues[] = $value;
            $uniqueActions[] = $action;

            if (count($uniqueActions) >= 5) {
                break;
            }
        }

        return $uniqueActions;
    }

    protected function buildFilterSummary(array $filters)
    {
        $parts = [];

        if (!empty($filters['brand_labels'])) {
            $parts[] = implode(', ', $filters['brand_labels']);
        }

        if (!empty($filters['category_labels'])) {
            $parts[] = implode(', ', $filters['category_labels']);
        }

        if (!empty($filters['feature_labels'])) {
            $parts[] = implode(', ', array_slice($filters['feature_labels'], 0, 2));
        }

        if ($filters['price_min'] !== null && $filters['price_max'] !== null) {
            $parts[] = 'từ ' . $this->formatCurrency($filters['price_min']) . ' đến ' . $this->formatCurrency($filters['price_max']);
        } elseif ($filters['price_max'] !== null) {
            $parts[] = 'dưới ' . $this->formatCurrency($filters['price_max']);
        } elseif ($filters['price_min'] !== null) {
            $parts[] = 'trên ' . $this->formatCurrency($filters['price_min']);
        }

        if ($filters['stock_status'] === 'in') {
            $parts[] = 'còn hàng';
        }

        if ($filters['stock_status'] === 'out') {
            $parts[] = 'tạm hết hàng';
        }

        if (!empty($filters['only_discounted'])) {
            $parts[] = 'đang khuyến mãi';
        }

        if (!empty($filters['condition_label'])) {
            $parts[] = $filters['condition_label'];
        }

        return implode(', ', $parts);
    }

    protected function sanitizeFiltersForSession(array $filters)
    {
        return [
            'brand_ids' => $filters['brand_ids'],
            'brand_labels' => $filters['brand_labels'],
            'category_ids' => $filters['category_ids'],
            'category_labels' => $filters['category_labels'],
            'feature_terms' => $filters['feature_terms'],
            'feature_labels' => $filters['feature_labels'],
            'price_min' => $filters['price_min'],
            'price_max' => $filters['price_max'],
            'stock_status' => $filters['stock_status'],
            'only_discounted' => $filters['only_discounted'],
            'condition' => $filters['condition'],
            'condition_label' => $filters['condition_label'],
        ];
    }

    protected function rememberProductResults(Request $request, Collection $products, array $filters, $replyType)
    {
        $focusProduct = $products->first();

        $this->rememberConversationContext($request, [
            'filters' => $this->sanitizeFiltersForSession($filters),
            'product_ids' => $products->pluck('id')->values()->all(),
            'price_anchor' => $focusProduct ? $this->calculateFinalPrice($focusProduct) : null,
            'reply_type' => $replyType,
        ]);
    }

    protected function rememberConversationContext(Request $request, array $context)
    {
        $request->session()->put(self::CONTEXT_SESSION_KEY, $context);
    }

    protected function getConversationContext(Request $request)
    {
        $context = $request->session()->get(self::CONTEXT_SESSION_KEY, []);

        return is_array($context) ? $context : [];
    }

    protected function clearConversationContext(Request $request)
    {
        $request->session()->forget(self::CONTEXT_SESSION_KEY);
    }

    protected function matchFaq($normalizedMessage, array $terms)
    {
        if (!Schema::hasTable('chatbot_faqs')) {
            return null;
        }

        $faqs = ChatbotFaq::where('status', 'active')
            ->orderBy('priority', 'ASC')
            ->get();

        $bestFaq = null;
        $bestScore = 0;

        foreach ($faqs as $faq) {
            $score = 0;
            $normalizedQuestion = $this->normalizeText($faq->question);
            $normalizedKeywords = $this->normalizeText($faq->keywords);
            $faqHaystack = trim($normalizedQuestion . ' ' . $normalizedKeywords);

            if ($normalizedQuestion !== '' && strpos($normalizedMessage, $normalizedQuestion) !== false) {
                $score += 5;
            }

            foreach (explode(',', (string) $faq->keywords) as $keyword) {
                $normalizedKeyword = $this->normalizeText($keyword);

                if ($normalizedKeyword !== '' && strpos($normalizedMessage, $normalizedKeyword) !== false) {
                    $score += 4;
                }
            }

            foreach ($terms as $term) {
                if ($term !== '' && strpos($faqHaystack, $term) !== false) {
                    $score += 1;
                }
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestFaq = $faq;
            }
        }

        if (!$bestFaq || $bestScore < 3) {
            return null;
        }

        $html = '<p>' . e($bestFaq->answer) . '</p>';

        if ($bestFaq->link_text && $bestFaq->link_url) {
            $html .= '<p><a href="' . e($bestFaq->link_url) . '">' . e($bestFaq->link_text) . '</a></p>';
        }

        return [
            'html' => $html,
            'actions' => $this->defaultActions(),
        ];
    }

    protected function matchBrands($normalizedMessage)
    {
        if (!$this->containsAny($normalizedMessage, ['thuong hieu', 'brand', 'hang nao'])) {
            return null;
        }

        $brands = Brand::withCount('products')
            ->where('status', 'active')
            ->orderBy('title', 'ASC')
            ->get();

        if ($brands->isEmpty()) {
            return null;
        }

        $html = '<p>Shop hiện có một vài thương hiệu nổi bật để bạn tham khảo nhanh:</p><ul>';

        foreach ($brands->take(5) as $brand) {
            $html .= '<li><a href="' . e(route('product-brand', $brand->slug)) . '">' . e($brand->title) . '</a> - ' . e((string) $brand->products_count) . ' sản phẩm</li>';
        }

        $html .= '</ul><p>Bạn có thể nhắn thẳng tên thương hiệu như Sony, JBL, Sennheiser hoặc SoundPEATS để mình lọc sâu hơn theo giá và nhu cầu sử dụng.</p>';

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Sony', 'value' => 'tai nghe Sony'],
                ['label' => 'JBL', 'value' => 'tai nghe JBL'],
                ['label' => 'Sennheiser', 'value' => 'tai nghe Sennheiser'],
                ['label' => 'SoundPEATS', 'value' => 'tai nghe SoundPEATS'],
            ],
        ];
    }

    protected function matchProducts(Request $request, $message, $normalizedMessage, array $terms)
    {
        $searchTerms = array_slice($terms, 0, 5);
        $messageSlug = Str::slug($message);

        if ($normalizedMessage === '' && empty($searchTerms)) {
            return null;
        }

        $query = Product::with(['brand', 'cat_info'])->where('status', 'active');
        $query->where(function ($productQuery) use ($message, $messageSlug, $searchTerms) {
            $productQuery->where('title', 'like', '%' . $message . '%')
                ->orWhere('summary', 'like', '%' . $message . '%')
                ->orWhere('description', 'like', '%' . $message . '%');

            if ($messageSlug !== '') {
                $productQuery->orWhere('slug', 'like', '%' . $messageSlug . '%');
            }

            foreach ($searchTerms as $term) {
                $productQuery->orWhere('title', 'like', '%' . $term . '%');
                $productQuery->orWhere('summary', 'like', '%' . $term . '%');
                $productQuery->orWhere('description', 'like', '%' . $term . '%');
                $productQuery->orWhere('slug', 'like', '%' . Str::slug($term) . '%');
            }
        });

        $products = $query->orderBy('id', 'DESC')->limit(8)->get();

        $rankedProducts = $products->map(function ($product) use ($normalizedMessage, $searchTerms) {
            $haystack = $this->getProductSearchHaystack($product);
            $score = 0;

            if ($normalizedMessage !== '' && strpos($haystack, $normalizedMessage) !== false) {
                $score += 7;
            }

            foreach ($searchTerms as $term) {
                if ($term !== '' && strpos($haystack, $term) !== false) {
                    $score += 2;
                }
            }

            return [
                'product' => $product,
                'score' => $score,
            ];
        })->filter(function ($item) {
            return $item['score'] > 0;
        })->sortByDesc('score')->values();

        if ($rankedProducts->isEmpty() || $rankedProducts->first()['score'] < 2) {
            return null;
        }

        $matchedProducts = $rankedProducts->take(4)->pluck('product')->values();
        $html = '<p>Mình tìm thấy một vài sản phẩm khá sát với nội dung bạn hỏi:</p><ul>';

        foreach ($matchedProducts as $product) {
            $html .= '<li><a href="' . e(route('product-detail', $product->slug)) . '">' . e($product->title) . '</a> - ' . e($this->formatCurrency($this->calculateFinalPrice($product))) . '<br><small>' . e($this->buildProductMeta($product)) . '</small></li>';
        }

        $html .= '</ul><p>Nếu bạn muốn, mình có thể lọc tiếp theo ngân sách, thương hiệu hoặc nhu cầu như Bluetooth, chống ồn hay chơi game.</p>';

        $this->rememberProductResults($request, $matchedProducts, [], 'product-search');

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Dưới 1 triệu', 'value' => 'dưới 1 triệu'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'],
                ['label' => 'Còn hàng', 'value' => 'còn hàng'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ shop'],
            ],
        ];
    }

    protected function matchCategories(Request $request, $message, $normalizedMessage, array $terms)
    {
        $searchTerms = array_slice($terms, 0, 5);
        $query = Category::withCount('products')->where('status', 'active')->where('is_parent', 1);

        if ($message !== '' || !empty($searchTerms)) {
            $query->where(function ($categoryQuery) use ($message, $searchTerms) {
                $categoryQuery->where('title', 'like', '%' . $message . '%');

                foreach ($searchTerms as $term) {
                    $categoryQuery->orWhere('title', 'like', '%' . $term . '%');
                    $categoryQuery->orWhere('summary', 'like', '%' . $term . '%');
                }
            });
        }

        $categories = $query->orderBy('title', 'ASC')->limit(4)->get();

        if ($categories->isEmpty()) {
            if (!$this->containsAny($normalizedMessage, ['danh muc', 'loai san pham', 'phan loai'])) {
                return null;
            }

            $categories = Category::withCount('products')
                ->where('status', 'active')
                ->where('is_parent', 1)
                ->orderBy('title', 'ASC')
                ->limit(4)
                ->get();
        }

        if ($categories->isEmpty()) {
            return null;
        }

        $html = '<p>Mình gợi ý một vài danh mục phù hợp để bạn xem nhanh:</p><ul>';

        foreach ($categories as $category) {
            $html .= '<li><a href="' . e(route('product-cat', $category->slug)) . '">' . e($category->title) . '</a> - ' . e((int) $category->products_count) . ' sản phẩm</li>';
        }

        $html .= '</ul><p>Bạn có thể nhắn tiếp kiểu "dưới 2 triệu", "Bluetooth", "chống ồn" hoặc "chơi game" để mình lọc luôn trong từng nhóm.</p>';

        $this->rememberConversationContext($request, [
            'filters' => [
                'category_ids' => $categories->pluck('id')->values()->all(),
                'category_labels' => $categories->pluck('title')->values()->all(),
                'brand_ids' => [],
                'brand_labels' => [],
                'feature_terms' => [],
                'feature_labels' => [],
                'price_min' => null,
                'price_max' => null,
                'stock_status' => null,
                'only_discounted' => false,
                'condition' => null,
                'condition_label' => null,
            ],
            'product_ids' => [],
            'price_anchor' => null,
            'reply_type' => 'category-suggestion',
        ]);

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Dưới 1 triệu', 'value' => 'dưới 1 triệu'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ'],
            ],
        ];
    }

    protected function matchPosts($normalizedMessage, array $terms)
    {
        $shouldSearchPosts = $this->containsAny($normalizedMessage, ['bai viet', 'tin tuc', 'huong dan', 'blog']);

        if (!$shouldSearchPosts && empty($terms)) {
            return null;
        }

        $searchTerms = array_slice($terms, 0, 5);
        $query = Post::where('status', 'active');

        $query->where(function ($postQuery) use ($searchTerms, $normalizedMessage) {
            $postQuery->where('title', 'like', '%' . $normalizedMessage . '%');

            foreach ($searchTerms as $term) {
                $postQuery->orWhere('title', 'like', '%' . $term . '%');
                $postQuery->orWhere('summary', 'like', '%' . $term . '%');
            }
        });

        $posts = $query->orderBy('id', 'DESC')->limit(3)->get();

        if ($posts->isEmpty()) {
            if (!$shouldSearchPosts) {
                return null;
            }

            $posts = Post::where('status', 'active')->orderBy('id', 'DESC')->limit(3)->get();
        }

        if ($posts->isEmpty()) {
            return null;
        }

        $html = '<p>Mình tìm thấy một vài bài viết liên quan để bạn đọc sâu hơn:</p><ul>';

        foreach ($posts as $post) {
            $summary = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) $post->summary))));
            $html .= '<li><a href="' . e(route('blog.detail', $post->slug)) . '">' . e($post->title) . '</a>';

            if ($summary !== '') {
                $html .= '<br><small>' . e(Str::limit($summary, 90)) . '</small>';
            }

            $html .= '</li>';
        }

        $html .= '</ul><p><a href="' . e(route('blog')) . '">Xem thêm bài viết</a> nếu bạn muốn đọc kỹ hơn trước khi chọn mua.</p>';

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'],
                ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ'],
            ],
        ];
    }

    protected function buildContactReply()
    {
        $settings = Settings::select('phone', 'email', 'address')->first();
        $html = '<p>Đây là thông tin liên hệ hiện có của shop:</p><ul>';

        if ($settings && $settings->phone) {
            $html .= '<li>Số điện thoại: <a href="tel:' . e($settings->phone) . '">' . e($settings->phone) . '</a></li>';
        }

        if ($settings && $settings->email) {
            $html .= '<li>Email: <a href="mailto:' . e($settings->email) . '">' . e($settings->email) . '</a></li>';
        }

        if ($settings && $settings->address) {
            $html .= '<li>Địa chỉ: ' . e($settings->address) . '</li>';
        }

        $html .= '<li><a href="' . e(route('contact')) . '">Mở trang liên hệ</a></li></ul><p>Nếu bạn còn đang phân vân sản phẩm nào phù hợp, mình vẫn có thể lọc và gợi ý ngay trong khung chat.</p>';

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Dưới 1 triệu', 'value' => 'tai nghe dưới 1 triệu'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Theo dõi đơn', 'value' => 'theo dõi đơn hàng'],
                ['label' => 'Bán chạy', 'value' => 'sản phẩm bán chạy'],
            ],
        ];
    }

    protected function buildGreetingReply()
    {
        return [
            'html' => '<p>Xin chào. Mình có thể giúp bạn tìm sản phẩm, lọc theo ngân sách, thương hiệu, nhu cầu như Bluetooth, chống ồn, chơi game hoặc kiểm tra giỏ hàng, bài viết và đơn hàng.</p><p>Bạn cứ nhắn tự nhiên như: "tai nghe dưới 1 triệu", "Sony chống ồn", "mẫu chơi game", "còn hàng" hoặc "liên hệ".</p>',
            'actions' => $this->defaultActions(),
        ];
    }

    protected function buildAboutReply()
    {
        $settings = Settings::select('short_des', 'description')->first();
        $shortDescription = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) optional($settings)->short_des))));
        $description = trim(preg_replace('/\s+/', ' ', strip_tags(html_entity_decode((string) optional($settings)->description))));

        $html = '<p>Một vài thông tin nhanh về shop:</p>';

        if ($shortDescription !== '') {
            $html .= '<p>' . e(Str::limit($shortDescription, 180)) . '</p>';
        } elseif ($description !== '') {
            $html .= '<p>' . e(Str::limit($description, 220)) . '</p>';
        } else {
            $html .= '<p>Shop đang cung cấp nhiều dòng tai nghe và phụ kiện âm thanh, kèm thêm nội dung tư vấn để bạn dễ chốt mẫu phù hợp hơn trước khi mua.</p>';
        }

        $html .= '<p><a href="' . e(route('about-us')) . '">Xem thêm về shop</a> hoặc nhắn luôn tiêu chí bạn cần để mình gợi ý tiếp.</p>';

        return [
            'html' => $html,
            'actions' => [
                ['label' => 'Dưới 1 triệu', 'value' => 'tai nghe dưới 1 triệu'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ'],
            ],
        ];
    }

    protected function buildOrderReply()
    {
        return [
            'html' => '<p>Bạn có thể theo dõi đơn hàng tại trang <a href="' . e(route('order.track')) . '">tra cứu đơn hàng</a>. Chỉ cần nhập thông tin theo mẫu của hệ thống là bạn sẽ xem được trạng thái xử lý và giao hàng.</p><p>Nếu cần hỗ trợ thêm, bạn cũng có thể mở <a href="' . e(route('contact')) . '">trang liên hệ</a>.</p>',
            'actions' => [
                ['label' => 'Liên hệ', 'value' => 'liên hệ shop'],
                ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
                ['label' => 'Dưới 1 triệu', 'value' => 'tai nghe dưới 1 triệu'],
            ],
        ];
    }

    protected function buildCartReply()
    {
        return [
            'html' => '<p>Nếu bạn đã chọn được sản phẩm, bạn có thể mở <a href="' . e(route('cart')) . '">giỏ hàng</a> để kiểm tra lại số lượng, giá và tiếp tục đặt hàng. Khi đã sẵn sàng, bạn có thể sang bước <a href="' . e(route('checkout')) . '">thanh toán</a>.</p><p>Nếu vẫn đang phân vân, bạn chỉ cần nhắn thêm ngân sách hoặc nhu cầu dùng để mình lọc tiếp.</p>',
            'actions' => [
                ['label' => 'Giỏ hàng', 'value' => 'giỏ hàng'],
                ['label' => 'Thanh toán', 'value' => 'thanh toán'],
                ['label' => 'Rẻ hơn', 'value' => 'rẻ hơn'],
                ['label' => 'Liên hệ', 'value' => 'liên hệ shop'],
            ],
        ];
    }

    protected function fallbackReply()
    {
        $categories = Category::where('status', 'active')
            ->where('is_parent', 1)
            ->orderBy('title', 'ASC')
            ->limit(3)
            ->get();

        $html = '<p>Mình chưa tìm được kết quả thật sát, nhưng bạn có thể bắt đầu nhanh bằng một trong các hướng sau:</p><ul>';

        foreach ($categories as $category) {
            $html .= '<li><a href="' . e(route('product-cat', $category->slug)) . '">' . e($category->title) . '</a></li>';
        }

        $html .= '</ul><p>Bạn thử nhắn cụ thể hơn như "tai nghe bluetooth dưới 2 triệu", "Sony", "chống ồn", "chơi game", "còn hàng" hoặc "liên hệ".</p>';

        return [
            'html' => $html,
            'actions' => $this->defaultActions(),
        ];
    }

    protected function defaultActions()
    {
        return [
            ['label' => 'Dưới 1 triệu', 'value' => 'tai nghe dưới 1 triệu'],
            ['label' => 'Bluetooth', 'value' => 'tai nghe bluetooth'],
            ['label' => 'Chống ồn', 'value' => 'tai nghe chống ồn'],
            ['label' => 'Chơi game', 'value' => 'tai nghe chơi game'],
            ['label' => 'Liên hệ', 'value' => 'liên hệ shop'],
        ];
    }

    protected function buildProductsListHtml($products, $intro, $linkText, $linkUrl)
    {
        $html = '<p>' . e($intro) . '</p><ul>';

        foreach ($products as $product) {
            $html .= '<li><a href="' . e(route('product-detail', $product->slug)) . '">' . e($product->title) . '</a> - ' . e($this->formatCurrency($this->calculateFinalPrice($product))) . '<br><small>' . e($this->buildProductMeta($product)) . '</small></li>';
        }

        $html .= '</ul><p><a href="' . e($linkUrl) . '">' . e($linkText) . '</a>.</p>';

        return $html;
    }

    protected function calculateFinalPrice(Product $product)
    {
        $discount = $product->discount ? (float) $product->discount : 0;

        return (float) $product->price - (((float) $product->price * $discount) / 100);
    }

    protected function formatCurrency($amount)
    {
        return number_format((float) $amount, 0) . ' ₫';
    }

    protected function buildProductMeta(Product $product)
    {
        $parts = [];

        if ((int) $product->discount > 0) {
            $parts[] = 'Giảm ' . (int) $product->discount . '%';
        }

        $parts[] = $product->stock > 0
            ? 'Còn ' . (int) $product->stock . ' sản phẩm'
            : 'Tạm hết hàng';

        if ($conditionLabel = $this->formatConditionLabel($product->condition)) {
            $parts[] = $conditionLabel;
        }

        return implode(' · ', $parts);
    }

    protected function formatConditionLabel($condition)
    {
        $conditionMap = [
            'new' => 'Hàng mới',
            'hot' => 'Bán chạy',
            'default' => 'Đang mở bán',
        ];

        return isset($conditionMap[$condition]) ? $conditionMap[$condition] : null;
    }

    protected function containsAny($text, array $keywords)
    {
        foreach ($keywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function isGreetingMessage($normalizedMessage)
    {
        $greetings = ['xin chao', 'chao shop', 'hello', 'alo', 'hi'];

        foreach ($greetings as $greeting) {
            if ($normalizedMessage === $greeting || strpos($normalizedMessage, $greeting . ' ') === 0) {
                return true;
            }
        }

        return false;
    }

    protected function extractTerms($text)
    {
        $stopWords = [
            'toi', 'minh', 'muon', 'can', 'hoi', 'shop', 'giup', 'voi', 'nhe', 'nha',
            'la', 've', 'co', 'khong', 'cho', 'xin', 'tu', 'van', 'mot', 'nhung',
            'cua', 'va', 'dang', 'tim', 'xem', 'mau', 'san', 'pham', 'bai', 'viet',
            'danh', 'muc', 'giao', 'hang', 'lien', 'he', 'don', 'order', 'tai', 'nghe',
            'nao', 'nen', 'chon', 'gi', 'theo', 'yeu', 'cau',
        ];

        $terms = array_filter(explode(' ', $text), function ($term) use ($stopWords) {
            return strlen($term) >= 2 && !in_array($term, $stopWords, true);
        });

        return array_values(array_unique($terms));
    }

    protected function normalizeText($text)
    {
        $normalizedText = Str::lower(Str::ascii((string) $text));
        $normalizedText = preg_replace('/[^a-z0-9\s]/', ' ', $normalizedText);

        return trim(preg_replace('/\s+/', ' ', $normalizedText));
    }
}
