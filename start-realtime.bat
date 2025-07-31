@echo off
echo ========================================
echo    KHỞI ĐỘNG HỆ THỐNG REALTIME
echo ========================================
echo.

echo [1/4] Khởi động WebSockets Server...
start "WebSockets Server" cmd /k "php artisan websockets:serve"

echo [2/4] Đợi 3 giây...
timeout /t 3 /nobreak > nul

echo [3/4] Khởi động Queue Worker...
start "Queue Worker" cmd /k "php artisan queue:work"

echo [4/4] Đợi 3 giây...
timeout /t 3 /nobreak > nul

echo.
echo ========================================
echo    KHỞI ĐỘNG HOÀN TẤT!
echo ========================================
echo.
echo Các service đã được khởi động:
echo - WebSockets Server (Port 6001)
echo - Queue Worker
echo.
echo Bây giờ bạn có thể:
echo 1. Mở trang test: http://localhost:8000/simple-test
echo 2. Mở trang test đầy đủ: http://localhost:8000/test-notifications
echo 3. Kiểm tra WebSockets dashboard: http://localhost:8000/laravel-websockets
echo.
echo Nhấn phím bất kỳ để đóng cửa sổ này...
pause > nul 