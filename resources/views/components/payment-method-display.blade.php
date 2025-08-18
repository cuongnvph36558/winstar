@props(['paymentMethod', 'showDescription' => false, 'showFeatures' => false])

@php
    $paymentInfo = \App\Helpers\PaymentHelper::getPaymentMethodInfo($paymentMethod);
    $paymentOptions = \App\Helpers\PaymentHelper::getPaymentMethodOptions();
    $optionInfo = $paymentOptions[$paymentMethod] ?? null;
@endphp

<div class="payment-method-display">
    <div class="flex items-center">
        <div class="payment-icon mr-3">
            @if($optionInfo && $optionInfo['logo'])
                <img src="{{ asset($optionInfo['logo']) }}" alt="{{ $paymentInfo['name'] }}" class="w-10 h-6 rounded">
            @else
                <i class="{{ $paymentInfo['icon'] }} text-lg {{ $paymentInfo['color'] === 'yellow' ? 'text-yellow-600' : ($paymentInfo['color'] === 'blue' ? 'text-blue-600' : 'text-gray-600') }}"></i>
            @endif
        </div>
        <div class="payment-info">
            <div class="font-medium text-gray-900">{{ $paymentInfo['name'] }}</div>
            @if($showDescription && $paymentInfo['description'])
                <div class="text-sm text-gray-500">{{ $paymentInfo['description'] }}</div>
            @endif
            @if($showFeatures && $optionInfo && isset($optionInfo['features']))
                <div class="payment-features mt-2">
                    @foreach($optionInfo['features'] as $feature)
                        <div class="text-xs text-gray-400 flex items-center">
                            <i class="fas fa-check text-green-500 mr-1"></i>
                            {{ $feature }}
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
