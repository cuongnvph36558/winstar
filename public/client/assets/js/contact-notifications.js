// Contact Notifications System
(function() {
    'use strict';

    // Check if user is authenticated (this will be set by the view)
    if (typeof window.isAuthenticated === 'undefined') {
        window.isAuthenticated = false;
    }

    if (typeof window.contactCheckUrl === 'undefined') {
        window.contactCheckUrl = '';
    }

    var checkInterval;
    var lastCheckTime = new Date();

    function checkNewReplies() {
        if (!window.isAuthenticated || !window.contactCheckUrl) {
            return;
        }

        $.ajax({
            url: window.contactCheckUrl,
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.has_new_replies) {
                    showNewReplyNotification();
                }
            },
            error: function() {
                // Silently handle errors
            }
        });
    }

    function showNewReplyNotification() {
        // Create notification element
        var notification = $('<div class="new-reply-notification">' +
            '<div class="notification-content">' +
            '<i class="fa fa-bell"></i>' +
            '<span>Có phản hồi mới cho tin nhắn của bạn!</span>' +
            '<button class="notification-close"><i class="fa fa-times"></i></button>' +
            '</div>' +
            '</div>');

        // Add to page
        $('body').append(notification);

        // Show notification with animation
        setTimeout(function() {
            notification.addClass('show');
        }, 100);

        // Auto hide after 10 seconds
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 10000);

        // Close button functionality
        notification.find('.notification-close').on('click', function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        });

        // Play notification sound (optional)
        playNotificationSound();
    }

    function playNotificationSound() {
        // Create audio element for notification sound
        var audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZiTYIG2m98OScTgwOUarm7blmGgU7k9n1unEiBC13yO/eizEIHWq+8+OWT');
        audio.volume = 0.3;
        audio.play().catch(function() {
            // Ignore errors if audio can't play
        });
    }

    // Initialize notifications when document is ready
    $(document).ready(function() {
        if (window.isAuthenticated) {
            // Start checking for new replies every 30 seconds
            checkInterval = setInterval(checkNewReplies, 30000);

            // Also check when page becomes visible
            $(document).on('visibilitychange', function() {
                if (!document.hidden) {
                    checkNewReplies();
                }
            });

            // Check immediately on page load
            setTimeout(checkNewReplies, 5000);
        }
    });

    // Expose functions globally for debugging
    window.ContactNotifications = {
        checkNewReplies: checkNewReplies,
        showNewReplyNotification: showNewReplyNotification
    };

})(); 