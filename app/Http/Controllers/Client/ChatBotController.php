<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChatBotController extends Controller
{
    public function processMessage(Request $request)
    {
        $message = strtolower(trim($request->message));
        
        // phân tích tin nhắn và trả về phản hồi phù hợp
        $response = $this->analyzeMessage($message);
        
        return response()->json($response);
    }
    
    private function analyzeMessage($message)
    {
        // chào hỏi
        if ($this->containsAny($message, ['xin chào', 'hello', 'hi', 'chào', 'alo'])) {
            return [
                'type' => 'text',
                'content' => 'xin chào! tôi là trợ lý ảo của winstar. tôi có thể giúp bạn tìm kiếm sản phẩm, xem thông tin đơn hàng, hoặc hỗ trợ các vấn đề khác. bạn cần gì ạ?',
                'suggestions' => [
                    'tìm sản phẩm',
                    'xem danh mục',
                    'hướng dẫn mua hàng',
                    'liên hệ hỗ trợ'
                ]
            ];
        }
        
        // tìm kiếm sản phẩm
        if ($this->containsAny($message, ['tìm', 'search', 'sản phẩm', 'product', 'mua', 'buy'])) {
            return $this->handleProductSearch($message);
        }
        
        // xem danh mục
        if ($this->containsAny($message, ['danh mục', 'category', 'loại', 'type'])) {
            return $this->handleCategoryRequest();
        }
        
        // hướng dẫn mua hàng
        if ($this->containsAny($message, ['hướng dẫn', 'guide', 'mua hàng', 'order', 'đặt hàng'])) {
            return [
                'type' => 'text',
                'content' => 'để mua hàng tại winstar, bạn cần:\n1. đăng ký tài khoản hoặc đăng nhập\n2. tìm kiếm sản phẩm mong muốn\n3. thêm vào giỏ hàng\n4. tiến hành thanh toán\n5. chọn phương thức thanh toán (momo, vnpay, cod)\n6. xác nhận đơn hàng\n\nbạn có cần tôi hướng dẫn chi tiết bước nào không?',
                'suggestions' => [
                    'đăng ký tài khoản',
                    'thanh toán online',
                    'thanh toán cod'
                ]
            ];
        }
        
        // thông tin liên hệ
        if ($this->containsAny($message, ['liên hệ', 'contact', 'số điện thoại', 'phone', 'email'])) {
            return [
                'type' => 'text',
                'content' => "📞 **hotline:** 05678999999\n\n📧 **email:** support@winstar.com\n\n🌐 **website:** www.winstar.com\n\n📍 **địa chỉ:**\nTòa nhà FPT Polytechnic\n13 Trịnh Văn Bô\nPhường Xuân Phương\nTP Hà Nội\n\n⏰ **giờ làm việc:**\n8h-22h (thứ 2 - chủ nhật)",
                'suggestions' => [
                    'gửi tin nhắn hỗ trợ',
                    'tìm cửa hàng gần nhất',
                    'đặt lịch tư vấn'
                ]
            ];
        }
        
        // giá cả
        if ($this->containsAny($message, ['giá', 'price', 'bao nhiêu', 'cost'])) {
            return [
                'type' => 'text',
                'content' => 'giá sản phẩm tại winstar rất đa dạng, từ vài trăm nghìn đến hàng triệu đồng tùy theo loại sản phẩm. bạn có thể:\n1. tìm kiếm sản phẩm cụ thể để xem giá\n2. xem danh mục sản phẩm theo khoảng giá\n3. liên hệ tư vấn để được báo giá chi tiết',
                'suggestions' => [
                    'sản phẩm dưới 500k',
                    'sản phẩm 500k-1 triệu',
                    'sản phẩm trên 1 triệu'
                ]
            ];
        }
        
        // vận chuyển
        if ($this->containsAny($message, ['vận chuyển', 'shipping', 'giao hàng', 'delivery'])) {
            return [
                'type' => 'text',
                'content' => 'thông tin vận chuyển:\n🚚 giao hàng toàn quốc\n⏰ thời gian: 1-3 ngày (nội thành), 3-7 ngày (tỉnh thành khác)\n💰 phí ship: 20k-50k tùy địa chỉ\n📦 miễn phí ship cho đơn hàng trên 500k\n\nbạn muốn kiểm tra phí ship cho địa chỉ cụ thể không?',
                'suggestions' => [
                    'tính phí ship',
                    'theo dõi đơn hàng',
                    'đổi trả hàng'
                ]
            ];
        }
        
        // mặc định
        return [
            'type' => 'text',
            'content' => 'xin lỗi, tôi chưa hiểu rõ yêu cầu của bạn. bạn có thể:\n- tìm kiếm sản phẩm\n- xem danh mục\n- hỏi về hướng dẫn mua hàng\n- liên hệ hỗ trợ',
            'suggestions' => [
                'tìm sản phẩm',
                'xem danh mục',
                'hướng dẫn mua hàng',
                'liên hệ hỗ trợ'
            ]
        ];
    }
    
    private function handleProductSearch($message)
    {
        // trích xuất từ khóa tìm kiếm
        $keywords = $this->extractKeywords($message);
        
        if (empty($keywords)) {
            return [
                'type' => 'text',
                'content' => 'bạn muốn tìm sản phẩm gì? hãy cho tôi biết tên sản phẩm hoặc danh mục bạn quan tâm.',
                'suggestions' => [
                    'điện thoại',
                    'laptop',
                    'phụ kiện',
                    'đồng hồ'
                ]
            ];
        }
        
        // tìm kiếm sản phẩm
        $products = Product::where(function($query) use ($keywords) {
            foreach ($keywords as $keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%")
                      ->orWhere('description', 'like', "%{$keyword}%");
                });
            }
        })
        ->where('status', 1)
        ->take(5)
        ->get();
        
        if ($products->count() > 0) {
            $content = "tôi tìm thấy {$products->count()} sản phẩm phù hợp:\n\n";
            $suggestions = [];
            
            foreach ($products as $product) {
                $content .= "📱 {$product->name}\n";
                $content .= "💰 " . number_format($product->price) . " vnđ\n";
                $content .= "🔗 <a href='" . route('client.single-product', $product->id) . "' target='_blank'>Xem chi tiết</a>\n\n";
                $suggestions[] = "xem {$product->name}";
            }
            
            return [
                'type' => 'product_list',
                'content' => $content,
                'products' => $products,
                'suggestions' => $suggestions
            ];
        } else {
            return [
                'type' => 'text',
                'content' => "xin lỗi, tôi không tìm thấy sản phẩm nào phù hợp với từ khóa '{$keywords[0]}'. bạn có thể thử:\n- tìm kiếm với từ khóa khác\n- xem danh mục sản phẩm\n- liên hệ tư vấn",
                'suggestions' => [
                    'xem danh mục',
                    'sản phẩm bán chạy',
                    'sản phẩm mới',
                    'liên hệ tư vấn'
                ]
            ];
        }
    }
    
    private function handleCategoryRequest()
    {
        $categories = Category::where('status', 1)->take(8)->get();
        
        $content = "danh mục sản phẩm tại winstar:\n\n";
        $suggestions = [];
        
        foreach ($categories as $category) {
            $content .= "📂 {$category->name}\n";
            $suggestions[] = "xem {$category->name}";
        }
        
        return [
            'type' => 'category_list',
            'content' => $content,
            'categories' => $categories,
            'suggestions' => $suggestions
        ];
    }
    
    private function containsAny($haystack, $needles)
    {
        foreach ($needles as $needle) {
            if (strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
    
    private function extractKeywords($message)
    {
        // loại bỏ các từ không cần thiết
        $stopWords = ['tìm', 'search', 'sản phẩm', 'product', 'mua', 'buy', 'cho', 'tôi', 'bạn', 'có', 'không', 'gì', 'nào', 'đó', 'này', 'kia'];
        
        $words = explode(' ', $message);
        $keywords = [];
        
        foreach ($words as $word) {
            $word = trim($word);
            if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                $keywords[] = $word;
            }
        }
        
        return $keywords;
    }
} 