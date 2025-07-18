
import Echo from 'laravel-echo';
import io from 'socket.io-client';

window.io = io;

window.Echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001', // Thay đổi nếu port khác
    transports: ['websocket', 'polling'],
    forceTLS: false,
    disableStats: true,
});