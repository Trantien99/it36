<?php

namespace Tests\Unit;

use App\Http\Controllers\CartController;
use App\Models\Product;
use ReflectionClass;
use Tests\TestCase;

class CartColorSelectionTest extends TestCase
{
    public function testMissingColorSelectsTheFirstProductColor()
    {
        $product = new Product();
        $product->color_code = '#ff0000,#000000';

        $this->assertSame('#FF0000', $this->selectColor($product, null));
    }

    public function testInvalidExplicitColorIsStillRejected()
    {
        $product = new Product();
        $product->color_code = '#ff0000,#000000';

        $this->assertFalse($this->selectColor($product, '#00FF00'));
    }

    private function selectColor(Product $product, $colorCode)
    {
        $controller = new CartController(new Product());
        $method = (new ReflectionClass(CartController::class))->getMethod('getSelectedColorCode');
        $method->setAccessible(true);

        return $method->invoke($controller, $product, $colorCode);
    }
}