-- Dữ liệu Services cho WinStar
-- Thêm dữ liệu vào bảng services

INSERT INTO `services` (`icon`, `title`, `description`, `order`, `created_at`, `updated_at`) VALUES
('icon-basket', 'Mua sắm trực tuyến', 'Trải nghiệm mua sắm tiện lợi, dễ dàng với giao diện thân thiện và quy trình đơn giản.', 1, NOW(), NOW()),
('icon-bike', 'Giao hàng tận nơi', 'Dịch vụ giao hàng nhanh chóng, đảm bảo sản phẩm đến tay khách hàng trong thời gian sớm nhất.', 2, NOW(), NOW()),
('icon-tools', 'Bảo hành sản phẩm', 'Chế độ bảo hành toàn diện, đổi trả linh hoạt đảm bảo quyền lợi tốt nhất cho khách hàng.', 3, NOW(), NOW()),
('icon-genius', 'Tư vấn chuyên nghiệp', 'Đội ngũ tư vấn viên giàu kinh nghiệm, hỗ trợ khách hàng chọn lựa sản phẩm phù hợp nhất.', 4, NOW(), NOW()),
('icon-mobile', 'Ứng dụng di động', 'Mua sắm mọi lúc mọi nơi với ứng dụng di động tiện lợi, tối ưu trải nghiệm người dùng.', 5, NOW(), NOW()),
('icon-lifesaver', 'Chăm sóc khách hàng', 'Dịch vụ chăm sóc khách hàng tận tâm, giải đáp mọi thắc mắc và hỗ trợ kịp thời.', 6, NOW(), NOW()); 