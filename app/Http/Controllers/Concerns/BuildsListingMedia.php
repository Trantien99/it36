<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Str;

trait BuildsListingMedia
{
    protected function attachListingMediaToProduct($product)
    {
        if (!$product) {
            return $product;
        }

        $product->listing_media = $this->buildListingMedia($product->photo ?? '');

        return $product;
    }

    protected function buildListingMedia($photoList)
    {
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

        if (empty($primaryPhoto)) {
            return $media;
        }

        $meta = $this->getPublicImageMeta($primaryPhoto);
        if (!$meta) {
            return $media;
        }

        $media['width'] = $meta['width'];
        $media['height'] = $meta['height'];

        if ($this->shouldEnhanceGalleryImage($meta['width'], $meta['height'])) {
            $media['is_low_resolution'] = true;
            $media['display_width'] = $this->getRecommendedListingDisplayWidth($meta['width'], $meta['height']);

            $enhancedUrl = $this->createEnhancedGalleryImage($meta);
            if (!empty($enhancedUrl)) {
                $media['display_url'] = $enhancedUrl;
            }

            return $media;
        }

        $media['display_width'] = min(260, max(180, (int) round($meta['width'] * 0.9)));

        return $media;
    }

    protected function getPublicImageMeta($imageUrl)
    {
        if (empty($imageUrl) || Str::startsWith($imageUrl, ['http://', 'https://', '//'])) {
            return null;
        }

        $parsedPath = parse_url($imageUrl, PHP_URL_PATH);
        $relativePath = ltrim($parsedPath ?: $imageUrl, '/');
        $absolutePath = public_path(str_replace('/', DIRECTORY_SEPARATOR, $relativePath));

        if (!is_file($absolutePath)) {
            return null;
        }

        $info = @getimagesize($absolutePath);
        if ($info === false) {
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

    protected function shouldEnhanceGalleryImage($width, $height)
    {
        return $width > 0 && $height > 0 && max($width, $height) < 700;
    }

    protected function getRecommendedListingDisplayWidth($width, $height)
    {
        $maxDimension = max((int) $width, (int) $height);

        if ($maxDimension <= 260) {
            return min(170, max(132, (int) round($width * 0.82)));
        }

        if ($maxDimension <= 360) {
            return min(188, max(150, (int) round($width * 0.84)));
        }

        if ($maxDimension <= 520) {
            return min(220, max(180, (int) round($width * 0.88)));
        }

        return min(250, max(190, (int) round($width * 0.92)));
    }

    protected function createEnhancedGalleryImage(array $imageMeta)
    {
        $extension = $this->normalizeOutputExtension($imageMeta['extension'] ?? '');
        if (empty($extension) || empty($imageMeta['absolute_path']) || !is_file($imageMeta['absolute_path'])) {
            return null;
        }

        $cacheDirectory = public_path('storage/generated/product-detail');
        if (!is_dir($cacheDirectory) && !mkdir($cacheDirectory, 0755, true) && !is_dir($cacheDirectory)) {
            return null;
        }

        $signature = md5($imageMeta['relative_path'] . '|' . $imageMeta['width'] . 'x' . $imageMeta['height'] . '|gallery-v2');
        $outputRelativePath = 'storage/generated/product-detail/' . $signature . '.' . $extension;
        $outputAbsolutePath = public_path($outputRelativePath);
        $sourceModifiedAt = @filemtime($imageMeta['absolute_path']);
        $outputModifiedAt = @filemtime($outputAbsolutePath);

        if ($outputModifiedAt !== false && $sourceModifiedAt !== false && $outputModifiedAt >= $sourceModifiedAt) {
            return '/' . $outputRelativePath;
        }

        $sourceImage = $this->createImageResource($imageMeta['absolute_path'], $extension);
        if (!$sourceImage) {
            return null;
        }

        $scale = max(2, (int) ceil(780 / max(1, $imageMeta['width'])));
        $scale = min($scale, 4);
        $targetWidth = (int) max($imageMeta['width'], $imageMeta['width'] * $scale);
        $targetHeight = (int) max($imageMeta['height'], $imageMeta['height'] * $scale);
        $enhancedImage = imagecreatetruecolor($targetWidth, $targetHeight);

        if (!$enhancedImage) {
            imagedestroy($sourceImage);

            return null;
        }

        if (in_array($extension, ['png', 'webp'], true)) {
            imagealphablending($enhancedImage, false);
            imagesavealpha($enhancedImage, true);
            $transparent = imagecolorallocatealpha($enhancedImage, 0, 0, 0, 127);
            imagefilledrectangle($enhancedImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        } else {
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

    protected function normalizeOutputExtension($extension)
    {
        $extension = strtolower((string) $extension);

        if ($extension === 'jpeg') {
            return 'jpg';
        }

        if (in_array($extension, ['jpg', 'png', 'webp'], true)) {
            return $extension;
        }

        return null;
    }

    protected function createImageResource($absolutePath, $extension)
    {
        if ($extension === 'jpg') {
            return @imagecreatefromjpeg($absolutePath);
        }

        if ($extension === 'png') {
            return @imagecreatefrompng($absolutePath);
        }

        if ($extension === 'webp' && function_exists('imagecreatefromwebp')) {
            return @imagecreatefromwebp($absolutePath);
        }

        return null;
    }

    protected function saveImageResource($image, $absolutePath, $extension)
    {
        if ($extension === 'jpg') {
            imageinterlace($image, true);

            return imagejpeg($image, $absolutePath, 90);
        }

        if ($extension === 'png') {
            return imagepng($image, $absolutePath, 4);
        }

        if ($extension === 'webp' && function_exists('imagewebp')) {
            return imagewebp($image, $absolutePath, 90);
        }

        return false;
    }
}
