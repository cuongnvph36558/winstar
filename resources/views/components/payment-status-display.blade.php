@props(['paymentStatus', 'orderStatus' => null])

@php
    $statusInfo = \App\Helpers\PaymentHelper::getPaymentStatusInfo($paymentStatus, $orderStatus);
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusInfo['bg_class'] }} {{ $statusInfo['text_class'] }}">
    <i class="{{ $statusInfo['icon'] }} mr-1"></i>
    {{ $statusInfo['name'] }}
</span>
