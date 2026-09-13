<?php

namespace Tests\Feature;

use App\Http\Controllers\FrontendController;
use ReflectionMethod;
use Tests\TestCase;

class FrontendControllerImageTest extends TestCase
{
    public function testLowResolutionGalleryImageGetsEnhancedVariant()
    {
        $testingDirectory = public_path('storage/testing');
        if (!is_dir($testingDirectory)) {
            mkdir($testingDirectory, 0755, true);
        }

        $relativePath = '/storage/testing/gallery-low-res-test.jpg';
        $absolutePath = public_path(ltrim($relativePath, '/'));
        $sourceImage = imagecreatetruecolor(120, 160);

        $background = imagecolorallocate($sourceImage, 228, 236, 245);
        $accent = imagecolorallocate($sourceImage, 30, 64, 175);
        imagefilledrectangle($sourceImage, 0, 0, 120, 160, $background);
        imagefilledellipse($sourceImage, 60, 80, 58, 96, $accent);
        imagejpeg($sourceImage, $absolutePath, 88);
        imagedestroy($sourceImage);

        $controller = new FrontendController();
        $method = new ReflectionMethod($controller, 'buildProductGalleryMedia');
        $method->setAccessible(true);
        $galleryMedia = $method->invoke($controller, $relativePath);

        $this->assertCount(1, $galleryMedia);
        $this->assertTrue($galleryMedia[0]['is_low_resolution']);
        $this->assertStringContainsString('/storage/generated/product-detail/', $galleryMedia[0]['display_url']);

        $generatedPath = public_path(ltrim(parse_url($galleryMedia[0]['display_url'], PHP_URL_PATH), '/'));
        $this->assertFileExists($generatedPath);

        $generatedSize = getimagesize($generatedPath);
        $this->assertGreaterThan(120, $generatedSize[0]);
        $this->assertGreaterThan(160, $generatedSize[1]);

        @unlink($absolutePath);
        @unlink($generatedPath);
    }

    public function testLowResolutionListingImageUsesControlledDisplayWidth()
    {
        $testingDirectory = public_path('storage/testing');
        if (!is_dir($testingDirectory)) {
            mkdir($testingDirectory, 0755, true);
        }

        $relativePath = '/storage/testing/listing-low-res-test.jpg';
        $absolutePath = public_path(ltrim($relativePath, '/'));
        $sourceImage = imagecreatetruecolor(150, 210);

        $background = imagecolorallocate($sourceImage, 240, 244, 248);
        $accent = imagecolorallocate($sourceImage, 17, 94, 89);
        imagefilledrectangle($sourceImage, 0, 0, 150, 210, $background);
        imagefilledellipse($sourceImage, 75, 105, 84, 128, $accent);
        imagejpeg($sourceImage, $absolutePath, 88);
        imagedestroy($sourceImage);

        $controller = new FrontendController();
        $method = new ReflectionMethod($controller, 'buildListingMedia');
        $method->setAccessible(true);
        $listingMedia = $method->invoke($controller, $relativePath);

        $this->assertTrue($listingMedia['is_low_resolution']);
        $this->assertStringContainsString('/storage/generated/product-detail/', $listingMedia['display_url']);
        $this->assertLessThanOrEqual(170, $listingMedia['display_width']);
        $this->assertGreaterThanOrEqual(132, $listingMedia['display_width']);

        $generatedPath = public_path(ltrim(parse_url($listingMedia['display_url'], PHP_URL_PATH), '/'));
        $this->assertFileExists($generatedPath);

        @unlink($absolutePath);
        @unlink($generatedPath);
    }
}
