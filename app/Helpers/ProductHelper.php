<?php

namespace App\Helpers;

class ProductHelper
{
    /**
     * Lấy đường dẫn ảnh sản phẩm theo ID
     */
    public static function getProductImage($product, $size = 'medium')
    {
        // Nếu sản phẩm có ảnh trong database
        if ($product && $product->image) {
            return asset('storage/' . $product->image);
        }

        // Thử tìm ảnh theo ID sản phẩm
        $imageExtensions = ['jpg', 'jpeg', 'png', 'webp'];
        $imagePath = 'client/assets/images/products/';
        
        foreach ($imageExtensions as $ext) {
            $filename = "product-{$product->id}.{$ext}";
            $fullPath = public_path($imagePath . $filename);
            
            if (file_exists($fullPath)) {
                return asset($imagePath . $filename);
            }
        }

        // Nếu không tìm thấy, trả về ảnh mặc định
        return asset('client/assets/images/portfolio/grid-portfolio1.jpg');
    }

    /**
     * Lấy ảnh sản phẩm với fallback
     */
    public static function getProductImageWithFallback($product)
    {
        $imageUrl = self::getProductImage($product);
        
        return [
            'url' => $imageUrl,
            'alt' => $product->name ?? 'Product Image',
            'fallback' => asset('client/assets/images/portfolio/grid-portfolio1.jpg')
        ];
    }
} 