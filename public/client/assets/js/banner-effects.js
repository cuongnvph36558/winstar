// Banner Effects JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Parallax effect for banner
    const banner = document.querySelector('.home-section');
    const particles = document.querySelectorAll('.particle');
    
    if (banner) {
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            banner.style.transform = `translateY(${rate}px)`;
        });
    }
    
    // Mouse move effect for particles
    if (particles.length > 0) {
        document.addEventListener('mousemove', function(e) {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            particles.forEach((particle, index) => {
                const speed = (index + 1) * 0.5;
                const x = (mouseX - 0.5) * speed * 20;
                const y = (mouseY - 0.5) * speed * 20;
                
                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    }
    
    // Smooth scroll for navigation buttons
    const sliderNavs = document.querySelectorAll('.slider-nav');
    sliderNavs.forEach(nav => {
        nav.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Add ripple effect
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
    
    // Add CSS for ripple effect
    const style = document.createElement('style');
    style.textContent = `
        .slider-nav {
            position: relative;
            overflow: hidden;
        }
        
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }
        
        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Typing effect for banner text
    const bannerTexts = document.querySelectorAll('.hero-slider-content .lead');
    bannerTexts.forEach(text => {
        if (text.textContent.trim()) {
            const originalText = text.textContent;
            text.textContent = '';
            text.style.borderRight = '2px solid white';
            
            let i = 0;
            const typeWriter = () => {
                if (i < originalText.length) {
                    text.textContent += originalText.charAt(i);
                    i++;
                    setTimeout(typeWriter, 50);
                } else {
                    text.style.borderRight = 'none';
                }
            };
            
            // Start typing effect when element is visible
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        typeWriter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(text);
        }
    });
    
    // Floating animation for content
    const heroContent = document.querySelector('.hero-slider-content');
    if (heroContent) {
        let floatY = 0;
        let floatDirection = 1;
        
        function floatAnimation() {
            floatY += 0.02 * floatDirection;
            
            if (floatY > 1) {
                floatDirection = -1;
            } else if (floatY < -1) {
                floatDirection = 1;
            }
            
            heroContent.style.transform = `translateY(${floatY * 5}px)`;
            requestAnimationFrame(floatAnimation);
        }
        
        floatAnimation();
    }
    
    // Glow effect for buttons
    const bannerButtons = document.querySelectorAll('.hero-slider-content .btn');
    bannerButtons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 0 20px rgba(255, 255, 255, 0.5)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
}); 