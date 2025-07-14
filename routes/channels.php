<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channel for general favorite updates
Broadcast::channel('favorites', function () {
    return true;
});
// Public channel for product card updates
Broadcast::channel('product-card', function () {
    return true;
});
// Public channel for cart updates
Broadcast::channel('cart-updates', function () {
    return true;
});
// Product-specific channel for favorite updates
Broadcast::channel('product.{productId}', function ($user, $productId) {
    return true; // Public channel
});

// Private user channel for personal notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
