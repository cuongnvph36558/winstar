<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStatViews extends Migration
{
    public function up(): void
    {
        // 1. view_monthly_revenue
        DB::statement("CREATE OR REPLACE VIEW view_monthly_revenue AS
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY month
        ");

        // 1.1. view_daily_revenue (new view for daily data)
        DB::statement("CREATE OR REPLACE VIEW view_daily_revenue AS
            SELECT DATE(created_at) AS date, SUM(total_amount) AS revenue
            FROM orders
            WHERE status = 'completed'
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");

        // 2. view_paid_revenue
        DB::statement("CREATE OR REPLACE VIEW view_paid_revenue AS
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_amount) AS paid_revenue
            FROM orders
            WHERE payment_status = 'paid'
            GROUP BY month
        ");

        // 2.1. view_daily_paid_revenue (new view for daily paid data)
        DB::statement("CREATE OR REPLACE VIEW view_daily_paid_revenue AS
            SELECT DATE(created_at) AS date, SUM(total_amount) AS paid_revenue
            FROM orders
            WHERE payment_status = 'paid'
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ");

        // 3. view_order_status_count
        DB::statement("CREATE OR REPLACE VIEW view_order_status_count AS
            SELECT status, COUNT(*) AS count, MIN(created_at) as created_at
            FROM orders
            GROUP BY status
        ");

        // 4. view_top_products
        DB::statement("CREATE OR REPLACE VIEW view_top_products AS
            SELECT 
                p.id, 
                p.name, 
                COALESCE(CONCAT(p.name, ' ', c.name, ' ', s.capacity), p.name) AS variant_name,
                SUM(od.quantity) AS total_sold, 
                MIN(od.created_at) as created_at
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            LEFT JOIN product_variants pv ON od.variant_id = pv.id
            LEFT JOIN colors c ON pv.color_id = c.id
            LEFT JOIN storages s ON pv.storage_id = s.id
            WHERE p.status = 1 -- Chỉ sản phẩm đang hoạt động
            GROUP BY p.id, p.name, c.name, s.capacity
            ORDER BY total_sold DESC
        ");

        // 5. view_top_coupons
        DB::statement("CREATE OR REPLACE VIEW view_top_coupons AS
            SELECT c.id, c.code, COUNT(o.id) AS total_usage, MIN(o.created_at) as created_at
            FROM coupons c
            JOIN orders o ON o.coupon_id = c.id
            GROUP BY c.id, c.code
            ORDER BY total_usage DESC
        ");

        // 6. view_revenue_by_category
        DB::statement("CREATE OR REPLACE VIEW view_revenue_by_category AS
            SELECT cat.id AS category_id, cat.name AS category_name, SUM(od.total) AS revenue
            FROM order_details od
            JOIN products p ON od.product_id = p.id
            JOIN categories cat ON p.category_id = cat.id
            WHERE p.status = 1 -- Chỉ sản phẩm đang hoạt động
            GROUP BY cat.id, cat.name
        ");

        // 7. view_most_viewed_products
        DB::statement("CREATE OR REPLACE VIEW view_most_viewed_products AS
            SELECT id, name, view
            FROM products
            WHERE status = 1 -- Chỉ sản phẩm đang hoạt động
            ORDER BY view DESC
        ");

        // 8. view_stock_by_color
        DB::statement("CREATE OR REPLACE VIEW view_stock_by_color AS
            SELECT c.id AS color_id, c.name AS color_name, SUM(pv.stock_quantity) AS total_stock
            FROM product_variants pv
            JOIN colors c ON pv.color_id = c.id
            JOIN products p ON pv.product_id = p.id
            WHERE p.status = 1 -- Chỉ sản phẩm đang hoạt động
            GROUP BY c.id, c.name
        ");

        // 9. view_user_status_count
        DB::statement("CREATE OR REPLACE VIEW view_user_status_count AS
            SELECT status, COUNT(*) AS count
            FROM users
            GROUP BY status
        ");

        // 10. view_average_product_rating
        DB::statement("CREATE OR REPLACE VIEW view_average_product_rating AS
            SELECT r.product_id, AVG(r.rating) AS avg_rating
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            WHERE p.status = 1 -- Chỉ sản phẩm đang hoạt động
            GROUP BY r.product_id
        ");

        // 11. view_storage_variants
        DB::statement("CREATE OR REPLACE VIEW view_storage_variants AS
            SELECT s.id AS storage_id, s.capacity, COUNT(pv.id) AS variant_count
            FROM storages s
            LEFT JOIN product_variants pv ON pv.storage_id = s.id
            LEFT JOIN products p ON pv.product_id = p.id
            WHERE p.status = 1 OR p.status IS NULL -- Chỉ sản phẩm đang hoạt động hoặc storage chưa được sử dụng
            GROUP BY s.id, s.capacity
        ");

        // 12. view_total_stock_per_product
        DB::statement("CREATE OR REPLACE VIEW view_total_stock_per_product AS
            SELECT p.id AS product_id, p.name AS product_name, SUM(pv.stock_quantity) AS total_stock
            FROM products p
            LEFT JOIN product_variants pv ON p.id = pv.product_id
            WHERE p.status = 1 -- Chỉ sản phẩm đang hoạt động
            GROUP BY p.id, p.name
        ");

        // 13. view_top_customers (new view for top customers)
        DB::statement("CREATE OR REPLACE VIEW view_top_customers AS
            SELECT 
                u.id,
                u.name,
                u.email,
                u.phone,
                COUNT(o.id) AS total_orders,
                SUM(o.total_amount) AS total_spent,
                AVG(o.total_amount) AS avg_order_value,
                MIN(o.created_at) as first_order_date,
                MAX(o.created_at) as last_order_date,
                COALESCE(p.earned_points, 0) AS total_earned_points,
                CASE 
                    WHEN COALESCE(p.earned_points, 0) >= 600000 THEN 'Diamond'
                    WHEN COALESCE(p.earned_points, 0) >= 390000 THEN 'Platinum'
                    WHEN COALESCE(p.earned_points, 0) >= 330000 THEN 'Gold'
                    WHEN COALESCE(p.earned_points, 0) >= 240000 THEN 'Silver'
                    ELSE 'Bronze'
                END AS vip_level
            FROM users u
            JOIN orders o ON u.id = o.user_id
            LEFT JOIN points p ON u.id = p.user_id
            WHERE o.status = 'completed'
            GROUP BY u.id, u.name, u.email, u.phone, p.earned_points
            ORDER BY total_spent DESC, total_orders DESC
        ");
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS view_monthly_revenue');
        DB::statement('DROP VIEW IF EXISTS view_daily_revenue');
        DB::statement('DROP VIEW IF EXISTS view_paid_revenue');
        DB::statement('DROP VIEW IF EXISTS view_daily_paid_revenue');
        DB::statement('DROP VIEW IF EXISTS view_order_status_count');
        DB::statement('DROP VIEW IF EXISTS view_top_products');
        DB::statement('DROP VIEW IF EXISTS view_top_coupons');
        DB::statement('DROP VIEW IF EXISTS view_revenue_by_category');
        DB::statement('DROP VIEW IF EXISTS view_most_viewed_products');
        DB::statement('DROP VIEW IF EXISTS view_stock_by_color');
        DB::statement('DROP VIEW IF EXISTS view_user_status_count');
        DB::statement('DROP VIEW IF EXISTS view_average_product_rating');
        DB::statement('DROP VIEW IF EXISTS view_storage_variants');
        DB::statement('DROP VIEW IF EXISTS view_total_stock_per_product');
    }
} 