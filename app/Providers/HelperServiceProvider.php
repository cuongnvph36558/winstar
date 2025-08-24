<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PaymentHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register PaymentHelper as a global helper
        if (!function_exists('payment_method_display')) {
            function payment_method_display($paymentMethod) {
                return \App\Helpers\PaymentHelper::getPaymentMethodDisplay($paymentMethod);
            }
        }

        if (!function_exists('payment_status_display')) {
            function payment_status_display($paymentStatus, $orderStatus = null) {
                return \App\Helpers\PaymentHelper::getPaymentStatusDisplay($paymentStatus, $orderStatus);
            }
        }

        if (!function_exists('format_amount')) {
            function format_amount($amount) {
                return \App\Helpers\PaymentHelper::formatAmount($amount);
            }
        }

        // Register Blade directives
        Blade::directive('paymentMethod', function ($expression) {
            return "<?php echo \App\Helpers\PaymentHelper::getPaymentMethodDisplay($expression); ?>";
        });

        Blade::directive('paymentStatus', function ($expression) {
            return "<?php echo \App\Helpers\PaymentHelper::getPaymentStatusDisplay($expression); ?>";
        });

        Blade::directive('formatAmount', function ($expression) {
            return "<?php echo \App\Helpers\PaymentHelper::formatAmount($expression); ?>";
        });
    }
}
