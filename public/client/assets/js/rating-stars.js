// Rating Stars Functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Rating stars script loaded');
    
    // Function to update star colors
    function updateStarColors(rating, type = 'active') {
        console.log('Updating star colors for rating:', rating, 'type:', type);
        
        const stars = document.querySelectorAll('.rating-star');
        const starIcons = document.querySelectorAll('.rating-star i');
        
        // Reset all stars
        stars.forEach(star => {
            star.classList.remove('active', 'hover');
        });
        starIcons.forEach(icon => {
            icon.style.color = '#ddd';
        });
        
        // Color stars up to the selected rating
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.add(type);
                const icon = star.querySelector('i');
                if (icon) {
                    icon.style.color = '#ffc107';
                }
            }
        });
        
        console.log('Updated stars - Active:', document.querySelectorAll('.rating-star.active').length);
        console.log('Updated stars - Yellow icons:', document.querySelectorAll('.rating-star i[style*="ffc107"]').length);
    }
    
    // Initialize rating stars
    function initRatingStars() {
        console.log('Initializing rating stars...');
        const stars = document.querySelectorAll('.rating-star');
        console.log('Found rating stars:', stars.length);
        
        if (stars.length === 0) {
            console.error('No rating stars found!');
            return;
        }
        
        // Set initial state
        stars.forEach(star => {
            const icon = star.querySelector('i');
            if (icon) {
                icon.style.color = '#ddd';
            }
        });
        
        // Add click events to stars
        stars.forEach((star, index) => {
            const rating = parseInt(star.getAttribute('data-rating'));
            console.log('Setting up click for star', index + 1, 'with rating:', rating);
            
            // Remove any existing event listeners
            if (star._clickHandler) {
                star.removeEventListener('click', star._clickHandler);
            }
            if (star._hoverHandler) {
                star.removeEventListener('mouseenter', star._hoverHandler);
            }
            
            // Create new event handlers
            star._clickHandler = function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                console.log('Rating star clicked:', rating);
                console.log('Click event target:', e.target);
                console.log('Click event currentTarget:', e.currentTarget);
                
                // Update hidden input
                const selectedRatingInput = document.getElementById('selected-rating');
                if (selectedRatingInput) {
                    selectedRatingInput.value = rating;
                    console.log('Selected rating value:', selectedRatingInput.value);
                } else {
                    console.error('Selected rating input not found!');
                }
                
                // Update visual state
                updateStarColors(rating, 'active');
                
                // Add a visual feedback
                star.style.transform = 'scale(1.2)';
                setTimeout(() => {
                    star.style.transform = 'scale(1)';
                }, 200);
                
                // Force a small delay to ensure the click is processed
                setTimeout(() => {
                    console.log('After click delay - Selected rating:', document.getElementById('selected-rating')?.value);
                    console.log('After click delay - Active stars:', document.querySelectorAll('.rating-star.active').length);
                }, 100);
            };
            
            star._hoverHandler = function(e) {
                console.log('Rating star hover:', rating);
                updateStarColors(rating, 'hover');
            };
            
            // Add event listeners with capture phase to ensure they run first
            star.addEventListener('click', star._clickHandler, true);
            star.addEventListener('mouseenter', star._hoverHandler, true);
            
            // Also add touch events for mobile devices
            star.addEventListener('touchstart', function(e) {
                e.preventDefault();
                star._clickHandler(e);
            }, { passive: false });
        });
        
        // Add mouseleave event to rating container
        const ratingContainer = document.querySelector('.rating-input');
        if (ratingContainer) {
            ratingContainer.addEventListener('mouseleave', function() {
                console.log('Rating container mouseleave');
                const selectedRatingInput = document.getElementById('selected-rating');
                const selectedRating = selectedRatingInput ? parseInt(selectedRatingInput.value) : 0;
                
                // Remove hover class from all stars
                document.querySelectorAll('.rating-star').forEach(star => {
                    star.classList.remove('hover');
                });
                
                // Show selected rating
                updateStarColors(selectedRating, 'active');
            });
        }
        
        console.log('Rating stars initialized successfully');
    }
    
    // Initialize immediately if DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRatingStars);
    } else {
        initRatingStars();
    }
    
    // Also initialize after a short delay to ensure everything is loaded
    setTimeout(initRatingStars, 500);
    
    // Re-initialize after 1 second as backup
    setTimeout(initRatingStars, 1000);
});

// Export functions for global use
window.updateStarColors = function(rating, type = 'active') {
    const stars = document.querySelectorAll('.rating-star');
    const starIcons = document.querySelectorAll('.rating-star i');
    
    // Reset all stars
    stars.forEach(star => {
        star.classList.remove('active', 'hover');
    });
    starIcons.forEach(icon => {
        icon.style.color = '#ddd';
    });
    
    // Color stars up to the selected rating
    stars.forEach(star => {
        const starRating = parseInt(star.getAttribute('data-rating'));
        if (starRating <= rating) {
            star.classList.add(type);
            const icon = star.querySelector('i');
            if (icon) {
                icon.style.color = '#ffc107';
            }
        }
    });
}; 