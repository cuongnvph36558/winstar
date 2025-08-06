/**
 * Professional Toast Notification System
 */
class ToastNotification {
    constructor() {
        this.container = null;
        this.toasts = [];
        this.maxToasts = 5;
        this.defaultDuration = 5000;
        this.init();
    }

    init() {
        // Create toast container if not exists
        if (!document.querySelector('.toast-container')) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        } else {
            this.container = document.querySelector('.toast-container');
        }
    }

    /**
     * Show toast notification
     * @param {string} type - success, error, warning, info
     * @param {string} title - Toast title
     * @param {string} message - Toast message
     * @param {number} duration - Duration in milliseconds (default: 5000)
     */
    show(type = 'info', title = '', message = '', duration = this.defaultDuration) {
        // Remove oldest toast if max reached
        if (this.toasts.length >= this.maxToasts) {
            this.remove(this.toasts[0]);
        }

        const toast = this.createToast(type, title, message);
        this.container.appendChild(toast);
        this.toasts.push(toast);

        // Show animation
        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        // Auto remove after duration
        if (duration > 0) {
            const progressBar = toast.querySelector('.toast-progress');
            if (progressBar) {
                progressBar.style.width = '100%';
                progressBar.style.transition = `width ${duration}ms linear`;
                setTimeout(() => {
                    progressBar.style.width = '0%';
                }, 100);
            }

            setTimeout(() => {
                this.remove(toast);
            }, duration);
        }

        return toast;
    }

    /**
     * Create toast element
     */
    createToast(type, title, message) {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.id = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);

        const icon = this.getIcon(type);
        const closeButton = this.getCloseButton();

        toast.innerHTML = `
            <div class="toast-header">
                <div style="display: flex; align-items: center;">
                    ${icon}
                    <h4 class="toast-title">${title}</h4>
                </div>
                ${closeButton}
            </div>
            <p class="toast-message">${message}</p>
            <div class="toast-progress ${type}"></div>
        `;

        // Add close event
        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                this.remove(toast);
            });
        }

        return toast;
    }

    /**
     * Get icon for toast type
     */
    getIcon(type) {
        const icons = {
            success: '<svg class="toast-icon success" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
            error: '<svg class="toast-icon error" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
            warning: '<svg class="toast-icon warning" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
            info: '<svg class="toast-icon info" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
        };

        return icons[type] || icons.info;
    }

    /**
     * Get close button
     */
    getCloseButton() {
        return '<button class="toast-close" aria-label="Close">×</button>';
    }

    /**
     * Remove toast
     */
    remove(toast) {
        if (!toast) return;

        toast.classList.add('hide');
        
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
            
            // Remove from array
            const index = this.toasts.indexOf(toast);
            if (index > -1) {
                this.toasts.splice(index, 1);
            }
        }, 300);
    }

    /**
     * Clear all toasts
     */
    clear() {
        this.toasts.forEach(toast => {
            this.remove(toast);
        });
    }

    /**
     * Success toast
     */
    success(title, message, duration) {
        return this.show('success', title, message, duration);
    }

    /**
     * Error toast
     */
    error(title, message, duration) {
        return this.show('error', title, message, duration);
    }

    /**
     * Warning toast
     */
    warning(title, message, duration) {
        return this.show('warning', title, message, duration);
    }

    /**
     * Info toast
     */
    info(title, message, duration) {
        return this.show('info', title, message, duration);
    }
}

// Initialize toast notification system
const Toast = new ToastNotification();

// Make it globally available
window.Toast = Toast;

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    // Check for flash messages from Laravel
    const flashMessages = document.querySelectorAll('[data-flash]');
    flashMessages.forEach(element => {
        const type = element.dataset.flashType || 'info';
        const title = element.dataset.flashTitle || '';
        const message = element.textContent || element.innerText;
        
        Toast.show(type, title, message);
        
        // Remove the element after showing toast
        element.remove();
    });
}); 