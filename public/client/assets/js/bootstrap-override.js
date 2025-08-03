// Bootstrap Override Script for Product Store
document.addEventListener('DOMContentLoaded', function() {
    console.log('Bootstrap override script loaded');
    
    // Force apply styles after page load
    function forceApplyStyles() {
        // Force banner background
        const banner = document.querySelector('.module.bg-dark-60.product-banner');
        if (banner) {
            banner.style.setProperty('background', '#000000', 'important');
            banner.style.setProperty('background-image', 'linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(20, 20, 20, 0.8) 50%, rgba(0, 0, 0, 0.8) 100%), url("../images/section-6.jpg")', 'important');
            banner.style.setProperty('background-size', 'cover', 'important');
            banner.style.setProperty('background-position', 'center', 'important');
            banner.style.setProperty('background-attachment', 'fixed', 'important');
            banner.style.setProperty('min-height', '250px', 'important');
            banner.style.setProperty('display', 'flex', 'important');
            banner.style.setProperty('align-items', 'center', 'important');
            banner.style.setProperty('position', 'relative', 'important');
            banner.style.setProperty('overflow', 'hidden', 'important');
            banner.style.setProperty('color', 'white', 'important');
            banner.style.setProperty('padding', '4rem 0', 'important');
        }
        
        // Force banner title
        const bannerTitle = document.querySelector('.banner-title');
        if (bannerTitle) {
            bannerTitle.style.setProperty('color', 'white', 'important');
            bannerTitle.style.setProperty('font-size', '2.5rem', 'important');
            bannerTitle.style.setProperty('font-weight', '700', 'important');
            bannerTitle.style.setProperty('margin-bottom', '1rem', 'important');
            bannerTitle.style.setProperty('text-shadow', '2px 2px 4px rgba(0,0,0,0.5)', 'important');
        }
        
        // Force banner subtitle
        const bannerSubtitle = document.querySelector('.banner-subtitle');
        if (bannerSubtitle) {
            bannerSubtitle.style.setProperty('color', 'rgba(255,255,255,0.9)', 'important');
            bannerSubtitle.style.setProperty('font-size', '1.1rem', 'important');
            bannerSubtitle.style.setProperty('margin-bottom', '2rem', 'important');
            bannerSubtitle.style.setProperty('text-shadow', '1px 1px 2px rgba(0,0,0,0.5)', 'important');
        }
        
        // Force banner stats
        const bannerStats = document.querySelector('.banner-stats');
        if (bannerStats) {
            bannerStats.style.setProperty('display', 'flex', 'important');
            bannerStats.style.setProperty('justify-content', 'center', 'important');
            bannerStats.style.setProperty('gap', '2rem', 'important');
            bannerStats.style.setProperty('flex-wrap', 'wrap', 'important');
        }
        
        // Force stat items
        const statItems = document.querySelectorAll('.stat-item');
        statItems.forEach(function(item) {
            item.style.setProperty('background', 'rgba(255,255,255,0.1)', 'important');
            item.style.setProperty('backdrop-filter', 'blur(10px)', 'important');
            item.style.setProperty('border', '1px solid rgba(255,255,255,0.2)', 'important');
            item.style.setProperty('border-radius', '15px', 'important');
            item.style.setProperty('padding', '1.5rem 2rem', 'important');
            item.style.setProperty('text-align', 'center', 'important');
            item.style.setProperty('min-width', '120px', 'important');
            item.style.setProperty('transition', 'all 0.3s ease', 'important');
        });
        
        // Force stat numbers
        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach(function(number) {
            number.style.setProperty('display', 'block', 'important');
            number.style.setProperty('color', '#ff6b6b', 'important');
            number.style.setProperty('font-size', '2rem', 'important');
            number.style.setProperty('font-weight', '700', 'important');
            number.style.setProperty('margin-bottom', '0.5rem', 'important');
            number.style.setProperty('text-shadow', '1px 1px 2px rgba(0,0,0,0.5)', 'important');
        });
        
        // Force stat labels
        const statLabels = document.querySelectorAll('.stat-label');
        statLabels.forEach(function(label) {
            label.style.setProperty('display', 'block', 'important');
            label.style.setProperty('color', 'white', 'important');
            label.style.setProperty('font-size', '0.9rem', 'important');
            label.style.setProperty('font-weight', '500', 'important');
            label.style.setProperty('text-transform', 'uppercase', 'important');
            label.style.setProperty('letter-spacing', '1px', 'important');
            label.style.setProperty('text-shadow', '1px 1px 2px rgba(0,0,0,0.5)', 'important');
        });
        
        // Force search section
        const searchSection = document.querySelector('.search-section');
        if (searchSection) {
            searchSection.style.setProperty('background', '#f6f6f6', 'important');
            searchSection.style.setProperty('padding', '3rem 0', 'important');
        }
        
        // Force search container
        const searchContainer = document.querySelector('.search-container');
        if (searchContainer) {
            searchContainer.style.setProperty('background', 'white', 'important');
            searchContainer.style.setProperty('border-radius', '20px', 'important');
            searchContainer.style.setProperty('box-shadow', '0 10px 40px rgba(0,0,0,0.12)', 'important');
            searchContainer.style.setProperty('border', '1px solid #f0f0f0', 'important');
            searchContainer.style.setProperty('margin-bottom', '3rem', 'important');
            searchContainer.style.setProperty('overflow', 'hidden', 'important');
            searchContainer.style.setProperty('transition', 'all 0.3s ease', 'important');
        }
        
        // Force search header
        const searchHeader = document.querySelector('.search-header');
        if (searchHeader) {
            searchHeader.style.setProperty('background', 'linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%)', 'important');
            searchHeader.style.setProperty('padding', '2rem', 'important');
            searchHeader.style.setProperty('border-bottom', '2px solid #f0f0f0', 'important');
            searchHeader.style.setProperty('border-radius', '20px 20px 0 0', 'important');
            searchHeader.style.setProperty('display', 'flex', 'important');
            searchHeader.style.setProperty('justify-content', 'space-between', 'important');
            searchHeader.style.setProperty('align-items', 'center', 'important');
            searchHeader.style.setProperty('flex-wrap', 'wrap', 'important');
            searchHeader.style.setProperty('gap', '1rem', 'important');
        }
        
        // Force search title
        const searchTitle = document.querySelector('.search-title');
        if (searchTitle) {
            searchTitle.style.setProperty('font-size', '1.4rem', 'important');
            searchTitle.style.setProperty('font-weight', '700', 'important');
            searchTitle.style.setProperty('color', '#1a1a1a', 'important');
            searchTitle.style.setProperty('margin', '0', 'important');
        }
        
        // Force search summary
        const searchSummary = document.querySelector('.search-summary');
        if (searchSummary) {
            searchSummary.style.setProperty('display', 'flex', 'important');
            searchSummary.style.setProperty('align-items', 'center', 'important');
            searchSummary.style.setProperty('gap', '1rem', 'important');
            searchSummary.style.setProperty('flex-wrap', 'wrap', 'important');
        }
        
        // Force product count
        const productCount = document.querySelector('.product-count');
        if (productCount) {
            productCount.style.setProperty('background', 'linear-gradient(135deg, #1a1a1a 0%, #333333 100%)', 'important');
            productCount.style.setProperty('color', 'white', 'important');
            productCount.style.setProperty('padding', '0.75rem 1.5rem', 'important');
            productCount.style.setProperty('border-radius', '25px', 'important');
            productCount.style.setProperty('font-weight', '600', 'important');
            productCount.style.setProperty('border', 'none', 'important');
            productCount.style.setProperty('font-size', '0.9rem', 'important');
            productCount.style.setProperty('box-shadow', '0 4px 15px rgba(26, 26, 26, 0.3)', 'important');
            productCount.style.setProperty('display', 'flex', 'important');
            productCount.style.setProperty('align-items', 'center', 'important');
            productCount.style.setProperty('gap', '0.5rem', 'important');
        }
        
        // Force clear all button
        const clearAllBtn = document.querySelector('.clear-all-btn');
        if (clearAllBtn) {
            clearAllBtn.style.setProperty('background', 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)', 'important');
            clearAllBtn.style.setProperty('color', 'white', 'important');
            clearAllBtn.style.setProperty('padding', '0.75rem 1.5rem', 'important');
            clearAllBtn.style.setProperty('border-radius', '25px', 'important');
            clearAllBtn.style.setProperty('text-decoration', 'none', 'important');
            clearAllBtn.style.setProperty('font-size', '0.9rem', 'important');
            clearAllBtn.style.setProperty('font-weight', '600', 'important');
            clearAllBtn.style.setProperty('transition', 'all 0.3s ease', 'important');
            clearAllBtn.style.setProperty('box-shadow', '0 4px 15px rgba(231, 76, 60, 0.3)', 'important');
            clearAllBtn.style.setProperty('display', 'flex', 'important');
            clearAllBtn.style.setProperty('align-items', 'center', 'important');
            clearAllBtn.style.setProperty('gap', '0.5rem', 'important');
            clearAllBtn.style.setProperty('border', 'none', 'important');
        }
        
        // Force filter toggle button
        const filterToggleBtn = document.querySelector('.filter-toggle-btn');
        if (filterToggleBtn) {
            filterToggleBtn.style.setProperty('background', 'white', 'important');
            filterToggleBtn.style.setProperty('border', '2px solid #1a1a1a', 'important');
            filterToggleBtn.style.setProperty('color', '#1a1a1a', 'important');
            filterToggleBtn.style.setProperty('padding', '0.75rem 1.5rem', 'important');
            filterToggleBtn.style.setProperty('border-radius', '25px', 'important');
            filterToggleBtn.style.setProperty('cursor', 'pointer', 'important');
            filterToggleBtn.style.setProperty('transition', 'all 0.3s ease', 'important');
            filterToggleBtn.style.setProperty('font-weight', '600', 'important');
            filterToggleBtn.style.setProperty('font-size', '0.9rem', 'important');
            filterToggleBtn.style.setProperty('display', 'flex', 'important');
            filterToggleBtn.style.setProperty('align-items', 'center', 'important');
            filterToggleBtn.style.setProperty('gap', '0.5rem', 'important');
        }
        
        // Force search content
        const searchContent = document.querySelector('.search-content');
        if (searchContent) {
            searchContent.style.setProperty('padding', '2.5rem', 'important');
            searchContent.style.setProperty('background', 'white', 'important');
            searchContent.style.setProperty('transition', 'all 0.4s ease', 'important');
            searchContent.style.setProperty('max-height', '1000px', 'important');
            searchContent.style.setProperty('opacity', '1', 'important');
            searchContent.style.setProperty('overflow', 'hidden', 'important');
        }
        
        // Force form elements visibility
        const formElements = searchContent ? searchContent.querySelectorAll('input, select, label, button') : [];
        formElements.forEach(function(element) {
            element.style.setProperty('visibility', 'visible', 'important');
            element.style.setProperty('opacity', '1', 'important');
            element.style.setProperty('display', 'block', 'important');
        });
        
        // Force grid system
        const gridRows = searchContent ? searchContent.querySelectorAll('.row') : [];
        gridRows.forEach(function(row) {
            row.style.setProperty('display', 'flex', 'important');
            row.style.setProperty('flex-wrap', 'wrap', 'important');
            row.style.setProperty('margin', '0 -0.75rem', 'important');
        });
        
        const gridCols = searchContent ? searchContent.querySelectorAll('[class*="col-"]') : [];
        gridCols.forEach(function(col) {
            col.style.setProperty('display', 'block', 'important');
            col.style.setProperty('visibility', 'visible', 'important');
            col.style.setProperty('opacity', '1', 'important');
            col.style.setProperty('padding', '0 0.75rem', 'important');
        });
    }
    
    // Apply styles immediately
    forceApplyStyles();
    
    // Apply styles after a short delay to ensure DOM is ready
    setTimeout(forceApplyStyles, 100);
    
    // Apply styles after window load
    window.addEventListener('load', forceApplyStyles);
    
    // Apply styles on resize
    window.addEventListener('resize', function() {
        setTimeout(forceApplyStyles, 50);
    });
    
    // Add hover effects for stat items
    const statItems = document.querySelectorAll('.stat-item');
    statItems.forEach(function(item) {
        item.addEventListener('mouseenter', function() {
            this.style.setProperty('background', 'rgba(255,255,255,0.2)', 'important');
            this.style.setProperty('transform', 'translateY(-5px)', 'important');
            this.style.setProperty('box-shadow', '0 10px 25px rgba(0,0,0,0.3)', 'important');
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.setProperty('background', 'rgba(255,255,255,0.1)', 'important');
            this.style.setProperty('transform', 'translateY(0)', 'important');
            this.style.setProperty('box-shadow', 'none', 'important');
        });
    });
    
    // Add hover effects for buttons
    const clearAllBtn = document.querySelector('.clear-all-btn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('mouseenter', function() {
            this.style.setProperty('background', 'linear-gradient(135deg, #c0392b 0%, #a93226 100%)', 'important');
            this.style.setProperty('transform', 'translateY(-2px)', 'important');
            this.style.setProperty('box-shadow', '0 6px 20px rgba(231, 76, 60, 0.4)', 'important');
        });
        
        clearAllBtn.addEventListener('mouseleave', function() {
            this.style.setProperty('background', 'linear-gradient(135deg, #e74c3c 0%, #c0392b 100%)', 'important');
            this.style.setProperty('transform', 'translateY(0)', 'important');
            this.style.setProperty('box-shadow', '0 4px 15px rgba(231, 76, 60, 0.3)', 'important');
        });
    }
    
    const filterToggleBtn = document.querySelector('.filter-toggle-btn');
    if (filterToggleBtn) {
        filterToggleBtn.addEventListener('mouseenter', function() {
            this.style.setProperty('background', '#1a1a1a', 'important');
            this.style.setProperty('color', 'white', 'important');
            this.style.setProperty('transform', 'translateY(-2px)', 'important');
            this.style.setProperty('box-shadow', '0 4px 15px rgba(26, 26, 26, 0.3)', 'important');
        });
        
        filterToggleBtn.addEventListener('mouseleave', function() {
            this.style.setProperty('background', 'white', 'important');
            this.style.setProperty('color', '#1a1a1a', 'important');
            this.style.setProperty('transform', 'translateY(0)', 'important');
            this.style.setProperty('box-shadow', 'none', 'important');
        });
    }
    
    console.log('Bootstrap override script completed');
}); 