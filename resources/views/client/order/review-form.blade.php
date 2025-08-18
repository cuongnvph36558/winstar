<!-- Toast CSS -->
<style>
/* Toast Notification Styles */
.toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    max-width: 400px;
}

.toast {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    margin-bottom: 10px;
    padding: 16px 20px;
    border-left: 4px solid;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.toast.show {
    transform: translateX(0);
    opacity: 1;
}

.toast.hide {
    transform: translateX(100%);
    opacity: 0;
}

.toast.success {
    border-left-color: #10b981;
    background: linear-gradient(135deg, #f0fdf4 0%, #ffffff 100%);
}

.toast.error {
    border-left-color: #ef4444;
    background: linear-gradient(135deg, #fef2f2 0%, #ffffff 100%);
}

.toast.warning {
    border-left-color: #f59e0b;
    background: linear-gradient(135deg, #fffbeb 0%, #ffffff 100%);
}

.toast.info {
    border-left-color: #3b82f6;
    background: linear-gradient(135deg, #eff6ff 0%, #ffffff 100%);
}

.toast-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 8px;
}

.toast-title {
    font-weight: 600;
    font-size: 14px;
    color: #1f2937;
    margin: 0;
}

.toast-close {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    font-size: 18px;
    padding: 0;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.toast-close:hover {
    background: #f3f4f6;
    color: #6b7280;
}

.toast-message {
    font-size: 13px;
    color: #6b7280;
    line-height: 1.4;
    margin: 0;
}

.toast-icon {
    width: 20px;
    height: 20px;
    margin-right: 8px;
    flex-shrink: 0;
}

.toast-icon.success {
    color: #10b981;
}

.toast-icon.error {
    color: #ef4444;
}

.toast-icon.warning {
    color: #f59e0b;
}

.toast-icon.info {
    color: #3b82f6;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: #e5e7eb;
    transition: width linear;
}

.toast-progress.success {
    background: #10b981;
}

.toast-progress.error {
    background: #ef4444;
}

.toast-progress.warning {
    background: #f59e0b;
}

.toast-progress.info {
    background: #3b82f6;
}

/* Responsive */
@media (max-width: 768px) {
    .toast-container {
        right: 10px;
        left: 10px;
        max-width: none;
    }
    
    .toast {
        margin-bottom: 8px;
        padding: 12px 16px;
    }
}
</style>

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
// Notification function
function showNotification(message, type = 'info') {
  const toastContainer = document.getElementById('toast-container') || createToastContainer();
  
  const toast = document.createElement('div');
  toast.className = `toast ${type}`;
  
  const iconClass = type === 'success' ? 'fas fa-check-circle' : type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-info-circle';
  const iconColor = type === 'success' ? 'success' : type === 'error' ? 'error' : 'info';
  
  toast.innerHTML = `
    <div class="toast-header">
      <div class="toast-icon ${iconColor}">
        <i class="${iconClass}"></i>
      </div>
      <button class="toast-close" onclick="this.closest('.toast').remove()">
        <i class="fas fa-times"></i>
      </button>
    </div>
    <div class="toast-message">${message}</div>
    <div class="toast-progress ${type}"></div>
  `;
  
  toastContainer.appendChild(toast);
  
  // Show toast
  setTimeout(() => {
    toast.classList.add('show');
  }, 100);
  
  // Auto remove after 5 seconds
  setTimeout(() => {
    toast.classList.add('hide');
    setTimeout(() => {
      if (toast.parentElement) {
        toast.remove();
      }
    }, 300);
  }, 5000);
}

function createToastContainer() {
  const container = document.createElement('div');
  container.id = 'toast-container';
  container.className = 'toast-container';
  document.body.appendChild(container);
  return container;
}

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

// Make function globally available
window.updateSubmitButton = updateSubmitButton;

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
        showNotification('🎉 Cảm ơn bạn đã đánh giá sản phẩm! Bạn có thể đánh giá lại bất cứ lúc nào.', 'success');
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

<!-- Toast notifications container -->
<div id="toast-container" class="toast-container"></div>
