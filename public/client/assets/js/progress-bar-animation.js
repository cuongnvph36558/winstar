// Progress Bar Animation JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize progress bar animations
    initializeProgressBars();
    
    // Add hover effects
    addProgressBarHoverEffects();
    
    // Add rating item hover effects
    addRatingItemHoverEffects();
});

function initializeProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach((bar, index) => {
        // Store original width
        const originalWidth = bar.style.width;
        
        // Reset to 0 for animation
        bar.style.width = '0%';
        
        // Animate with stagger delay
        setTimeout(() => {
            bar.style.width = originalWidth;
            bar.style.transition = 'width 1.2s ease-out';
            
            // Add pulse effect after animation completes
            setTimeout(() => {
                bar.classList.add('pulse');
            }, 1200);
        }, index * 150);
    });
}

function addProgressBarHoverEffects() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        bar.addEventListener('mouseenter', function() {
            this.style.transform = 'scaleY(1.2)';
            this.style.transition = 'transform 0.3s ease, box-shadow 0.3s ease';
            this.style.boxShadow = '0 4px 12px rgba(255, 193, 7, 0.6)';
        });
        
        bar.addEventListener('mouseleave', function() {
            this.style.transform = 'scaleY(1)';
            this.style.boxShadow = '0 2px 6px rgba(255, 193, 7, 0.4)';
        });
    });
}

function addRatingItemHoverEffects() {
    const ratingItems = document.querySelectorAll('.rating-item');
    
    ratingItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            // Add hover class for CSS transitions
            this.style.background = 'rgba(255, 193, 7, 0.05)';
            this.style.transform = 'translateX(5px)';
            this.style.transition = 'all 0.3s ease';
            
            // Enhance text elements
            const starCount = this.querySelector('.star-count');
            const starCountNumber = this.querySelector('.star-count-number');
            
            if (starCount) {
                starCount.style.color = '#ffc107';
                starCount.style.fontWeight = '600';
                starCount.style.transform = 'scale(1.05)';
            }
            
            if (starCountNumber) {
                starCountNumber.style.color = '#f39c12';
                starCountNumber.style.fontWeight = '600';
                starCountNumber.style.transform = 'scale(1.05)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            // Remove hover effects
            this.style.background = '';
            this.style.transform = '';
            
            // Reset text elements
            const starCount = this.querySelector('.star-count');
            const starCountNumber = this.querySelector('.star-count-number');
            
            if (starCount) {
                starCount.style.color = '';
                starCount.style.fontWeight = '';
                starCount.style.transform = '';
            }
            
            if (starCountNumber) {
                starCountNumber.style.color = '';
                starCountNumber.style.fontWeight = '';
                starCountNumber.style.transform = '';
            }
        });
    });
}

// Function to refresh progress bars (useful for dynamic content)
function refreshProgressBars() {
    const progressBars = document.querySelectorAll('.progress-bar');
    
    progressBars.forEach(bar => {
        // Remove existing animations
        bar.classList.remove('pulse');
        bar.style.animation = 'none';
        
        // Trigger reflow
        bar.offsetHeight;
        
        // Restart animations
        bar.style.animation = '';
        setTimeout(() => {
            bar.classList.add('pulse');
        }, 100);
    });
}

// Export functions for global use
window.progressBarUtils = {
    refresh: refreshProgressBars,
    initialize: initializeProgressBars
}; 