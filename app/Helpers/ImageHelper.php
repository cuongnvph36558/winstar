<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Get image URL with fallback
     */
    public static function getImageUrl($image, $default = null)
    {
        if (!$image) {
            return $default ? asset($default) : asset('client/assets/images/default.jpg');
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }

        return asset('storage/' . $image);
    }

    /**
     * Check if image exists
     */
    public static function imageExists($image)
    {
        if (!$image) {
            return false;
        }

        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return true;
        }

        return file_exists(storage_path('app/public/' . $image));
    }

    /**
     * Get image dimensions
     */
    public static function getImageDimensions($image)
    {
        if (!$image || !self::imageExists($image)) {
            return null;
        }

        $path = storage_path('app/public/' . $image);
        if (file_exists($path)) {
            $dimensions = getimagesize($path);
            return [
                'width' => $dimensions[0] ?? 0,
                'height' => $dimensions[1] ?? 0
            ];
        }

        return null;
    }

    /**
     * Format file size
     */
    public static function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
} 