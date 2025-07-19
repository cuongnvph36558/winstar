<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Coupon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Test Coupon Functionality</h1>
    
    <div>
        <h2>1. Create Test Coupon</h2>
        <button onclick="createCoupon()">Create TEST50K Coupon</button>
        <div id="create-result"></div>
    </div>

    <div>
        <h2>2. List All Coupons</h2>
        <button onclick="listCoupons()">List Coupons</button>
        <div id="list-result"></div>
    </div>

    <div>
        <h2>3. Test Coupon Validation</h2>
        <input type="text" id="coupon-code" placeholder="Coupon code" value="TEST50K">
        <input type="number" id="order-amount" placeholder="Order amount" value="500000">
        <button onclick="testValidation()">Test Validation</button>
        <div id="validation-result"></div>
    </div>

    <div>
        <h2>4. Test Apply Coupon (AJAX) - No Auth</h2>
        <input type="text" id="apply-code" placeholder="Coupon code" value="TEST50K">
        <button onclick="applyCoupon()">Apply Coupon</button>
        <div id="apply-result"></div>
    </div>

    <div>
        <h2>5. Test Apply Coupon (AJAX) - With Auth</h2>
        <input type="text" id="apply-code-auth" placeholder="Coupon code" value="TEST50K">
        <button onclick="applyCouponAuth()">Apply Coupon (Auth)</button>
        <div id="apply-result-auth"></div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function createCoupon() {
            $.get('/test-coupon')
                .done(function(data) {
                    $('#create-result').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                })
                .fail(function(xhr) {
                    $('#create-result').html('<pre>Error: ' + xhr.responseText + '</pre>');
                });
        }

        function listCoupons() {
            $.get('/list-coupons')
                .done(function(data) {
                    $('#list-result').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                })
                .fail(function(xhr) {
                    $('#list-result').html('<pre>Error: ' + xhr.responseText + '</pre>');
                });
        }

        function testValidation() {
            const code = $('#coupon-code').val();
            const amount = $('#order-amount').val();
            
            $.get('/test-coupon-validation', { code: code, amount: amount })
                .done(function(data) {
                    $('#validation-result').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                })
                .fail(function(xhr) {
                    $('#validation-result').html('<pre>Error: ' + xhr.responseText + '</pre>');
                });
        }

        function applyCoupon() {
            const code = $('#apply-code').val();
            
            $.ajax({
                url: '/test-apply-coupon',
                method: 'POST',
                data: JSON.stringify({ coupon_code: code }),
                contentType: 'application/json',
                success: function(data) {
                    $('#apply-result').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                },
                error: function(xhr) {
                    $('#apply-result').html('<pre>Error: ' + xhr.responseText + '</pre>');
                }
            });
        }

        function applyCouponAuth() {
            const code = $('#apply-code-auth').val();
            
            $.ajax({
                url: '/client/apply-coupon-auth',
                method: 'POST',
                data: JSON.stringify({ coupon_code: code }),
                contentType: 'application/json',
                success: function(data) {
                    $('#apply-result-auth').html('<pre>' + JSON.stringify(data, null, 2) + '</pre>');
                },
                error: function(xhr) {
                    $('#apply-result-auth').html('<pre>Error: ' + xhr.responseText + '</pre>');
                }
            });
        }
    </script>
</body>
</html> 