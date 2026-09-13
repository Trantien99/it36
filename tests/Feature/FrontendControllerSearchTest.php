<?php

namespace Tests\Feature;

use App\Http\Controllers\FrontendController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use ReflectionMethod;
use Tests\TestCase;

class FrontendControllerSearchTest extends TestCase
{
    public function testWirelessAndNoiseCancelQueriesExpandIntoSmartSearchTerms()
    {
        $controller = new FrontendController();

        $normalized = $this->invokeControllerMethod($controller, 'normalizeProductSearchText', 'Tai nghe không dây chống ồn');
        $terms = $this->invokeControllerMethod($controller, 'extractProductSearchTerms', $normalized);
        $expandedTerms = $this->invokeControllerMethod($controller, 'expandProductSearchTerms', $normalized, $terms);

        $this->assertContains('wireless', $expandedTerms);
        $this->assertContains('bluetooth', $expandedTerms);
        $this->assertContains('anc', $expandedTerms);
        $this->assertContains('chong on', $expandedTerms);
    }

    public function testRelevantProductsGetHigherSearchScoresThanWeakMatches()
    {
        $controller = new FrontendController();
        $normalized = $this->invokeControllerMethod($controller, 'normalizeProductSearchText', 'Tai nghe Sony không dây chống ồn');
        $terms = $this->invokeControllerMethod($controller, 'extractProductSearchTerms', $normalized);
        $expandedTerms = $this->invokeControllerMethod($controller, 'expandProductSearchTerms', $normalized, $terms);
        $context = [
            'normalized' => $normalized,
            'terms' => $terms,
            'expanded_terms' => $expandedTerms,
            'brand_ids' => [1],
            'category_ids' => [10],
            'price_min' => null,
            'price_max' => null,
            'sort_by' => 'relevance',
        ];

        $bestMatch = new Product([
            'id' => 101,
            'title' => 'Sony WH-1000XM5',
            'slug' => 'sony-wh-1000xm5',
            'summary' => 'Tai nghe bluetooth chong on danh cho lam viec',
            'description' => 'Mau khong day cao cap voi anc va micro ro',
            'price' => 8990000,
            'discount' => 5,
            'stock' => 8,
            'condition' => 'new',
            'brand_id' => 1,
            'cat_id' => 10,
        ]);
        $bestMatch->setRelation('brand', new Brand(['id' => 1, 'title' => 'Sony']));
        $bestMatch->setRelation('cat_info', new Category(['id' => 10, 'title' => 'Tai nghe chup tai']));

        $weakMatch = new Product([
            'id' => 102,
            'title' => 'Loa bluetooth mini',
            'slug' => 'loa-bluetooth-mini',
            'summary' => 'Phu kien nghe nhac co ban',
            'description' => 'Mau nho gon cho ban hoc',
            'price' => 690000,
            'discount' => 0,
            'stock' => 2,
            'condition' => 'default',
            'brand_id' => 2,
            'cat_id' => 12,
        ]);
        $weakMatch->setRelation('brand', new Brand(['id' => 2, 'title' => 'JBL']));
        $weakMatch->setRelation('cat_info', new Category(['id' => 12, 'title' => 'Phu kien am thanh']));

        $bestScore = $this->invokeControllerMethod($controller, 'scoreProductSearchMatch', $bestMatch, $context);
        $weakScore = $this->invokeControllerMethod($controller, 'scoreProductSearchMatch', $weakMatch, $context);
        $rankedProducts = $this->invokeControllerMethod(
            $controller,
            'rankProductSearchResults',
            new Collection([$weakMatch, $bestMatch]),
            $context
        );

        $this->assertGreaterThan($weakScore, $bestScore);
        $this->assertSame('sony-wh-1000xm5', $rankedProducts->first()->slug);
    }

    public function testVietnamesePriceRangesAreParsedIntoNumericBounds()
    {
        $controller = new FrontendController();

        $underRange = $this->invokeControllerMethod($controller, 'detectProductSearchPriceRange', 'tai nghe dưới 1.5 triệu');
        $betweenRange = $this->invokeControllerMethod($controller, 'detectProductSearchPriceRange', 'tai nghe từ 1 đến 2 triệu');
        $budgetTerms = $this->invokeControllerMethod(
            $controller,
            'extractProductSearchTerms',
            $this->invokeControllerMethod($controller, 'normalizeProductSearchText', 'tai nghe dưới 1 triệu')
        );

        $this->assertSame(1500000, $underRange['max']);
        $this->assertSame(1000000, $betweenRange['min']);
        $this->assertSame(2000000, $betweenRange['max']);
        $this->assertSame([], $budgetTerms);
    }

    public function testBrandAndNoiseCancelCriteriaMustBothMatch()
    {
        $controller = new FrontendController();
        $context = [
            'brand_ids' => [1],
            'category_ids' => [],
            'matched_features' => [[
                'keywords' => ['chong on', 'anc', 'xuyen am'],
                'search_terms' => ['chong on', 'anc', 'xuyen am'],
            ]],
            'required_terms' => [],
        ];

        $sonyAnc = new Product([
            'id' => 201,
            'title' => 'Sony WH-1000XM5',
            'slug' => 'sony-wh-1000xm5',
            'summary' => 'Tai nghe chup tai chong on cao cap',
            'description' => 'Ho tro anc va bluetooth on dinh',
            'brand_id' => 1,
            'cat_id' => 10,
        ]);
        $sonyAnc->setRelation('brand', new Brand(['id' => 1, 'title' => 'Sony']));
        $sonyAnc->setRelation('cat_info', new Category(['id' => 10, 'title' => 'Tai nghe chup tai']));

        $sonyNoAnc = new Product([
            'id' => 202,
            'title' => 'Sony ZX110',
            'slug' => 'sony-zx110',
            'summary' => 'Tai nghe co day gia tot',
            'description' => 'Phu hop nghe nhac co ban',
            'brand_id' => 1,
            'cat_id' => 10,
        ]);
        $sonyNoAnc->setRelation('brand', new Brand(['id' => 1, 'title' => 'Sony']));
        $sonyNoAnc->setRelation('cat_info', new Category(['id' => 10, 'title' => 'Tai nghe chup tai']));

        $otherBrandAnc = new Product([
            'id' => 203,
            'title' => 'Sennheiser CX Plus',
            'slug' => 'sennheiser-cx-plus',
            'summary' => 'Tai nghe true wireless chong on',
            'description' => 'Anc co ban cho di chuyen hang ngay',
            'brand_id' => 2,
            'cat_id' => 11,
        ]);
        $otherBrandAnc->setRelation('brand', new Brand(['id' => 2, 'title' => 'Sennheiser']));
        $otherBrandAnc->setRelation('cat_info', new Category(['id' => 11, 'title' => 'Tai nghe true wireless']));

        $this->assertTrue($this->invokeControllerMethod($controller, 'matchesProductSearchCriteria', $sonyAnc, $context));
        $this->assertFalse($this->invokeControllerMethod($controller, 'matchesProductSearchCriteria', $sonyNoAnc, $context));
        $this->assertFalse($this->invokeControllerMethod($controller, 'matchesProductSearchCriteria', $otherBrandAnc, $context));
    }

    private function invokeControllerMethod($controller, $method, ...$arguments)
    {
        $reflection = new ReflectionMethod($controller, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($controller, $arguments);
    }
}
