<?php

namespace App\Helpers;

use Image;
use Storage;

class ImageHelper
{
    /**
     * Function to download and resize the image.
     *
     * @param mixed $product_type
     * @param mixed $imageUrl
     * @param mixed $sku
     * @param bool $mainImage
     * @param int $count
     * @param bool $update
     * @return string
     * 
     */
    public static function downloadAndResizeImage($product_type, $imageUrl, $sku, $mainImage = false, $count = 1, $update = false): string
    {
        $data_url = '';

        if ($imageUrl instanceof \Illuminate\Http\UploadedFile) {
            $ext = $imageUrl->getClientOriginalExtension();
            $imageContents = file_get_contents($imageUrl->getRealPath());
        } else {
            $parsedUrl = parse_url($imageUrl, PHP_URL_PATH);
            $ext = pathinfo($parsedUrl, PATHINFO_EXTENSION) ?: 'jpg';
            $imageContents = file_get_contents($imageUrl);
        }

        // Generate timestamp once
        $time = now()->format('Ymd_His');

        if ($product_type == 'main_product') {
            $path = 'products/' . $sku . '/main/';
        } else {
            $path = 'products/' . $sku . '/';
        }

        if ($mainImage) {
            $filename = $path . $sku . '_' . $time . '.' . $ext;
        } else {
            $n = $sku . '_gallery_' . $count . '_' . $time;
            $filename = $path . $n . '.' . $ext;
        }

        // Save original image
        Storage::disk('public')->put($filename, $imageContents);
        $data_url = $filename;

        // Create Intervention Image instance
        $image = Image::make($imageContents);
        $sizes = config('app.img_sizes');

        foreach ($sizes as $size) {
            $resizedImage = $image->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            if ($mainImage) {
                $filename2 = $path . $sku . '_' . $time . "_{$size}px." . $ext;
            } else {
                $n = $sku . '_gallery_' . $count . '_' . $time . "_{$size}px";
                $filename2 = $path . $n . '.' . $ext;
            }

            Storage::disk('public')->put($filename2, $resizedImage->encode('jpg'));
        }

        return $data_url;
    }
}
