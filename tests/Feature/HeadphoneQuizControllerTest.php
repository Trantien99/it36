<?php

namespace Tests\Feature;

use App\Http\Controllers\HeadphoneQuizController;
use ReflectionMethod;
use Tests\TestCase;

class HeadphoneQuizControllerTest extends TestCase
{
    public function testBattleChoicesCanResolveToFocusHeavyPremiumSetup()
    {
        $controller = new HeadphoneQuizController();

        $answers = $this->invokeControllerMethod($controller, 'deriveAnswersFromBattleChoices', [
            'round_1' => 'focus_boost',
            'round_2' => 'marathon_wear',
            'round_3' => 'quiet_zone',
            'round_4' => 'premium_push',
        ]);

        $this->assertSame('office', $answers['primary_use']);
        $this->assertSame('over_ear', $answers['form_factor']);
        $this->assertSame('noise', $answers['priority']);
        $this->assertSame('over_3000', $answers['budget']);
    }

    public function testBattleChoicesCanResolveToGamingValueWirelessProfile()
    {
        $controller = new HeadphoneQuizController();

        $answers = $this->invokeControllerMethod($controller, 'deriveAnswersFromBattleChoices', [
            'round_1' => 'frag_mode',
            'round_2' => 'street_move',
            'round_3' => 'best_bang',
            'round_4' => 'easy_checkout',
        ]);
        $recap = $this->invokeControllerMethod($controller, 'buildBattleRecap', [
            'round_1' => 'frag_mode',
            'round_2' => 'street_move',
            'round_3' => 'best_bang',
            'round_4' => 'easy_checkout',
        ]);

        $this->assertSame('gaming', $answers['primary_use']);
        $this->assertSame('true_wireless', $answers['form_factor']);
        $this->assertSame('value', $answers['priority']);
        $this->assertSame('between_1000_2000', $answers['budget']);
        $this->assertSame(['Vào trận', 'Nhịp di chuyển', 'Giá ngon dễ chốt', 'Chốt đơn gọn'], $recap);
    }

    public function testQuizCanAttachEnhancedListingMediaForLowResolutionProduct()
    {
        $testingDirectory = public_path('storage/testing');
        if (!is_dir($testingDirectory)) {
            mkdir($testingDirectory, 0755, true);
        }

        $relativePath = '/storage/testing/quiz-listing-low-res-test.jpg';
        $absolutePath = public_path(ltrim($relativePath, '/'));
        $sourceImage = imagecreatetruecolor(150, 210);

        $background = imagecolorallocate($sourceImage, 245, 247, 250);
        $accent = imagecolorallocate($sourceImage, 42, 52, 65);
        imagefilledrectangle($sourceImage, 0, 0, 150, 210, $background);
        imagefilledellipse($sourceImage, 75, 105, 88, 132, $accent);
        imagejpeg($sourceImage, $absolutePath, 88);
        imagedestroy($sourceImage);

        $controller = new HeadphoneQuizController();
        $product = (object) ['photo' => $relativePath];
        $product = $this->invokeControllerMethod($controller, 'attachListingMediaToProduct', $product);

        $this->assertTrue($product->listing_media['is_low_resolution']);
        $this->assertStringContainsString('/storage/generated/product-detail/', $product->listing_media['display_url']);
        $this->assertLessThanOrEqual(170, $product->listing_media['display_width']);
        $this->assertGreaterThanOrEqual(132, $product->listing_media['display_width']);

        $generatedPath = public_path(ltrim(parse_url($product->listing_media['display_url'], PHP_URL_PATH), '/'));
        $this->assertFileExists($generatedPath);

        @unlink($absolutePath);
        @unlink($generatedPath);
    }

    private function invokeControllerMethod($controller, $method, ...$arguments)
    {
        $reflection = new ReflectionMethod($controller, $method);
        $reflection->setAccessible(true);

        return $reflection->invokeArgs($controller, $arguments);
    }
}
