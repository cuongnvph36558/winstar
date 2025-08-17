<!-- Review Form Modal -->
<div id="reviewFormModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
  <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
    <div class="mt-3">
      <!-- Header -->
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Đánh giá sản phẩm</h3>
        <button onclick="closeReviewFormModal()" class="text-gray-400 hover:text-gray-600">
          <i class="fas fa-times text-xl"></i>
        </button>
      </div>

      <!-- Order Info -->
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
        <div class="flex items-center">
          <i class="fas fa-shopping-bag text-blue-600 mr-3"></i>
          <div>
            <h4 class="font-medium text-blue-900">Đơn hàng #{{ $order->code_order }}</h4>
            <p class="text-sm text-blue-700">Hoàn thành ngày {{ $order->updated_at ? $order->updated_at->format('d/m/Y') : 'N/A' }}</p>
          </div>
        </div>
      </div>

      <!-- Products List -->
      <div class="mb-6">
        <h4 class="font-medium text-gray-900 mb-3">Sản phẩm trong đơn hàng:</h4>
        <div class="space-y-3 max-h-60 overflow-y-auto">
          @foreach($order->orderDetails as $detail)
            <div class="border border-gray-200 rounded-lg p-3">
              <div class="flex items-center">
                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                  @if($detail->product && $detail->product->image)
                    <img src="{{ asset('storage/' . $detail->product->image) }}" 
                         alt="{{ $detail->product->name }}" 
                         class="w-12 h-12 object-cover rounded-lg">
                  @else
                    <i class="fas fa-image text-gray-400"></i>
                  @endif
                </div>
                <div class="flex-1">
                  <h5 class="font-medium text-gray-900">{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}</h5>
                  <p class="text-sm text-gray-600">Số lượng: {{ $detail->quantity }}</p>
                </div>
                <button onclick="selectProductForReview({{ $detail->product->id ?? 0 }}, '{{ $detail->product->name ?? 'Sản phẩm không tồn tại' }}')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition duration-200">
                  <i class="fas fa-star mr-1"></i>Đánh giá
                </button>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Review Form -->
      <form id="reviewForm" method="POST" action="{{ route('client.review.store') }}" enctype="multipart/form-data" class="hidden">
        @csrf
        <input type="hidden" name="order_id" value="{{ $order->id }}">
        <input type="hidden" name="product_id" id="selectedProductId">
        
        <!-- Selected Product Info -->
        <div id="selectedProductInfo" class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 hidden">
          <div class="flex items-center">
            <i class="fas fa-check-circle text-green-600 mr-3"></i>
            <div>
              <h4 class="font-medium text-green-900">Đang đánh giá:</h4>
              <p class="text-sm text-green-700" id="selectedProductName"></p>
            </div>
          </div>
        </div>

        <!-- Rating Section -->
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-3">Đánh giá của bạn:</label>
          <div class="flex items-center space-x-2">
            <div class="rating-stars flex space-x-1">
              @for($i = 1; $i <= 5; $i++)
                <button type="button" class="rating-star text-2xl text-gray-300 hover:text-yellow-400 transition-colors" data-rating="{{ $i }}">
                  <i class="fas fa-star"></i>
                </button>
              @endfor
            </div>
            <span class="text-sm text-gray-600 ml-3">
              <span id="ratingText">Chọn số sao</span>
            </span>
          </div>
          <input type="hidden" name="rating" id="selectedRating" required>
        </div>

        <!-- Review Content -->
        <div class="mb-6">
          <label for="reviewContent" class="block text-sm font-medium text-gray-700 mb-2">
            Nội dung đánh giá:
          </label>
          <textarea 
            id="reviewContent" 
            name="content" 
            rows="4" 
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm này..."
            required
            minlength="10"
            maxlength="1000"
          ></textarea>
          <div class="flex justify-between items-center mt-2">
            <span class="text-xs text-gray-500">Tối thiểu 10 ký tự</span>
            <span class="text-xs text-gray-500">
              <span id="charCount">0</span>/1000 ký tự
            </span>
          </div>
        </div>

        <!-- Image Upload (Optional) -->
        <div class="mb-6">
          <label for="reviewImage" class="block text-sm font-medium text-gray-700 mb-2">
            Hình ảnh (tùy chọn):
          </label>
          <div class="flex items-center space-x-4">
            <input 
              type="file" 
              id="reviewImage" 
              name="image" 
              accept="image/*"
              class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
            >
            <div id="imagePreview" class="hidden">
              <img id="previewImg" src="" alt="Preview" class="w-16 h-16 object-cover rounded-lg">
            </div>
          </div>
          <p class="text-xs text-gray-500 mt-1">Định dạng: JPG, PNG, GIF. Tối đa 2MB</p>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3">
          <button 
            type="button" 
            onclick="closeReviewFormModal()"
            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200"
          >
            Hủy bỏ
          </button>
          <button 
            type="submit" 
            id="submitReviewBtn"
            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
            disabled
          >
            <i class="fas fa-paper-plane mr-2"></i>Gửi đánh giá
          </button>
        </div>
      </form>

      <!-- No Product Selected Message -->
      <div id="noProductMessage" class="text-center py-8">
        <i class="fas fa-star text-4xl text-gray-300 mb-4"></i>
        <h4 class="text-lg font-medium text-gray-900 mb-2">Chọn sản phẩm để đánh giá</h4>
        <p class="text-gray-600">Vui lòng chọn một sản phẩm từ danh sách bên trên để bắt đầu đánh giá.</p>
      </div>
    </div>
  </div>
