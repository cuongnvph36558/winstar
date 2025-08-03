<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Upload image to storage
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $path
     * @return string
     */
    public static function uploadImage($file, $path = 'images')
    {
        if (!$file) {
            return null;
        }

        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs($path, $fileName, 'public');
        
        return $filePath;
    }

    /**
     * Delete image from storage
     *
     * @param string $path
     * @return bool
     */
    public static function deleteImage($path)
    {
        if (!$path) {
            return false;
        }

        $fullPath = storage_path('app/public/' . $path);
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

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

    /**
     * Resize image
     *
     * @param string $path
     * @param int $width
     * @param int $height
     * @return string
     */
    public static function resizeImage($path, $width = 300, $height = 300)
    {
        // Basic resize implementation
        // You can extend this with more advanced image processing
        return $path;
    }

    /**
     * Generate thumbnail
     *
     * @param string $path
     * @param int $size
     * @return string
     */
    public static function generateThumbnail($path, $size = 150)
    {
        return self::resizeImage($path, $size, $size);
    }
} 