</div>

<script>
function closeReviewFormModal() {
  document.getElementById('reviewFormModal').classList.add('hidden');
  resetReviewForm();
}

function selectProductForReview(productId, productName) {
  // Update hidden inputs
  document.getElementById('selectedProductId').value = productId;
  
  // Show selected product info
  document.getElementById('selectedProductName').textContent = productName;
  document.getElementById('selectedProductInfo').classList.remove('hidden');
  
  // Show form and hide message
  document.getElementById('reviewForm').classList.remove('hidden');
  document.getElementById('noProductMessage').classList.add('hidden');
  
  // Enable submit button if rating is selected
  updateSubmitButton();
}

function resetReviewForm() {
  // Reset form
  document.getElementById('reviewForm').reset();
  document.getElementById('selectedProductId').value = '';
  document.getElementById('selectedRating').value = '';
  
  // Reset stars
  document.querySelectorAll('.rating-star').forEach(star => {
    star.classList.remove('text-yellow-400');
    star.classList.add('text-gray-300');
  });
  
  // Reset UI
  document.getElementById('ratingText').textContent = 'Chọn số sao';
  document.getElementById('charCount').textContent = '0';
  document.getElementById('selectedProductInfo').classList.add('hidden');
  document.getElementById('reviewForm').classList.add('hidden');
  document.getElementById('noProductMessage').classList.remove('hidden');
  document.getElementById('imagePreview').classList.add('hidden');
  
  // Disable submit button
  document.getElementById('submitReviewBtn').disabled = true;
}

function updateSubmitButton() {
  const rating = document.getElementById('selectedRating').value;
  const content = document.getElementById('reviewContent').value.trim();
  const productId = document.getElementById('selectedProductId').value;
  
  const isValid = rating && content.length >= 10 && productId;
  document.getElementById('submitReviewBtn').disabled = !isValid;
}

// Initialize rating stars
document.addEventListener('DOMContentLoaded', function() {
  // Rating stars functionality
  document.querySelectorAll('.rating-star').forEach(star => {
    star.addEventListener('click', function() {
      const rating = this.getAttribute('data-rating');
      
      // Update hidden input
      document.getElementById('selectedRating').value = rating;
      
      // Update stars visual
      document.querySelectorAll('.rating-star').forEach((s, index) => {
        if (index < rating) {
          s.classList.remove('text-gray-300');
          s.classList.add('text-yellow-400');
        } else {
          s.classList.remove('text-yellow-400');
          s.classList.add('text-gray-300');
        }
      });
      
      // Update rating text
      const ratingTexts = ['', 'Rất không hài lòng', 'Không hài lòng', 'Bình thường', 'Hài lòng', 'Rất hài lòng'];
      document.getElementById('ratingText').textContent = ratingTexts[rating];
      
      updateSubmitButton();
    });
  });

  // Character count for review content
  document.getElementById('reviewContent').addEventListener('input', function() {
    const count = this.value.length;
    document.getElementById('charCount').textContent = count;
    updateSubmitButton();
  });

  // Image preview
  document.getElementById('reviewImage').addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('previewImg').src = e.target.result;
        document.getElementById('imagePreview').classList.remove('hidden');
      };
      reader.readAsDataURL(file);
    } else {
      document.getElementById('imagePreview').classList.add('hidden');
    }
  });

  // Form submission
  document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitReviewBtn');
    const originalText = submitBtn.innerHTML;
    
    // Disable button and show loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Đang gửi...';
    
    // Submit form via AJAX
    const formData = new FormData(this);
    
    fetch(this.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        showNotification('🎉 Cảm ơn bạn đã đánh giá sản phẩm! Đánh giá sẽ được hiển thị sau khi được duyệt.', 'success');
        closeReviewFormModal();
      } else {
        throw new Error(data.message || 'Có lỗi xảy ra');
      }
    })
    .catch(error => {
      showNotification('❌ ' + error.message, 'error');
    })
    .finally(() => {
      // Re-enable button
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
    });
  });
});
</script>
