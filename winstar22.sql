-- MySQL dump 10.13  Distrib 8.0.30, for Win64 (x86_64)
--
-- Host: localhost    Database: winstar
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `about_page`
--

DROP TABLE IF EXISTS `about_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `about_page` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `about_page`
--

LOCK TABLES `about_page` WRITE;
/*!40000 ALTER TABLE `about_page` DISABLE KEYS */;
INSERT INTO `about_page` VALUES (1,'Giới thiệu','Điện thoại thông minh hay smartphone là khái niệm để chỉ các loại thiết bị di động kết hợp điện thoại di động các chức năng điện toán di động vào một thiết bị. Chúng được phân biệt với điện thoại phổ thông bởi khả năng phần cứng mạnh hơn và hệ điều hành di động mở rộng, tạo điều kiện cho phần mềm rộng hơn, internet (bao gồm duyệt web qua băng thông rộng di động) và chức năng đa phương tiện (bao gồm âm nhạc, video, máy ảnh và chơi game), cùng với các chức năng chính của điện thoại như cuộc gọi thoại và nhắn tin văn bản.[1][2][3] Điện thoại thông minh thường chứa một số chip IC kim loại-oxit-bán dẫn (MOS), bao gồm các cảm biến khác nhau có thể được tận dụng bởi phần mềm của chúng (chẳng hạn như từ kế, cảm biến tiệm cận, phong vũ biểu, con quay hồi chuyển hoặc gia tốc kế) và hỗ trợ giao thức truyền thông không dây (chẳng hạn như Bluetooth, Wi-Fi hoặc định vị vệ tinh).\r\n\r\nĐiện thoại thông minh ban đầu được tiếp thị chủ yếu hướng tới thị trường doanh nghiệp, cố gắng kết nối chức năng của thiết bị trợ lý kỹ thuật số cá nhân PDA độc lập với hỗ trợ điện thoại di động, nhưng bị hạn chế bởi hình thức cồng kềnh, thời lượng pin ngắn, mạng di động tương tự chậm và sự non nớt của các dịch vụ dữ liệu không dây. Những vấn đề này cuối cùng đã được giải quyết với việc thu nhỏ theo cấp số nhân và thu nhỏ bóng bán dẫn MOS xuống mức dưới micromet (định luật Moore), pin lithium-ion được cải tiến, mạng dữ liệu di động kỹ thuật số nhanh hơn (định luật Edholm) và các nền tảng phần mềm hoàn thiện hơn cho phép di động hệ sinh thái thiết bị để phát triển độc lập với các nhà cung cấp dữ liệu.\r\n\r\nVào những năm 2000, nền tảng i-mode của NTT DoCoMo, BlackBerry, nền tảng Symbian của Nokia, và Windows Mobile bắt đầu giành được sức hút trên thị trường, với các mẫu máy thường có bàn phím QWERTY hoặc đầu vào màn hình cảm ứng điện trở và nhấn mạnh khả năng truy cập để gửi email và internet không dây. Sau sự phổ biến ngày càng tăng của iPhone vào cuối những năm 2000, phần lớn smartphone có kiểu dáng mỏng, dạng thanh, với màn hình điện dung lớn, hỗ trợ các cử chỉ đa chạm thay vì bàn phím vật lý và cho phép người dùng tải xuống hoặc mua các ứng dụng bổ sung từ cửa hàng tập trung và sử dụng lưu trữ và đồng bộ hóa đám mây, trợ lý ảo cũng như các dịch vụ thanh toán di động. Smartphone đã thay thế phần lớn PDA và PC cầm tay.\r\n\r\nCải tiến phần cứng và giao tiếp không dây nhanh hơn (do các tiêu chuẩn như LTE) đã thúc đẩy sự phát triển của ngành công nghiệp smartphone. Trong quý 3 năm 2012, một tỷ smartphone đã được sử dụng trên toàn thế giới. Doanh số bán smartphone toàn cầu đã vượt qua con số doanh số của điện thoại phổ thông vào đầu năm 2013.[4]\r\n\r\n\r\nChiếc iPhone đầu tiên ra mắt năm 2007 - chiếc điện thoại định hình thế giới smartphone hiện đại\r\nNhững điện thoại thông minh phổ biến nhất hiện nay dựa trên nền tảng của 2 hệ điều hành thành công nhất là Android của Google và iOS của Apple.[5]\r\n\r\nLịch sử\r\nĐiện thoại thông minh đời đầu chủ yếu được tiếp thị cho thị trường doanh nghiệp, nhằm kết hợp chức năng của các thiết bị PDA độc lập với khả năng hỗ trợ điện thoại di động, nhưng bị hạn chế bởi thiết kế cồng kềnh, thời lượng pin ngắn, mạng di động analog chậm và sự non nớt của các dịch vụ dữ liệu không dây. Những vấn đề này cuối cùng đã được giải quyết với sự phát triển theo cấp số nhân và thu nhỏ của bóng bán dẫn MOS xuống cấp độ sub-micron (luật Moore), việc cải thiện pin lithium-ion, mạng dữ liệu di động kỹ thuật số nhanh hơn (luật Edholm) và nền tảng phần mềm trưởng thành hơn cho phép hệ sinh thái thiết bị di động phát triển độc lập với nhà cung cấp dữ liệu.\r\n\r\nTrong những năm 2000, nền tảng i-mode của NTT DoCoMo, BlackBerry, nền tảng Symbian của Nokia và Windows Mobile bắt đầu chiếm được thị phần, với các mẫu máy thường có bàn phím QWERTY hoặc màn hình cảm ứng điện trở và nhấn mạnh vào việc truy cập email push và internet không dây.\r\n\r\nTiền thân\r\n\r\nIBM Simon và đế sạc (1994)[6]\r\nĐầu những năm 1990, kỹ sư Frank Canova của IBM nhận ra rằng công nghệ chip và không dây đang trở nên đủ nhỏ để sử dụng trong các thiết bị cầm tay.[7] Thiết bị thương mại đầu tiên có thể được gọi một cách chính xác là \"điện thoại thông minh\" bắt đầu với tư cách là một nguyên mẫu có tên \"Angler\" được phát triển bởi Canova vào năm 1992 khi đang làm việc tại IBM và được trình diễn vào tháng 11 năm đó tại triển lãm thương mại ngành máy tính COMDEX.[8][9][10] Một phiên bản tinh chỉnh đã được đưa ra thị trường cho người tiêu dùng vào năm 1994 bởi BellSouth dưới tên gọi Simon Personal Communicator. Ngoài việc thực hiện và nhận các cuộc gọi di động, Simon được trang bị màn hình cảm ứng có thể gửi và nhận fax và email. Nó bao gồm sổ địa chỉ, lịch, lịch hẹn, máy tính, đồng hồ giờ thế giới và sổ ghi chép, cũng như các ứng dụng di động vision khác như bản đồ, báo cáo chứng khoán và tin tức.[11]\r\n\r\nIBM Simon được sản xuất bởi Mitsubishi Electric, công ty đã tích hợp các tính năng của Simon với công nghệ radio di động của riêng mình.[12] Simon có màn hình tinh thể lỏng (LCD) và hỗ trợ thẻ PC.[13] Simon không thành công về mặt thương mại, đặc biệt là do kiểu dáng cồng kềnh và thời lượng pin hạn chế,[14] sử dụng pin NiCad thay vì pin nickel-kim loại hydride thường được sử dụng trong điện thoại di động vào những năm 1990 hoặc pin lithium-ion được sử dụng trong điện thoại thông minh hiện đại.[15]\r\n\r\nThuật ngữ \"điện thoại thông minh\" không được đặt ra cho đến một năm sau khi Simon ra mắt, xuất hiện trong các ấn phẩm in từ năm 1995, mô tả thiết bị PhoneWriter Communicator của AT&T.[16] Ericsson lần đầu tiên sử dụng thuật ngữ \"smartphone\" vào năm 1997 để mô tả một khái niệm thiết bị mới, GS88.[17]\r\n\r\nĐiện thoại thông minh đời đầu\r\n\r\nMột số điện thoại thông minh BlackBerry, vốn rất phổ biến vào giữa đến cuối những năm 2000\r\nCho đến khi Danger Hiptop được giới thiệu vào năm 2002, những chiếc điện thoại sử dụng kết nối dữ liệu hiệu quả vẫn còn hiếm bên ngoài Nhật Bản. Danger Hiptop đã thành công ở mức độ vừa phải với người tiêu dùng Hoa Kỳ với tên gọi T-Mobile Sidekick. Sau đó, vào giữa những năm 2000, người dùng doanh nghiệp ở Hoa Kỳ bắt đầu sử dụng các thiết bị dựa trên Windows Mobile của Microsoft và sau đó là điện thoại thông minh BlackBerry của Research In Motion. Người dùng Mỹ đã phổ biến thuật ngữ \"CrackBerry\" vào năm 2006 do tính chất gây nghiện của BlackBerry.[18] Ở Hoa Kỳ, chi phí cao của các gói dữ liệu và sự khan hiếm tương đối của các thiết bị có khả năng Wi-Fi có thể tránh sử dụng mạng dữ liệu di động đã khiến việc áp dụng điện thoại thông minh chủ yếu dành cho các chuyên gia kinh doanh và','2025-08-19 06:10:31','2025-08-19 06:10:31');
/*!40000 ALTER TABLE `about_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `attendance`
--

DROP TABLE IF EXISTS `attendance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `attendance` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `date` date NOT NULL COMMENT 'Ngày điểm danh',
  `check_in_time` time DEFAULT NULL COMMENT 'Thời gian điểm danh vào',
  `check_out_time` time DEFAULT NULL COMMENT 'Thời gian điểm danh ra',
  `points_earned` int NOT NULL DEFAULT '0' COMMENT 'Điểm tích được từ điểm danh',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'present' COMMENT 'Trạng thái: present, absent, late',
  `notes` text COLLATE utf8mb4_unicode_ci COMMENT 'Ghi chú',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `points_claimed` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Đã tích điểm chưa',
  PRIMARY KEY (`id`),
  UNIQUE KEY `attendance_user_id_date_unique` (`user_id`,`date`),
  KEY `attendance_user_id_date_index` (`user_id`,`date`),
  KEY `attendance_date_index` (`date`),
  KEY `attendance_status_index` (`status`),
  CONSTRAINT `attendance_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendance`
--

LOCK TABLES `attendance` WRITE;
/*!40000 ALTER TABLE `attendance` DISABLE KEYS */;
INSERT INTO `attendance` VALUES (1,1,'2025-07-21','07:15:00','17:21:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(2,1,'2025-07-23','07:03:00','17:36:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(3,1,'2025-07-24','09:33:00','18:47:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(4,1,'2025-07-25','08:42:00','17:05:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(5,1,'2025-07-29','07:20:00','19:03:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(6,1,'2025-07-30','08:25:00','18:18:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(7,1,'2025-07-31','08:53:00','17:25:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(8,1,'2025-08-01','08:48:00','17:45:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(9,1,'2025-08-04','07:44:00','18:00:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(10,1,'2025-08-05','07:27:00','17:38:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(11,1,'2025-08-06','08:53:00','18:39:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(12,1,'2025-08-07','08:14:00','17:43:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(13,1,'2025-08-08','08:21:00','18:40:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(14,1,'2025-08-11','08:31:00','18:46:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(15,1,'2025-08-12','08:09:00','18:24:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(16,1,'2025-08-14','07:56:00','17:22:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(17,1,'2025-08-15','09:15:00','17:21:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(18,2,'2025-07-21','07:39:00','17:58:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(19,2,'2025-07-22','07:21:00','19:14:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(20,2,'2025-07-23','08:37:00','18:31:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(21,2,'2025-07-24','08:48:00','18:58:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(22,2,'2025-07-25','09:36:00','17:38:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(23,2,'2025-07-29','09:56:00','17:29:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(24,2,'2025-07-31','08:06:00','19:29:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(25,2,'2025-08-01','07:33:00','19:30:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(26,2,'2025-08-05','07:20:00','19:27:00',135,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(27,2,'2025-08-08','07:06:00','17:03:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:36','2025-08-18 13:56:36',0),(28,2,'2025-08-11','09:09:00','18:34:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(29,2,'2025-08-13','08:56:00','18:56:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(30,2,'2025-08-14','09:18:00','18:20:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(31,2,'2025-08-15','07:43:00','19:41:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(32,2,'2025-08-18','07:28:00','18:27:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(33,3,'2025-07-21','08:44:00','17:21:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(34,3,'2025-07-22','07:17:00','18:33:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(35,3,'2025-07-23','08:51:00','19:28:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(36,3,'2025-07-24','07:29:00','18:05:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(37,3,'2025-07-25','07:58:00','19:39:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(38,3,'2025-07-28','08:39:00','18:22:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(39,3,'2025-07-29','07:52:00','17:43:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(40,3,'2025-07-30','08:35:00','17:32:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(41,3,'2025-07-31','07:30:00','17:03:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(42,3,'2025-08-01','07:13:00','18:48:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(43,3,'2025-08-04','08:28:00','17:16:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(44,3,'2025-08-05','09:04:00','17:21:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(45,3,'2025-08-06','09:24:00','17:54:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(46,3,'2025-08-07','09:58:00','17:13:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(47,3,'2025-08-08','09:32:00','18:35:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(48,3,'2025-08-11','09:55:00','17:36:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(49,3,'2025-08-12','08:59:00','17:27:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(50,3,'2025-08-13','09:37:00','18:54:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(51,3,'2025-08-14','09:18:00','18:39:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(52,3,'2025-08-18','08:59:00','18:34:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(53,4,'2025-07-21','07:23:00','17:10:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(54,4,'2025-07-22','09:01:00','17:28:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(55,4,'2025-07-23','09:44:00','18:54:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(56,4,'2025-07-24','09:48:00','18:23:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(57,4,'2025-07-25','08:49:00','19:46:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(58,4,'2025-07-29','07:17:00','17:54:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(59,4,'2025-07-30','08:09:00','17:30:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(60,4,'2025-07-31','07:17:00','17:50:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(61,4,'2025-08-04','07:35:00','18:49:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(62,4,'2025-08-06','07:45:00','18:34:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(63,4,'2025-08-07','09:35:00','19:51:00',110,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(64,4,'2025-08-11','09:11:00','19:04:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(65,4,'2025-08-12','08:50:00','19:01:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(66,4,'2025-08-13','07:37:00','18:18:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(67,4,'2025-08-14','09:33:00','19:03:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(68,4,'2025-08-15','07:37:00','17:45:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(69,4,'2025-08-18','09:32:00','19:25:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(70,5,'2025-07-21','07:21:00','17:10:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(71,5,'2025-07-22','09:59:00','17:13:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(72,5,'2025-07-24','08:06:00','17:46:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(73,5,'2025-07-25','09:46:00','17:28:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(74,5,'2025-07-28','09:58:00','19:40:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(75,5,'2025-07-29','07:06:00','18:57:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(76,5,'2025-07-30','07:59:00','18:15:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(77,5,'2025-07-31','09:48:00','19:41:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(78,5,'2025-08-01','07:48:00','18:15:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(79,5,'2025-08-04','08:36:00','17:13:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(80,5,'2025-08-05','09:40:00','18:38:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(81,5,'2025-08-06','08:36:00','19:45:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(82,5,'2025-08-08','09:32:00','19:15:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(83,5,'2025-08-11','08:57:00','18:04:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(84,5,'2025-08-12','08:46:00','19:03:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(85,5,'2025-08-13','07:58:00','18:25:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(86,5,'2025-08-14','07:08:00','17:31:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(87,5,'2025-08-18','08:59:00','17:38:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(88,6,'2025-07-21','09:58:00','17:47:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(89,6,'2025-07-22','07:53:00','17:07:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(90,6,'2025-07-23','08:40:00','18:41:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(91,6,'2025-07-25','08:33:00','17:49:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(92,6,'2025-07-28','07:28:00','17:02:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(93,6,'2025-07-29','07:52:00','17:41:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(94,6,'2025-07-30','08:29:00','18:24:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(95,6,'2025-07-31','09:05:00','17:43:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(96,6,'2025-08-04','07:12:00','18:51:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(97,6,'2025-08-05','09:28:00','18:12:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(98,6,'2025-08-06','07:39:00','19:46:00',135,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(99,6,'2025-08-07','07:38:00','19:06:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(100,6,'2025-08-08','09:52:00','18:26:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(101,6,'2025-08-11','08:44:00','19:27:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(102,6,'2025-08-12','08:52:00','17:12:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(103,6,'2025-08-14','07:08:00','19:39:00',135,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(104,6,'2025-08-15','07:46:00','19:48:00',135,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(105,6,'2025-08-18','08:57:00','17:29:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(106,7,'2025-07-21','09:14:00','19:51:00',110,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(107,7,'2025-07-22','07:51:00','18:14:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(108,7,'2025-07-23','07:05:00','17:14:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(109,7,'2025-07-24','07:20:00','19:54:00',135,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(110,7,'2025-07-25','09:24:00','17:50:00',90,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(111,7,'2025-07-28','08:17:00','19:32:00',125,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(112,7,'2025-07-29','08:47:00','17:44:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(113,7,'2025-07-30','07:15:00','17:43:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(114,7,'2025-07-31','09:26:00','18:54:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(115,7,'2025-08-01','09:22:00','17:00:00',75,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(116,7,'2025-08-04','08:18:00','19:11:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(117,7,'2025-08-05','08:16:00','17:13:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(118,7,'2025-08-06','09:27:00','18:49:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(119,7,'2025-08-07','08:55:00','18:26:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(120,7,'2025-08-08','07:11:00','17:05:00',105,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(121,7,'2025-08-12','09:09:00','19:14:00',110,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(122,7,'2025-08-13','08:46:00','17:29:00',95,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(123,7,'2025-08-14','09:41:00','18:42:00',100,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(124,7,'2025-08-15','08:31:00','19:30:00',115,'present','Dữ liệu mẫu','2025-08-18 13:56:37','2025-08-18 13:56:37',0),(125,1,'2025-08-19','11:31:12','11:31:12',100,'present',NULL,'2025-08-19 04:31:12','2025-08-19 04:31:12',1);
/*!40000 ALTER TABLE `attendance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `authors`
--

DROP TABLE IF EXISTS `authors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `authors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `authors_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `authors`
--

LOCK TABLES `authors` WRITE;
/*!40000 ALTER TABLE `authors` DISABLE KEYS */;
INSERT INTO `authors` VALUES (1,'Vũ Thị Mai','benton34@example.net','Kỹ sư phần cứng, chuyên về thiết kế chip và bo mạch.','https://via.placeholder.com/200x200.png/00dddd?text=people+rerum','https://www.west.com/et-fugiat-assumenda-similique-magnam-quasi-et-labore','2025-08-18 13:56:34','2025-08-18 13:56:34'),(2,'Trần Thị Lan','flatley.audie@example.net','Chuyên gia đánh giá sản phẩm công nghệ với góc nhìn khách quan.','https://via.placeholder.com/200x200.png/00bbff?text=people+eos','https://maggio.net/vel-labore-et-possimus-voluptatem-accusamus-reiciendis-ex.html','2025-08-18 13:56:34','2025-08-18 13:56:34'),(3,'Vũ Thị Mai','felicia35@example.com','Chuyên gia về trải nghiệm người dùng và thiết kế giao diện.','https://via.placeholder.com/200x200.png/0000dd?text=people+qui','https://www.beer.com/voluptas-natus-rem-recusandae-sint-minus-dolorem-omnis-praesentium','2025-08-18 13:56:34','2025-08-18 13:56:34'),(4,'Lê Hoàng Nam','howe.evalyn@example.net','Chuyên gia công nghệ với hơn 10 năm kinh nghiệm trong lĩnh vực điện thoại di động.','https://via.placeholder.com/200x200.png/00dd77?text=people+ea',NULL,'2025-08-18 13:56:34','2025-08-18 13:56:34'),(5,'Đặng Văn Hùng','zswift@example.net','Chuyên gia về camera và công nghệ chụp ảnh trên điện thoại.','https://via.placeholder.com/200x200.png/006622?text=people+quis',NULL,'2025-08-18 13:56:34','2025-08-18 13:56:34'),(6,'Quản Trị Viên','admin@winstar.com','Admin author',NULL,NULL,'2025-08-19 06:16:39','2025-08-19 06:16:39');
/*!40000 ALTER TABLE `authors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banners` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `status` enum('1','0') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'banners/YK9u0jW9jSkizaDr4N0WjaPVF8ofxactliD0sCfF.webp','#','iPhone 15 Pro Max - Đẳng cấp mới','2025-08-15 00:00:00','2025-08-31 00:00:00','1','2025-08-18 13:56:36','2025-08-19 06:11:26',NULL),(2,'banners/K4GaCGO9kOCazwqIoCLmhmWYOpKLRs7atXPRW68f.jpg','#','Samsung Galaxy S24 Ultra - Sáng tạo vô tận','2025-08-14 00:00:00','2025-08-31 00:00:00','1','2025-08-18 13:56:36','2025-08-19 06:11:41',NULL),(3,'banners/NLIdI0CEZn3D8TDa0W1jTlRHLNq8kRubdsTfkXoc.jpg','#','Xiaomi 14 Ultra - Hiệu năng đỉnh cao','2025-08-19 00:00:00','2025-08-31 00:00:00','1','2025-08-18 13:56:36','2025-08-19 06:11:59',NULL),(4,'banners/banner-4-oppo.jpg','#','OPPO Find X7 Ultra - Nghệ thuật nhiếp ảnh','2025-08-18 20:56:36','2026-02-18 20:56:36','1','2025-08-18 13:56:36','2025-08-19 06:12:25','2025-08-19 06:12:25');
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_details`
--

DROP TABLE IF EXISTS `cart_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_details_cart_id_foreign` (`cart_id`),
  KEY `cart_details_product_id_foreign` (`product_id`),
  KEY `cart_details_variant_id_foreign` (`variant_id`),
  CONSTRAINT `cart_details_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_details_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_details`
--

LOCK TABLES `cart_details` WRITE;
/*!40000 ALTER TABLE `cart_details` DISABLE KEYS */;
INSERT INTO `cart_details` VALUES (4,4,17,105,6,11990000.00,'2025-08-19 05:51:14','2025-08-19 05:51:14');
/*!40000 ALTER TABLE `cart_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `is_temp` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `carts_user_id_foreign` (`user_id`),
  CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (3,10,0,'2025-08-19 05:49:34','2025-08-19 05:49:34'),(4,1,0,'2025-08-19 05:51:14','2025-08-19 05:51:14');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Apple',NULL,'Các sản phẩm điện thoại Apple iPhone','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(2,'Samsung',NULL,'Các sản phẩm điện thoại Samsung','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(3,'Xiaomi',NULL,'Các sản phẩm điện thoại Xiaomi','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(4,'OPPO',NULL,'Các sản phẩm điện thoại OPPO','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(5,'Vivo',NULL,'Các sản phẩm điện thoại Vivo','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(6,'Realme',NULL,'Các sản phẩm điện thoại Realme','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(7,'OnePlus',NULL,'Các sản phẩm điện thoại OnePlus','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(8,'Huawei',NULL,'Các sản phẩm điện thoại Huawei','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(9,'Nokia',NULL,'Các sản phẩm điện thoại Nokia','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(10,'Motorola',NULL,'Các sản phẩm điện thoại Motorola','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL),(11,'ASUS',NULL,'Các sản phẩm điện thoại ASUS','2025-08-18 13:56:34','2025-08-18 13:56:34',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat_messages`
--

DROP TABLE IF EXISTS `chat_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender` enum('user','bot') COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_messages_user_id_created_at_index` (`user_id`,`created_at`),
  KEY `chat_messages_user_id_sender_is_read_index` (`user_id`,`sender`,`is_read`),
  CONSTRAINT `chat_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat_messages`
--

LOCK TABLES `chat_messages` WRITE;
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `colors`
--

DROP TABLE IF EXISTS `colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `colors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color_code` varchar(7) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã màu hex (ví dụ: #FF0000)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `colors`
--

LOCK TABLES `colors` WRITE;
/*!40000 ALTER TABLE `colors` DISABLE KEYS */;
INSERT INTO `colors` VALUES (1,'Đen','#000000','#000000','2025-08-18 13:56:34','2025-08-18 13:56:34'),(2,'Trắng','#FFFFFF','#FFFFFF','2025-08-18 13:56:34','2025-08-18 13:56:34'),(3,'Xám','#808080','#808080','2025-08-18 13:56:34','2025-08-18 13:56:34'),(4,'Bạc','#C0C0C0','#C0C0C0','2025-08-18 13:56:34','2025-08-18 13:56:34'),(5,'Vàng','#FFD700','#FFD700','2025-08-18 13:56:34','2025-08-18 13:56:34'),(6,'Hồng','#FFC0CB','#FFC0CB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(7,'Đỏ','#FF0000','#FF0000','2025-08-18 13:56:34','2025-08-18 13:56:34'),(8,'Xanh dương','#0000FF','#0000FF','2025-08-18 13:56:34','2025-08-18 13:56:34'),(9,'Xanh lá','#00FF00','#00FF00','2025-08-18 13:56:34','2025-08-18 13:56:34'),(10,'Tím','#800080','#800080','2025-08-18 13:56:34','2025-08-18 13:56:34'),(11,'Xám không gian','#2C2C2C','#2C2C2C','2025-08-18 13:56:34','2025-08-18 13:56:34'),(12,'Trắng ánh sao','#F5F2EB','#F5F2EB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(13,'Đen bóng đêm','#1E1E2F','#1E1E2F','2025-08-18 13:56:34','2025-08-18 13:56:34'),(14,'Xanh biển','#4B9CD3','#4B9CD3','2025-08-18 13:56:34','2025-08-18 13:56:34'),(15,'Cam san hô','#FF6F61','#FF6F61','2025-08-18 13:56:34','2025-08-18 13:56:34'),(16,'Titan tự nhiên','#D6D6D6','#D6D6D6','2025-08-18 13:56:34','2025-08-18 13:56:34'),(17,'Titan trắng','#F8F8F8','#F8F8F8','2025-08-18 13:56:34','2025-08-18 13:56:34'),(18,'Titan đen','#1A1A1A','#1A1A1A','2025-08-18 13:56:34','2025-08-18 13:56:34'),(19,'Titan xanh','#4D6A78','#4D6A78','2025-08-18 13:56:34','2025-08-18 13:56:34'),(20,'Đỏ sản phẩm','#BE0032','#BE0032','2025-08-18 13:56:34','2025-08-18 13:56:34'),(21,'Xanh navy','#000080','#000080','2025-08-18 13:56:34','2025-08-18 13:56:34'),(22,'Xanh rêu','#4B5320','#4B5320','2025-08-18 13:56:34','2025-08-18 13:56:34'),(23,'Xanh ngọc','#40E0D0','#40E0D0','2025-08-18 13:56:34','2025-08-18 13:56:34'),(24,'Xanh mint','#98FF98','#98FF98','2025-08-18 13:56:34','2025-08-18 13:56:34'),(25,'Tím lavender','#E6E6FA','#E6E6FA','2025-08-18 13:56:34','2025-08-18 13:56:34'),(26,'Hồng rose','#FFE4E1','#FFE4E1','2025-08-18 13:56:34','2025-08-18 13:56:34'),(27,'Vàng gold','#FFD700','#FFD700','2025-08-18 13:56:34','2025-08-18 13:56:34'),(28,'Đen phantom','#1A1A1A','#1A1A1A','2025-08-18 13:56:34','2025-08-18 13:56:34'),(29,'Trắng cream','#FFFDD0','#FFFDD0','2025-08-18 13:56:34','2025-08-18 13:56:34'),(30,'Xanh ocean','#006994','#006994','2025-08-18 13:56:34','2025-08-18 13:56:34'),(31,'Đen carbon','#2C2C2C','#2C2C2C','2025-08-18 13:56:34','2025-08-18 13:56:34'),(32,'Trắng pearl','#F5F5F5','#F5F5F5','2025-08-18 13:56:34','2025-08-18 13:56:34'),(33,'Xanh forest','#228B22','#228B22','2025-08-18 13:56:34','2025-08-18 13:56:34'),(34,'Tím violet','#8A2BE2','#8A2BE2','2025-08-18 13:56:34','2025-08-18 13:56:34'),(35,'Xanh sky','#87CEEB','#87CEEB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(36,'Đen midnight','#191970','#191970','2025-08-18 13:56:34','2025-08-18 13:56:34'),(37,'Trắng snow','#FFFAFA','#FFFAFA','2025-08-18 13:56:34','2025-08-18 13:56:34'),(38,'Xanh emerald','#50C878','#50C878','2025-08-18 13:56:34','2025-08-18 13:56:34'),(39,'Hồng sakura','#FFB7C5','#FFB7C5','2025-08-18 13:56:34','2025-08-18 13:56:34'),(40,'Xanh sapphire','#0F52BA','#0F52BA','2025-08-18 13:56:34','2025-08-18 13:56:34'),(41,'Đen obsidian','#1A1A1A','#1A1A1A','2025-08-18 13:56:34','2025-08-18 13:56:34'),(42,'Trắng crystal','#F8F8FF','#F8F8FF','2025-08-18 13:56:34','2025-08-18 13:56:34'),(43,'Xanh jade','#00A86B','#00A86B','2025-08-18 13:56:34','2025-08-18 13:56:34'),(44,'Tím amethyst','#9966CC','#9966CC','2025-08-18 13:56:34','2025-08-18 13:56:34');
/*!40000 ALTER TABLE `colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `guest_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `rating` int DEFAULT NULL COMMENT 'Rating from 1 to 5 stars',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `post_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_product_id_foreign` (`product_id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_post_id_foreign` (`post_id`),
  CONSTRAINT `comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contacts_user_id_foreign` (`user_id`),
  CONSTRAINT `contacts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupon_users`
--

DROP TABLE IF EXISTS `coupon_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupon_users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `coupon_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coupon_users_user_id_foreign` (`user_id`),
  KEY `coupon_users_coupon_id_foreign` (`coupon_id`),
  KEY `coupon_users_order_id_foreign` (`order_id`),
  CONSTRAINT `coupon_users_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_users_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupon_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupon_users`
--

LOCK TABLES `coupon_users` WRITE;
/*!40000 ALTER TABLE `coupon_users` DISABLE KEYS */;
INSERT INTO `coupon_users` VALUES (1,1,1,'2025-08-13 13:56:36','2025-07-21 13:56:36',NULL,NULL),(2,1,1,'2025-07-19 13:56:36','2025-08-07 13:56:36',NULL,NULL),(3,1,2,'2025-07-24 13:56:36','2025-07-23 13:56:36',NULL,NULL),(4,1,2,'2025-08-07 13:56:36','2025-08-03 13:56:36',NULL,NULL),(5,1,3,'2025-08-03 13:56:36','2025-08-05 13:56:36',NULL,NULL),(6,1,3,'2025-07-29 13:56:36','2025-08-16 13:56:36',NULL,NULL),(7,2,1,'2025-08-02 13:56:36','2025-07-27 13:56:36',NULL,NULL),(8,2,2,'2025-07-23 13:56:36','2025-08-02 13:56:36',NULL,NULL),(9,2,3,'2025-07-21 13:56:36','2025-08-09 13:56:36',NULL,NULL),(10,3,1,'2025-08-03 13:56:36','2025-07-27 13:56:36',NULL,NULL),(11,3,2,'2025-07-23 13:56:36','2025-08-10 13:56:36',NULL,NULL),(12,3,3,'2025-08-04 13:56:36','2025-07-20 13:56:36',NULL,NULL),(13,4,1,'2025-08-11 13:56:36','2025-08-14 13:56:36',NULL,NULL),(14,4,1,'2025-08-12 13:56:36','2025-08-16 13:56:36',NULL,NULL),(15,4,2,'2025-08-06 13:56:36','2025-08-02 13:56:36',NULL,NULL),(16,4,2,'2025-08-10 13:56:36','2025-08-11 13:56:36',NULL,NULL),(17,4,3,'2025-08-05 13:56:36','2025-07-25 13:56:36',NULL,NULL),(18,5,1,'2025-08-07 13:56:36','2025-07-29 13:56:36',NULL,NULL),(19,5,1,'2025-08-03 13:56:36','2025-07-31 13:56:36',NULL,NULL),(20,5,2,'2025-08-04 13:56:36','2025-08-04 13:56:36',NULL,NULL),(21,5,3,'2025-08-13 13:56:36','2025-07-29 13:56:36',NULL,NULL),(22,5,3,'2025-08-16 13:56:36','2025-07-20 13:56:36',NULL,NULL);
/*!40000 ALTER TABLE `coupon_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_order_value` decimal(10,2) DEFAULT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `max_discount_value` decimal(10,2) DEFAULT NULL,
  `max_discount` decimal(10,2) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `usage_limit` int DEFAULT NULL,
  `usage_limit_per_user` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '1',
  `exchange_points` int NOT NULL DEFAULT '0',
  `vip_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `validity_days` int NOT NULL DEFAULT '30',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (1,'BRONZE1','Mã Giảm Giá Bronze - 1%','Giảm giá 1% cho khách hàng Bronze','percentage',5000000.00,1.00,NULL,100000.00,'2025-08-18','2026-02-18',1000,NULL,0,1,50000,'Bronze',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(2,'BRONZE2','Mã Giảm Giá Bronze - 2%','Giảm giá 2% cho khách hàng Bronze','percentage',10000000.00,2.00,NULL,200000.00,'2025-08-18','2026-02-18',800,NULL,0,1,100000,'Bronze',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(3,'SILVER2','Mã Giảm Giá Silver - 2%','Giảm giá 2% cho khách hàng Silver','percentage',5000000.00,2.00,NULL,200000.00,'2025-08-18','2026-02-18',600,NULL,0,1,80000,'Silver',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(4,'SILVER3','Mã Giảm Giá Silver - 3%','Giảm giá 3% cho khách hàng Silver','percentage',10000000.00,3.00,NULL,300000.00,'2025-08-18','2026-02-18',500,NULL,0,1,150000,'Silver',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(5,'GOLD3','Mã Giảm Giá Gold - 3%','Giảm giá 3% cho khách hàng Gold','percentage',5000000.00,3.00,NULL,300000.00,'2025-08-18','2026-02-18',400,NULL,0,1,120000,'Gold',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(6,'GOLD4','Mã Giảm Giá Gold - 4%','Giảm giá 4% cho khách hàng Gold','percentage',10000000.00,4.00,NULL,400000.00,'2025-08-18','2026-02-18',300,NULL,0,1,200000,'Gold',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(7,'PLATINUM4','Mã Giảm Giá Platinum - 4%','Giảm giá 4% cho khách hàng Platinum','percentage',5000000.00,4.00,NULL,400000.00,'2025-08-18','2026-02-18',250,NULL,0,1,160000,'Platinum',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(8,'PLATINUM5','Mã Giảm Giá Platinum - 5%','Giảm giá 5% cho khách hàng Platinum','percentage',10000000.00,5.00,NULL,500000.00,'2025-08-18','2026-02-18',200,NULL,0,1,300000,'Platinum',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(9,'DIAMOND5','Mã Giảm Giá Diamond - 5%','Giảm giá 5% cho khách hàng Diamond','percentage',5000000.00,5.00,NULL,500000.00,'2025-08-18','2026-02-18',150,NULL,0,1,200000,'Diamond',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(10,'DIAMOND6','Mã Giảm Giá Diamond - 6%','Giảm giá 6% cho khách hàng Diamond','percentage',10000000.00,6.00,NULL,600000.00,'2025-08-18','2026-02-18',100,NULL,0,1,400000,'Diamond',30,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL);
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `favorites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `favorites_user_id_foreign` (`user_id`),
  KEY `favorites_product_id_foreign` (`product_id`),
  CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feature_items`
--

DROP TABLE IF EXISTS `feature_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feature_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `feature_id` bigint unsigned NOT NULL,
  `icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feature_items_feature_id_foreign` (`feature_id`),
  CONSTRAINT `feature_items_feature_id_foreign` FOREIGN KEY (`feature_id`) REFERENCES `features` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feature_items`
--

LOCK TABLES `feature_items` WRITE;
/*!40000 ALTER TABLE `feature_items` DISABLE KEYS */;
INSERT INTO `feature_items` VALUES (5,1,'icon-strategy','Chất lượng cao','Sản phẩm được tuyển chọn kỹ lưỡng với chất lượng đảm bảo và giá cả hợp lý.','2025-08-19 06:12:58','2025-08-19 06:12:58'),(6,1,'icon-tools-2','Giao hàng nhanh','Giao hàng toàn quốc với thời gian nhanh chóng và an toàn.','2025-08-19 06:12:58','2025-08-19 06:12:58'),(7,1,'icon-mobile','Thanh toán đa dạng','Hỗ trợ nhiều hình thức thanh toán tiện lợi và bảo mật.','2025-08-19 06:12:58','2025-08-19 06:12:58'),(8,1,'icon-lifesaver','Hỗ trợ 24/7','Đội ngũ chăm sóc khách hàng tận tình, hỗ trợ 24/7.','2025-08-19 06:12:58','2025-08-19 06:12:58');
/*!40000 ALTER TABLE `feature_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `features`
--

DROP TABLE IF EXISTS `features`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `features` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `features`
--

LOCK TABLES `features` WRITE;
/*!40000 ALTER TABLE `features` DISABLE KEYS */;
INSERT INTO `features` VALUES (1,'Tại sao chọn chúng tôi','Cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng','features/ZQUi8vOoibBRAwZLcwXVBybT3Nmju6OXgtcw9Gmw.jpg','active','2025-08-18 13:56:36','2025-08-19 06:12:58');
/*!40000 ALTER TABLE `features` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `message_type` enum('text','image','file') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0000_00_00_000000_create_websockets_statistics_entries_table',1),(2,'2019_12_14_000001_create_personal_access_tokens_table',1),(3,'2024_06_25_000001_create_about_page_table',1),(4,'2024_06_25_000002_create_videos_table',1),(5,'2025_06_24_223420_create_users_table',1),(6,'2025_06_24_223421_create_points_table',1),(7,'2025_06_24_223422_create_point_transactions_table',1),(8,'2025_06_24_223423_create_point_vouchers_table',1),(9,'2025_06_24_223500_create_roles_table',1),(10,'2025_06_24_223507_create_permissions_table',1),(11,'2025_06_24_223516_create_user_roles_table',1),(12,'2025_06_24_223532_create_role_permissions_table',1),(13,'2025_06_24_223539_create_categories_table',1),(14,'2025_06_24_223545_create_colors_table',1),(15,'2025_06_24_223552_create_storages_table',1),(16,'2025_06_24_223558_create_products_table',1),(17,'2025_06_24_223559_add_compare_price_to_products_table',1),(18,'2025_06_24_223603_create_product_variants_table',1),(19,'2025_06_24_223608_create_carts_table',1),(20,'2025_06_24_223614_create_cart_details_table',1),(21,'2025_06_24_223617_create_coupons_table',1),(22,'2025_06_24_223618_create_orders_table',1),(23,'2025_06_24_223619_create_user_point_vouchers_table',1),(24,'2025_06_24_223620_add_points_to_orders_table',1),(25,'2025_06_24_223623_create_order_details_table',1),(26,'2025_06_24_223654_create_favorites_table',1),(27,'2025_06_24_223706_create_messages_table',1),(28,'2025_06_24_223716_create_contacts_table',1),(29,'2025_06_24_223720_create_authors_table',1),(30,'2025_06_24_223727_create_coupon_users_table',1),(31,'2025_06_24_223733_create_posts_table',1),(32,'2025_06_24_223738_create_banners_table',1),(33,'2025_06_24_223800_create_vnpay_transactions_table',1),(34,'2025_06_25_170541_create_comments_table',1),(35,'2025_06_25_223847_add_color_code_to_colors_table',1),(36,'2025_07_01_000001_add_post_id_to_comments_table',1),(37,'2025_07_02_151756_create_reviews_table',1),(38,'2025_07_07_182132_create_jobs_table',1),(39,'2025_07_07_190000_create_features_table',1),(40,'2025_07_07_190100_create_feature_items_table',1),(41,'2025_07_18_145022_create_failed_jobs_table',1),(42,'2025_07_18_151531_create_notifications_table',1),(43,'2025_07_31_235337_add_discount_amount_to_orders_table',1),(44,'2025_08_01_082426_add_order_id_and_used_at_to_coupon_users_table',1),(45,'2025_08_01_171125_update_variant_storage_id',1),(46,'2025_08_01_173749_add_hex_code_to_colors_table',1),(47,'2025_08_01_201031_add_vip_level_to_points_table',1),(48,'2025_08_02_184622_update_coupons_max_discount_value_column',1),(49,'2025_08_02_230118_add_exchange_points_to_coupons_table',1),(50,'2025_08_02_235612_add_exchange_fields_to_coupons_table',1),(51,'2025_08_03_051624_create_services_table',1),(52,'2025_08_03_055216_add_is_received_to_orders_table',1),(53,'2025_08_03_195650_add_rating_to_comments_table',1),(54,'2025_08_03_212835_add_detailed_address_fields_to_users_table',1),(55,'2025_08_03_214707_add_avatar_to_users_table',1),(56,'2025_08_04_113649_add_email_verification_code_to_users_table',1),(57,'2025_08_04_234720_add_received_status_to_orders_table',1),(58,'2025_08_05_021659_add_is_temp_to_carts_table',1),(59,'2025_08_05_093602_create_attendance_table',1),(60,'2025_08_05_101632_add_points_claimed_to_attendance_table',1),(61,'2025_08_05_122126_add_soft_deletes_to_services_table',1),(62,'2025_08_06_115647_add_stock_validation_to_products_and_variants',1),(63,'2025_08_06_120000_remove_stock_validation_constraints',1),(64,'2025_08_16_015200_add_cancellation_reason_to_orders_table',1),(65,'2025_08_16_030417_remove_variant_name_from_product_variants_table',1),(66,'2025_08_16_031324_add_additional_fields_to_products_table',1),(67,'2025_08_16_052911_add_return_exchange_fields_to_orders_table',1),(68,'2025_08_16_054826_add_media_fields_to_orders_table',1),(69,'2025_08_16_182400_add_delivered_status_to_orders_table',1),(70,'2025_08_17_183855_add_order_id_to_reviews_table',1),(71,'2025_08_17_185203_add_points_value_to_orders_table',1),(72,'2025_08_17_190729_update_vip_levels_for_phone_sales',1),(73,'2025_08_17_191505_update_vip_levels_reduced_points',1),(74,'2025_08_18_121116_update_vip_levels_300m_threshold',1),(75,'2025_08_18_121929_fix_attendance_time_format',1),(76,'2025_08_18_122009_fix_attendance_time_format',1),(77,'2025_08_18_204228_create_chat_messages_table',1),(78,'2025_12_31_000000_add_stock_reserved_to_orders_table',1),(79,'2025_12_31_235959_create_stat_views',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_details` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `variant_id` bigint unsigned DEFAULT NULL,
  `quantity` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_order_id_foreign` (`order_id`),
  KEY `order_details_product_id_foreign` (`product_id`),
  KEY `order_details_variant_id_foreign` (`variant_id`),
  CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_details`
--

LOCK TABLES `order_details` WRITE;
/*!40000 ALTER TABLE `order_details` DISABLE KEYS */;
INSERT INTO `order_details` VALUES (1,1,4,23,1,29990000.00,29990000.00,'pending','Samsung Galaxy S24 Ultra - Xanh lá, ','2025-08-19 04:08:20','2025-08-19 04:08:20',NULL),(2,2,2,13,1,27990000.00,27990000.00,'pending','iPhone 15 Pro - Xanh rêu, ','2025-08-19 04:13:17','2025-08-19 04:13:17',NULL),(3,3,17,105,6,11990000.00,71940000.00,'pending','Realme 11 Pro+ - Đen, ','2025-08-19 05:52:12','2025-08-19 05:52:12',NULL);
/*!40000 ALTER TABLE `order_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code_order` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receiver_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `billing_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_ward` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `billing_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `points_earned` int NOT NULL DEFAULT '0',
  `points_used` int NOT NULL DEFAULT '0',
  `points_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `point_voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','processing','shipping','delivered','received','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `is_received` tinyint(1) NOT NULL DEFAULT '0',
  `return_status` enum('none','requested','approved','rejected','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `return_reason` text COLLATE utf8mb4_unicode_ci,
  `return_description` text COLLATE utf8mb4_unicode_ci,
  `return_requested_at` timestamp NULL DEFAULT NULL,
  `return_processed_at` timestamp NULL DEFAULT NULL,
  `return_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_amount` decimal(15,2) DEFAULT NULL,
  `admin_return_note` text COLLATE utf8mb4_unicode_ci,
  `stock_reserved` tinyint(1) NOT NULL DEFAULT '0',
  `return_video` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_order_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `return_product_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_id` bigint unsigned DEFAULT NULL,
  `discount_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_status` enum('pending','paid','processing','completed','failed','refunded','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cancellation_reason` text COLLATE utf8mb4_unicode_ci,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_user_id_foreign` (`user_id`),
  KEY `orders_coupon_id_foreign` (`coupon_id`),
  CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'WS17555764995902','Quản Trị Viên','Tỉnh Quảng Ninh','Huyện Vân Đồn','Xã Bản Sen','Hà Nội, Việt Nam','0123456789',NULL,30020000.00,0,0,0.00,NULL,'vnpay','pending',0,'none',NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,0.00,'pending',NULL,NULL,'2025-08-19 04:08:19','2025-08-19 04:08:20',NULL),(2,1,'WS17555767974055','Quản Trị Viên','Tỉnh Bắc Ninh','Thị xã Thuận Thành','Phường Xuân Lâm','Hà Nội, Việt Nam','0123456789',NULL,28020000.00,0,0,0.00,NULL,'vnpay','completed',1,'approved','Sản phẩm không đúng mô tả',NULL,'2025-08-19 04:26:42','2025-08-19 04:30:49','points',28020000.00,'Chị hay chờ đơn vị shipper đến nơi mik đang ở lấy hàng',0,'returns/videos/return_video_2_1755577602.mp4','returns/images/return_order_2_1755577602.png','returns/images/return_product_2_1755577602.png',NULL,0.00,'paid',NULL,NULL,'2025-08-19 04:13:17','2025-08-19 04:30:49',NULL),(3,10,'WS17555827329340','Cường Nguyễn','Tỉnh Bắc Kạn','Huyện Ngân Sơn','Xã Bằng Vân','Hà Nội, Việt Nam','0869555252',NULL,71970000.00,0,0,0.00,NULL,'cod','completed',1,'none',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,0.00,'paid',NULL,NULL,'2025-08-19 05:52:12','2025-08-19 05:55:15',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (38,'system.manage','Quản lý toàn bộ hệ thống','2025-08-19 03:02:15','2025-08-19 03:02:15'),(39,'content.manage','Quản lý sản phẩm, đơn hàng, danh mục','2025-08-19 03:02:15','2025-08-19 03:02:15'),(40,'info.view','Xem thông tin cơ bản','2025-08-19 03:02:15','2025-08-19 03:02:15'),(41,'user.view','Xem danh sách người dùng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(42,'user.edit','Chỉnh sửa người dùng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(43,'user.delete','Xóa người dùng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(44,'user.manage_roles','Quản lý vai trò người dùng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(45,'role.view','Xem danh sách vai trò','2025-08-19 03:15:30','2025-08-19 03:15:30'),(46,'role.create','Tạo mới vai trò','2025-08-19 03:15:30','2025-08-19 03:15:30'),(47,'role.edit','Chỉnh sửa vai trò','2025-08-19 03:15:30','2025-08-19 03:15:30'),(48,'role.delete','Xóa vai trò','2025-08-19 03:15:30','2025-08-19 03:15:30'),(49,'role.manage_permissions','Quản lý quyền của vai trò','2025-08-19 03:15:30','2025-08-19 03:15:30'),(50,'permission.view','Xem danh sách quyền','2025-08-19 03:15:30','2025-08-19 03:15:30'),(51,'permission.create','Tạo mới quyền','2025-08-19 03:15:30','2025-08-19 03:15:30'),(52,'permission.edit','Chỉnh sửa quyền','2025-08-19 03:15:30','2025-08-19 03:15:30'),(53,'permission.delete','Xóa quyền','2025-08-19 03:15:30','2025-08-19 03:15:30'),(54,'category.view','Xem danh sách danh mục','2025-08-19 03:15:30','2025-08-19 03:15:30'),(55,'category.create','Tạo mới danh mục','2025-08-19 03:15:30','2025-08-19 03:15:30'),(56,'category.edit','Chỉnh sửa danh mục','2025-08-19 03:15:30','2025-08-19 03:15:30'),(57,'category.delete','Xóa danh mục','2025-08-19 03:15:30','2025-08-19 03:15:30'),(58,'product.view','Xem danh sách sản phẩm','2025-08-19 03:15:30','2025-08-19 03:15:30'),(59,'product.create','Tạo mới sản phẩm','2025-08-19 03:15:30','2025-08-19 03:15:30'),(60,'product.edit','Chỉnh sửa sản phẩm','2025-08-19 03:15:30','2025-08-19 03:15:30'),(61,'product.delete','Xóa sản phẩm','2025-08-19 03:15:30','2025-08-19 03:15:30'),(62,'order.view','Xem danh sách đơn hàng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(63,'order.create','Tạo mới đơn hàng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(64,'order.edit','Chỉnh sửa đơn hàng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(65,'order.delete','Xóa đơn hàng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(66,'order.process','Xử lý đơn hàng','2025-08-19 03:15:30','2025-08-19 03:15:30'),(67,'review.view','Xem danh sách đánh giá','2025-08-19 03:15:30','2025-08-19 03:15:30'),(68,'review.moderate','Kiểm duyệt đánh giá','2025-08-19 03:15:30','2025-08-19 03:15:30'),(69,'review.delete','Xóa đánh giá','2025-08-19 03:15:30','2025-08-19 03:15:30'),(70,'dashboard.view','Xem dashboard admin','2025-08-19 03:15:30','2025-08-19 03:15:30'),(71,'report.view','Xem báo cáo','2025-08-19 03:15:30','2025-08-19 03:15:30'),(72,'report.export','Xuất báo cáo','2025-08-19 03:15:30','2025-08-19 03:15:30'),(73,'setting.view','Xem cài đặt hệ thống','2025-08-19 03:15:30','2025-08-19 03:15:30'),(74,'setting.edit','Chỉnh sửa cài đặt hệ thống','2025-08-19 03:15:30','2025-08-19 03:15:30');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_transactions`
--

DROP TABLE IF EXISTS `point_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_expired` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `point_transactions_user_id_type_index` (`user_id`,`type`),
  KEY `point_transactions_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `point_transactions_expiry_date_index` (`expiry_date`),
  CONSTRAINT `point_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_transactions`
--

LOCK TABLES `point_transactions` WRITE;
/*!40000 ALTER TABLE `point_transactions` DISABLE KEYS */;
INSERT INTO `point_transactions` VALUES (1,1,'earn',280200,'Tích điểm từ đơn hàng #WS17555767974055','order',2,'2026-08-19',0,'2025-08-19 04:16:51','2025-08-19 04:16:51'),(2,1,'earned',28020000,'Điểm hoàn từ yêu cầu đổi hoàn hàng đơn hàng #2',NULL,NULL,NULL,0,'2025-08-19 04:30:49','2025-08-19 04:30:49'),(3,1,'earn',100,'Điểm danh ngày 2025-08-19','attendance',125,'2026-08-19',0,'2025-08-19 04:31:12','2025-08-19 04:31:12'),(4,1,'use',-28300300,'Sử dụng 28300300 điểm để giảm giá đơn hàng','order_points',NULL,NULL,0,'2025-08-19 05:51:47','2025-08-19 05:51:47'),(5,10,'earn',719700,'Tích điểm từ đơn hàng #WS17555827329340','order',3,'2026-08-19',0,'2025-08-19 05:55:09','2025-08-19 05:55:09');
/*!40000 ALTER TABLE `point_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `point_vouchers`
--

DROP TABLE IF EXISTS `point_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `point_vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `points_required` int NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `discount_type` enum('percentage','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `min_order_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_usage` int DEFAULT NULL,
  `current_usage` int NOT NULL DEFAULT '0',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `point_vouchers`
--

LOCK TABLES `point_vouchers` WRITE;
/*!40000 ALTER TABLE `point_vouchers` DISABLE KEYS */;
INSERT INTO `point_vouchers` VALUES (1,'Giảm 50.000đ cho đơn hàng từ 500.000đ','Voucher giảm giá 50.000đ cho đơn hàng có giá trị từ 500.000đ trở lên',100,50000.00,'fixed',500000.00,1000,0,'2025-08-18','2026-02-18',1,'2025-08-18 13:56:36','2025-08-18 13:56:36'),(2,'Giảm 10% cho đơn hàng từ 1.000.000đ','Voucher giảm giá 10% cho đơn hàng có giá trị từ 1.000.000đ trở lên',200,10.00,'percentage',1000000.00,500,0,'2025-08-18','2026-02-18',1,'2025-08-18 13:56:36','2025-08-18 13:56:36'),(3,'Giảm 100.000đ cho đơn hàng từ 2.000.000đ','Voucher giảm giá 100.000đ cho đơn hàng có giá trị từ 2.000.000đ trở lên',300,100000.00,'fixed',2000000.00,200,0,'2025-08-18','2026-02-18',1,'2025-08-18 13:56:36','2025-08-18 13:56:36'),(4,'Giảm 15% cho đơn hàng từ 3.000.000đ','Voucher giảm giá 15% cho đơn hàng có giá trị từ 3.000.000đ trở lên',500,15.00,'percentage',3000000.00,100,0,'2025-08-18','2026-02-18',1,'2025-08-18 13:56:36','2025-08-18 13:56:36'),(5,'Giảm 200.000đ cho đơn hàng từ 5.000.000đ','Voucher giảm giá 200.000đ cho đơn hàng có giá trị từ 5.000.000đ trở lên',800,200000.00,'fixed',5000000.00,50,0,'2025-08-18','2026-02-18',1,'2025-08-18 13:56:36','2025-08-18 13:56:36');
/*!40000 ALTER TABLE `point_vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `points`
--

DROP TABLE IF EXISTS `points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `points` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total_points` int NOT NULL DEFAULT '0',
  `earned_points` int NOT NULL DEFAULT '0',
  `used_points` int NOT NULL DEFAULT '0',
  `expired_points` int NOT NULL DEFAULT '0',
  `vip_level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bronze',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `points_user_id_unique` (`user_id`),
  CONSTRAINT `points_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `points`
--

LOCK TABLES `points` WRITE;
/*!40000 ALTER TABLE `points` DISABLE KEYS */;
INSERT INTO `points` VALUES (1,1,0,280300,28300300,0,'Bronze','2025-08-19 04:16:51','2025-08-19 05:51:47'),(2,10,719700,719700,0,0,'Bronze','2025-08-19 05:55:09','2025-08-19 05:55:09');
/*!40000 ALTER TABLE `points` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `view` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_author_id_foreign` (`author_id`),
  CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,'Đánh giá camera của Xiaomi 14 Ultra','Những tính năng mới và cải tiến trong phiên bản Android 15. Ullam aut labore minima. Animi veritatis doloribus quibusdam nihil nam perferendis laboriosam. Pariatur natus sunt quos esse officiis quam molestiae.',NULL,'posts/WJRht6iiccYFIeJnGq2K0B63LnwZaXm3hxN2ALao.jpg','published','2025-08-19 23:18:00','2025-08-18 13:56:36','2025-08-19 06:29:19'),(8,1,'Cách bảo vệ điện thoại khỏi virus và malware','Đánh giá chi tiết về khả năng chụp ảnh của Xiaomi 14 Ultra. Quia fugiat dolorem et molestias. Corporis odit aut qui quibusdam omnis labore. Quia cum explicabo eos iste. Repudiandae cupiditate iste delectus esse explicabo fugiat provident.',NULL,'posts/l8PPCoQwuq0S7psyQusJf1rPXT3lpAa474QPIeFe.jpg','published','2025-08-19 08:56:00','2025-08-18 13:56:36','2025-08-19 06:29:25'),(11,6,'Đánh giá camera của Xiaomi 14 Ultra','Những tính năng mới và cải tiến trong phiên bản Android 15. Ullam aut labore minima. Animi veritatis doloribus quibusdam nihil nam perferendis laboriosam. Pariatur natus sunt quos esse officiis quam molestiae.',NULL,'posts/EaCysHTvKM8rbMD9gFRgF5Mv0ykflpem9fLlF6OE.jpg','published','2025-08-19 13:16:00','2025-08-19 06:16:39','2025-08-19 06:29:04');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_variants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `image_variant` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `promotion_price` decimal(15,2) DEFAULT NULL,
  `stock_quantity` int NOT NULL,
  `color_id` bigint unsigned DEFAULT NULL,
  `storage_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_variants_product_id_foreign` (`product_id`),
  KEY `product_variants_color_id_foreign` (`color_id`),
  KEY `product_variants_storage_id_foreign` (`storage_id`),
  CONSTRAINT `product_variants_color_id_foreign` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `product_variants_storage_id_foreign` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=162 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_variants`
--

LOCK TABLES `product_variants` WRITE;
/*!40000 ALTER TABLE `product_variants` DISABLE KEYS */;
INSERT INTO `product_variants` VALUES (1,1,'[\"product-variants\\/Fjaj8aIQMZGMlFuTfGd3LVDUHAia7Wt1zL0KST69.webp\"]',34990000.00,32990000.00,17,14,4,'2025-08-18 13:56:36','2025-08-19 05:20:42',NULL),(2,1,'[\"product-variants\\/71SPrAlm7ck0sb3GUV1k3Hh9qTdyAN8ht71x7cPl.webp\"]',38489000.00,36289000.00,11,14,5,'2025-08-18 13:56:36','2025-08-19 05:20:57',NULL),(3,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',45487000.00,42887000.00,10,14,7,'2025-08-18 13:56:36','2025-08-19 05:21:01','2025-08-19 05:21:01'),(4,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',34990000.00,32990000.00,20,25,4,'2025-08-18 13:56:36','2025-08-19 05:19:44','2025-08-19 05:19:44'),(5,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',38489000.00,36289000.00,11,25,5,'2025-08-18 13:56:36','2025-08-19 05:19:39','2025-08-19 05:19:39'),(6,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',45487000.00,42887000.00,17,25,7,'2025-08-18 13:56:36','2025-08-19 05:19:55','2025-08-19 05:19:55'),(7,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',34990000.00,32990000.00,15,31,4,'2025-08-18 13:56:36','2025-08-19 05:19:48','2025-08-19 05:19:48'),(8,1,'[\"iphone-15-pro-max.jpg\",\"iphone-15-pro-max-variant1.jpg\",\"iphone-15-pro-max-variant2.jpg\"]',38489000.00,36289000.00,14,31,5,'2025-08-18 13:56:36','2025-08-19 05:19:51','2025-08-19 05:19:51'),(9,1,'[\"product-variants\\/FADbHNsaMezqWQGOd2SwsD8ZrC7rCIaIIitwkSSF.webp\"]',45487000.00,42887000.00,17,31,7,'2025-08-18 13:56:36','2025-08-19 05:21:19',NULL),(10,2,'[\"product-variants\\/MwowDsHmgaETInkub0qXLpelnP55O7B3ysWhDy87.webp\"]',29990000.00,27990000.00,18,11,3,'2025-08-18 13:56:36','2025-08-19 05:25:15',NULL),(11,2,'[\"product-variants\\/VzODKEqKKTOVfsFeBjd3MycTYCsdN6bDqpZqCN3n.webp\"]',38987000.00,36387000.00,20,11,7,'2025-08-18 13:56:36','2025-08-19 05:25:25',NULL),(12,2,'[\"iphone-15-pro.jpg\",\"iphone-15-pro-variant1.jpg\",\"iphone-15-pro-variant2.jpg\"]',41986000.00,39186000.00,14,11,8,'2025-08-18 13:56:36','2025-08-19 05:25:29','2025-08-19 05:25:29'),(13,2,'[\"iphone-15-pro.jpg\",\"iphone-15-pro-variant1.jpg\",\"iphone-15-pro-variant2.jpg\"]',29990000.00,27990000.00,19,22,3,'2025-08-18 13:56:36','2025-08-19 05:21:43','2025-08-19 05:21:43'),(14,2,'[\"iphone-15-pro.jpg\",\"iphone-15-pro-variant1.jpg\",\"iphone-15-pro-variant2.jpg\"]',38987000.00,36387000.00,18,22,7,'2025-08-18 13:56:36','2025-08-19 05:21:50','2025-08-19 05:21:50'),(15,2,'[\"iphone-15-pro.jpg\",\"iphone-15-pro-variant1.jpg\",\"iphone-15-pro-variant2.jpg\"]',41986000.00,39186000.00,5,22,8,'2025-08-18 13:56:36','2025-08-19 05:21:47','2025-08-19 05:21:47'),(16,2,'[\"product-variants\\/sIzpEE8X8WJsv6WlXETeNQi8gdmLq7iu18CQNEpS.webp\"]',29990000.00,27990000.00,16,31,3,'2025-08-18 13:56:36','2025-08-19 05:25:51',NULL),(17,2,'[\"product-variants\\/t1O99odrvqijjS7IExe6FxNskJz0kfKC6gDsobCW.webp\"]',38987000.00,36387000.00,11,31,7,'2025-08-18 13:56:36','2025-08-19 05:25:59',NULL),(18,2,'[\"product-variants\\/NWl5zvH7pAJ6PC5UvkVsy3ewniMbbWYX6cntShfl.webp\"]',41986000.00,39186000.00,13,2,7,'2025-08-18 13:56:36','2025-08-19 05:26:33',NULL),(19,3,'[\"product-variants\\/IzEIQhZkl90vHLpQ2hs61krkVV8W3WFNZOvvqs9S.webp\"]',29988000.00,27588000.00,11,6,6,'2025-08-18 13:56:36','2025-08-19 05:27:32',NULL),(20,3,'[\"product-variants\\/C2zvKPsHRikIf3vsGk43JqjMhgqTQ2YVdKZDdLxJ.webp\"]',34986000.00,32186000.00,14,6,8,'2025-08-18 13:56:36','2025-08-19 05:27:42',NULL),(21,3,'[\"product-variants\\/uv1x1vJUoNbgIbJOzDHULpzj49Xmyf9JIAuGfL0M.webp\"]',29988000.00,27588000.00,6,5,6,'2025-08-18 13:56:36','2025-08-19 05:28:26',NULL),(22,3,'[\"product-variants\\/vj04yhY5ULKaSVe1CzVuANvDClq6KOL8TdvXMCd7.webp\"]',34986000.00,32186000.00,19,9,8,'2025-08-18 13:56:36','2025-08-19 05:29:02',NULL),(23,4,'[\"product-variants\\/SNwPMvTvuhgIWnNR2RHD3D5P8Sz1SGhwLvYSTRLk.webp\"]',31990000.00,29990000.00,17,1,5,'2025-08-18 13:56:36','2025-08-19 05:30:22',NULL),(24,4,'[\"product-variants\\/rQbJ7hRgjUUwnAGFuLfAT9rLle131rJRWik2TDms.webp\"]',35189000.00,32989000.00,11,3,5,'2025-08-18 13:56:36','2025-08-19 05:30:49',NULL),(25,4,'[\"product-variants\\/0B2QiWuZcezWun18uxkW0ytrG04YaPV3ypgYRrRp.webp\"]',31990000.00,29990000.00,15,5,5,'2025-08-18 13:56:36','2025-08-19 05:31:21',NULL),(26,4,'[\"product-variants\\/Q8JKmGmEjzpL7zeTn2XlLx9qiKggSU2tcKisNSL0.webp\"]',35189000.00,32989000.00,20,10,5,'2025-08-18 13:56:36','2025-08-19 05:31:45',NULL),(27,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',26990000.00,24990000.00,6,19,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(28,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',26990000.00,24990000.00,12,19,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(29,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',32388000.00,29988000.00,6,19,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(30,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',26990000.00,24990000.00,6,40,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(31,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',26990000.00,24990000.00,20,40,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(32,5,'[\"galaxy-s24-plus.jpg\",\"galaxy-s24-plus-variant1.jpg\",\"galaxy-s24-plus-variant2.jpg\"]',32388000.00,29988000.00,13,40,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(33,6,'[\"product-variants\\/Ga4fNVnhkgOH5hblvl47WFe2wD13PEB7DFu817YG.webp\"]',12990000.00,11990000.00,11,8,1,'2025-08-18 13:56:36','2025-08-19 05:34:37',NULL),(34,6,'[\"product-variants\\/H7DpYRxB84pK6EFA6GN2nWknXrkcIAN8uLyy6c7W.webp\"]',15588000.00,14388000.00,17,25,6,'2025-08-18 13:56:36','2025-08-19 05:35:09',NULL),(35,6,'[\"galaxy-a55.jpg\",\"galaxy-a55-variant1.jpg\",\"galaxy-a55-variant2.jpg\"]',12990000.00,11990000.00,20,9,1,'2025-08-18 13:56:36','2025-08-19 05:35:12','2025-08-19 05:35:12'),(36,6,'[\"galaxy-a55.jpg\",\"galaxy-a55-variant1.jpg\",\"galaxy-a55-variant2.jpg\"]',15588000.00,14388000.00,5,9,6,'2025-08-18 13:56:36','2025-08-19 05:35:17','2025-08-19 05:35:17'),(37,6,'[\"product-variants\\/ToUFqwIFjwHB49VtrS9z5P2XPmxE5E3RXK80peEV.webp\"]',12990000.00,11990000.00,11,1,1,'2025-08-18 13:56:36','2025-08-19 05:35:46',NULL),(38,6,'[\"galaxy-a55.jpg\",\"galaxy-a55-variant1.jpg\",\"galaxy-a55-variant2.jpg\"]',15588000.00,14388000.00,19,12,6,'2025-08-18 13:56:36','2025-08-19 05:36:10','2025-08-19 05:36:10'),(39,7,'[\"product-variants\\/6tmxFtg5bmxjebLtrbFtrTV3OjKqrgQT1d7U1y2L.webp\"]',24990000.00,22990000.00,12,1,5,'2025-08-18 13:56:36','2025-08-19 05:36:58',NULL),(40,7,'[\"product-variants\\/5Tqod0NzhpBoKFnApQAsuDnC6HEgTV1SB8R8ByWQ.webp\"]',27489000.00,25289000.00,6,2,5,'2025-08-18 13:56:36','2025-08-19 05:37:29',NULL),(41,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',34986000.00,32186000.00,20,3,8,'2025-08-18 13:56:36','2025-08-19 05:37:32','2025-08-19 05:37:32'),(42,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',24990000.00,22990000.00,18,21,3,'2025-08-18 13:56:36','2025-08-19 05:37:35','2025-08-19 05:37:35'),(43,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',27489000.00,25289000.00,16,21,5,'2025-08-18 13:56:36','2025-08-19 05:37:37','2025-08-19 05:37:37'),(44,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',34986000.00,32186000.00,13,21,8,'2025-08-18 13:56:36','2025-08-19 05:37:39','2025-08-19 05:37:39'),(45,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',24990000.00,22990000.00,11,26,3,'2025-08-18 13:56:36','2025-08-19 05:37:41','2025-08-19 05:37:41'),(46,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',27489000.00,25289000.00,12,26,5,'2025-08-18 13:56:36','2025-08-19 05:37:44','2025-08-19 05:37:44'),(47,7,'[\"xiaomi-14-ultra.jpg\",\"xiaomi-14-ultra-variant1.jpg\",\"xiaomi-14-ultra-variant2.jpg\"]',34986000.00,32186000.00,18,26,8,'2025-08-18 13:56:36','2025-08-19 05:37:46','2025-08-19 05:37:46'),(48,8,'[\"product-variants\\/IGBJ4IL05iICoXXRZwEAIwBibJGpdetIjfn9FFzc.webp\"]',19990000.00,17990000.00,17,1,5,'2025-08-18 13:56:36','2025-08-19 05:38:30',NULL),(49,8,'[\"product-variants\\/83bSXHHYjs1I6NIMqjbHQN6cUulYzHDMU9HHbPO5.webp\"]',23988000.00,21588000.00,13,12,6,'2025-08-18 13:56:36','2025-08-19 05:38:52',NULL),(50,8,'[\"product-variants\\/OWrZdjXj17I917mba9ri0A3jXgfqTAcAr0DJIHIs.webp\"]',27986000.00,25186000.00,18,9,8,'2025-08-18 13:56:36','2025-08-19 05:39:28',NULL),(51,8,'[\"xiaomi-14.jpg\",\"xiaomi-14-variant1.jpg\",\"xiaomi-14-variant2.jpg\"]',19990000.00,17990000.00,6,22,2,'2025-08-18 13:56:36','2025-08-19 05:38:56','2025-08-19 05:38:56'),(52,8,'[\"xiaomi-14.jpg\",\"xiaomi-14-variant1.jpg\",\"xiaomi-14-variant2.jpg\"]',23988000.00,21588000.00,14,22,6,'2025-08-18 13:56:36','2025-08-19 05:38:58','2025-08-19 05:38:58'),(53,8,'[\"xiaomi-14.jpg\",\"xiaomi-14-variant1.jpg\",\"xiaomi-14-variant2.jpg\"]',27986000.00,25186000.00,20,22,8,'2025-08-18 13:56:36','2025-08-19 05:39:00','2025-08-19 05:39:00'),(54,9,'[\"product-variants\\/T5HDyBpGKw1aU8peKeIeLqo95Q51c4A8QaVkGcZn.webp\"]',8990000.00,7990000.00,11,1,1,'2025-08-18 13:56:36','2025-08-19 05:40:27',NULL),(55,9,'[\"product-variants\\/d2H95dKaIDVHYurwjFenYDqmmHMi0rZuCOVylSRU.webp\"]',10788000.00,9588000.00,7,9,6,'2025-08-18 13:56:36','2025-08-19 05:40:59',NULL),(56,9,'[\"redmi-note-13-pro-plus.jpg\",\"redmi-note-13-pro-plus-variant1.jpg\",\"redmi-note-13-pro-plus-variant2.jpg\"]',8990000.00,7990000.00,15,33,1,'2025-08-18 13:56:36','2025-08-19 05:40:33','2025-08-19 05:40:33'),(57,9,'[\"redmi-note-13-pro-plus.jpg\",\"redmi-note-13-pro-plus-variant1.jpg\",\"redmi-note-13-pro-plus-variant2.jpg\"]',10788000.00,9588000.00,16,33,6,'2025-08-18 13:56:36','2025-08-19 05:40:31','2025-08-19 05:40:31'),(58,9,'[\"redmi-note-13-pro-plus.jpg\",\"redmi-note-13-pro-plus-variant1.jpg\",\"redmi-note-13-pro-plus-variant2.jpg\"]',8990000.00,7990000.00,7,38,1,'2025-08-18 13:56:36','2025-08-19 05:40:35','2025-08-19 05:40:35'),(59,9,'[\"product-variants\\/MORPI5URsU43Qdk00HfVwH6XMIcvQoxuWcv4o0ri.webp\"]',10788000.00,9588000.00,10,10,6,'2025-08-18 13:56:36','2025-08-19 05:41:20',NULL),(60,10,'[\"product-variants\\/WY3OznZ145XEHKMaR64bNhbKpRnq1raapIOSZVS7.webp\"]',22990000.00,20990000.00,13,4,1,'2025-08-18 13:56:36','2025-08-19 05:42:16',NULL),(61,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',22990000.00,20990000.00,10,4,4,'2025-08-18 13:56:36','2025-08-19 05:42:19','2025-08-19 05:42:19'),(62,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',25289000.00,23089000.00,16,4,5,'2025-08-18 13:56:36','2025-08-19 05:42:21','2025-08-19 05:42:21'),(63,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',22990000.00,20990000.00,13,24,1,'2025-08-18 13:56:36','2025-08-19 05:42:23','2025-08-19 05:42:23'),(64,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',22990000.00,20990000.00,11,24,4,'2025-08-18 13:56:36','2025-08-19 05:42:25','2025-08-19 05:42:25'),(65,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',25289000.00,23089000.00,13,24,5,'2025-08-18 13:56:36','2025-08-19 05:42:27','2025-08-19 05:42:27'),(66,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',22990000.00,20990000.00,9,33,1,'2025-08-18 13:56:36','2025-08-19 05:42:29','2025-08-19 05:42:29'),(67,10,'[\"product-variants\\/5NrxgdHnlDOZJtmCLoKopmLiXgm7Nme9kHs75TcX.webp\"]',22990000.00,20990000.00,6,27,4,'2025-08-18 13:56:36','2025-08-19 05:42:58',NULL),(68,10,'[\"oppo-find-x7-ultra.jpg\",\"oppo-find-x7-ultra-variant1.jpg\",\"oppo-find-x7-ultra-variant2.jpg\"]',25289000.00,23089000.00,16,33,5,'2025-08-18 13:56:36','2025-08-19 05:43:01','2025-08-19 05:43:01'),(69,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',18990000.00,16990000.00,17,9,1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(70,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',18990000.00,16990000.00,9,9,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(71,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',20889000.00,18689000.00,8,9,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(72,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',18990000.00,16990000.00,20,15,1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(73,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',18990000.00,16990000.00,19,15,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(74,11,'[\"oppo-find-x7.jpg\",\"oppo-find-x7-variant1.jpg\",\"oppo-find-x7-variant2.jpg\"]',20889000.00,18689000.00,13,15,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(75,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,13,1,1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(76,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,5,1,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(77,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',13986000.00,12586000.00,17,1,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(78,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,19,9,1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(79,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,20,9,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(80,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',13986000.00,12586000.00,11,9,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(81,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,16,15,1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(82,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',9990000.00,8990000.00,10,15,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(83,12,'[\"oppo-reno-11.jpg\",\"oppo-reno-11-variant1.jpg\",\"oppo-reno-11-variant2.jpg\"]',13986000.00,12586000.00,19,15,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(84,13,'[\"product-variants\\/d8ncKkpuVE9BL5SdIacU4oqJ5fivflGO7YPreROY.jpg\"]',21990000.00,19990000.00,7,15,5,'2025-08-18 13:56:36','2025-08-19 05:45:53',NULL),(85,13,'[\"product-variants\\/B4zR9EmcZiMWLdqJtFewdd4NU7RpmQ99ogPC3zcm.jpg\"]',24189000.00,21989000.00,5,28,5,'2025-08-18 13:56:36','2025-08-19 05:46:21',NULL),(86,13,'[\"product-variants\\/wwcmSROitavOEoG6X76oFbzHzjZAgghhJG02Fzi2.jpg\"]',28587000.00,25987000.00,8,35,7,'2025-08-18 13:56:36','2025-08-19 05:46:45',NULL),(87,13,'[\"product-variants\\/qmyvTHjuWU9ZWfvHSLzpiMlySfhWM8oo2C80dvaP.webp\"]',21990000.00,19990000.00,18,37,3,'2025-08-18 13:56:36','2025-08-19 05:47:07',NULL),(88,13,'[\"vivo-x100-pro.jpg\",\"vivo-x100-pro-variant1.jpg\",\"vivo-x100-pro-variant2.jpg\"]',24189000.00,21989000.00,20,37,5,'2025-08-18 13:56:36','2025-08-19 05:45:19','2025-08-19 05:45:19'),(89,13,'[\"vivo-x100-pro.jpg\",\"vivo-x100-pro-variant1.jpg\",\"vivo-x100-pro-variant2.jpg\"]',28587000.00,25987000.00,10,37,7,'2025-08-18 13:56:36','2025-08-19 05:45:16','2025-08-19 05:45:16'),(90,14,'[\"vivo-x100.jpg\",\"vivo-x100-variant1.jpg\",\"vivo-x100-variant2.jpg\"]',23387000.00,20787000.00,15,10,7,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(91,14,'[\"vivo-x100.jpg\",\"vivo-x100-variant1.jpg\",\"vivo-x100-variant2.jpg\"]',25186000.00,22386000.00,17,10,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(92,14,'[\"vivo-x100.jpg\",\"vivo-x100-variant1.jpg\",\"vivo-x100-variant2.jpg\"]',23387000.00,20787000.00,6,20,7,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(93,14,'[\"vivo-x100.jpg\",\"vivo-x100-variant1.jpg\",\"vivo-x100-variant2.jpg\"]',25186000.00,22386000.00,16,20,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(94,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',14289000.00,13189000.00,16,17,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(95,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',15588000.00,14388000.00,13,17,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(96,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',14289000.00,13189000.00,8,39,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(97,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',15588000.00,14388000.00,17,39,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(98,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',14289000.00,13189000.00,18,41,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(99,15,'[\"vivo-v29.jpg\",\"vivo-v29-variant1.jpg\",\"vivo-v29-variant2.jpg\"]',15588000.00,14388000.00,11,41,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(100,16,'[\"product-variants\\/h9lwoSMMFU4n97egDWeiSJ22p3AxB3NMsbtSeGCu.jpg\"]',8990000.00,7990000.00,15,11,1,'2025-08-18 13:56:36','2025-08-19 05:04:18',NULL),(101,16,'[\"product-variants\\/q6XVxzFZwH7LsqoaCxo1b5WgUvbaY7bz13dfbQT7.jpg\"]',11687000.00,10387000.00,20,11,7,'2025-08-18 13:56:36','2025-08-19 05:04:27',NULL),(102,16,'[\"product-variants\\/PgPb1quSdWz479nozYjrmE1qNAE05ZXStX2Y47uR.jpg\"]',8990000.00,7990000.00,14,28,1,'2025-08-18 13:56:36','2025-08-19 05:04:48',NULL),(103,16,'[\"product-variants\\/NeOWf5m0hYJHtosd5NdXLRgXGTKm2iihxfERWYye.jpg\"]',11687000.00,10387000.00,12,28,7,'2025-08-18 13:56:36','2025-08-19 05:04:58',NULL),(104,17,'[\"product-variants\\/Cb6Ma9R3vfq0I84a2K3R6QsEcfihEFWkORwoH3db.jpg\"]',12990000.00,11990000.00,19,1,1,'2025-08-18 13:56:36','2025-08-19 05:01:41',NULL),(105,17,'[\"product-variants\\/aR9aB8HgyEVmR9USC4Ejeez7Wpq8q80hnMq5uahj.jpg\"]',12990000.00,11990000.00,0,1,4,'2025-08-18 13:56:36','2025-08-19 05:52:12',NULL),(106,17,'[\"realme-11-pro-plus.jpg\",\"realme-11-pro-plus-variant1.jpg\",\"realme-11-pro-plus-variant2.jpg\"]',16887000.00,15587000.00,11,1,7,'2025-08-18 13:56:36','2025-08-19 05:01:56','2025-08-19 05:01:56'),(107,17,'[\"product-variants\\/DSlFrIo97kC31SG0KRAHcqHYwECXxBugIzZPtPmy.jpg\"]',12990000.00,11990000.00,10,32,1,'2025-08-18 13:56:36','2025-08-19 05:02:13',NULL),(108,17,'[\"product-variants\\/HEe0ZR2sj5dBa2jKXYuylGZD7CCDOQNEfYbonyEl.jpg\"]',12990000.00,11990000.00,8,32,4,'2025-08-18 13:56:36','2025-08-19 05:02:22',NULL),(109,17,'[\"realme-11-pro-plus.jpg\",\"realme-11-pro-plus-variant1.jpg\",\"realme-11-pro-plus-variant2.jpg\"]',16887000.00,15587000.00,15,32,7,'2025-08-18 13:56:36','2025-08-19 05:02:26','2025-08-19 05:02:26'),(110,17,'[\"realme-11-pro-plus.jpg\",\"realme-11-pro-plus-variant1.jpg\",\"realme-11-pro-plus-variant2.jpg\"]',12990000.00,11990000.00,19,41,1,'2025-08-18 13:56:36','2025-08-19 05:02:47','2025-08-19 05:02:47'),(111,17,'[\"realme-11-pro-plus.jpg\",\"realme-11-pro-plus-variant1.jpg\",\"realme-11-pro-plus-variant2.jpg\"]',12990000.00,11990000.00,11,41,4,'2025-08-18 13:56:36','2025-08-19 05:02:44','2025-08-19 05:02:44'),(112,17,'[\"realme-11-pro-plus.jpg\",\"realme-11-pro-plus-variant1.jpg\",\"realme-11-pro-plus-variant2.jpg\"]',16887000.00,15587000.00,10,41,7,'2025-08-18 13:56:36','2025-08-19 05:02:49','2025-08-19 05:02:49'),(113,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',19990000.00,17990000.00,12,2,2,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(114,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',27986000.00,25186000.00,19,2,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(115,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',19990000.00,17990000.00,20,12,2,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(116,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',27986000.00,25186000.00,7,12,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(117,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',19990000.00,17990000.00,6,16,2,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(118,18,'[\"oneplus-12.jpg\",\"oneplus-12-variant1.jpg\",\"oneplus-12-variant2.jpg\"]',27986000.00,25186000.00,10,16,8,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(119,19,'[\"oneplus-12r.jpg\",\"oneplus-12r-variant1.jpg\",\"oneplus-12r-variant2.jpg\"]',15990000.00,13990000.00,10,10,1,'2025-08-18 13:56:36','2025-08-19 04:57:43','2025-08-19 04:57:43'),(120,19,'[\"oneplus-12r.jpg\",\"oneplus-12r-variant1.jpg\",\"oneplus-12r-variant2.jpg\"]',15990000.00,13990000.00,12,10,2,'2025-08-18 13:56:36','2025-08-19 04:57:48','2025-08-19 04:57:48'),(121,19,'[\"oneplus-12r.jpg\",\"oneplus-12r-variant1.jpg\",\"oneplus-12r-variant2.jpg\"]',22386000.00,19586000.00,17,10,8,'2025-08-18 13:56:36','2025-08-19 04:57:51','2025-08-19 04:57:51'),(122,19,'[\"product-variants\\/9qKDfbsi2HEGRt2TIVAFN7iaWw7ruileuWqbx1iw.jpg\"]',15990000.00,13990000.00,15,22,1,'2025-08-18 13:56:36','2025-08-19 04:58:11',NULL),(123,19,'[\"product-variants\\/6K5egCihKDPgTDtsZuT4qbD6MDbRqaqWZeKgxA7N.jpg\"]',15990000.00,13990000.00,8,22,2,'2025-08-18 13:56:36','2025-08-19 04:58:19',NULL),(124,19,'[\"oneplus-12r.jpg\",\"oneplus-12r-variant1.jpg\",\"oneplus-12r-variant2.jpg\"]',22386000.00,19586000.00,12,22,8,'2025-08-18 13:56:36','2025-08-19 04:58:24','2025-08-19 04:58:24'),(125,19,'[\"product-variants\\/QA4xxH54e3mPWXAnUMYK8UETLrMJgVfIUgQw4D1F.jpg\"]',15990000.00,13990000.00,17,40,1,'2025-08-18 13:56:36','2025-08-19 04:58:55',NULL),(126,19,'[\"product-variants\\/vDMzkrXACLM2u0YT1DI7BlIsOcrl6wQYLksfN0J0.jpg\"]',15990000.00,13990000.00,9,40,2,'2025-08-18 13:56:36','2025-08-19 04:59:04',NULL),(127,19,'[\"oneplus-12r.jpg\",\"oneplus-12r-variant1.jpg\",\"oneplus-12r-variant2.jpg\"]',22386000.00,19586000.00,15,40,8,'2025-08-18 13:56:36','2025-08-19 04:58:47','2025-08-19 04:58:47'),(128,20,'[\"product-variants\\/m29tufDwUaUsHfmzLynRPXh0F2v99IK426MM4QTv.jpg\"]',24990000.00,22990000.00,20,9,3,'2025-08-18 13:56:36','2025-08-19 04:54:40',NULL),(129,20,'[\"product-variants\\/KK0NKa7GGP78XrFHSEQehy6l2Ye72JX7Uzwj272L.jpg\"]',32487000.00,29887000.00,11,9,7,'2025-08-18 13:56:36','2025-08-19 04:54:49',NULL),(130,20,'[\"product-variants\\/ROwtNgAZcwJnEfvKXwt0TR6Gl4qhIzHstDusHnZz.jpg\"]',34986000.00,32186000.00,11,9,8,'2025-08-18 13:56:36','2025-08-19 04:55:00',NULL),(131,20,'[\"product-variants\\/MBWbXiFWBGxIQE8bBa7dBemQllO9LA7LfLdtThvC.jpg\"]',24990000.00,22990000.00,14,29,3,'2025-08-18 13:56:36','2025-08-19 04:55:35',NULL),(132,20,'[\"product-variants\\/Y7PJOWXduVv4HIlsDDuLz5gvBPHYesK1yDcwSiz5.jpg\"]',32487000.00,29887000.00,15,29,7,'2025-08-18 13:56:36','2025-08-19 04:55:44',NULL),(133,20,'[\"product-variants\\/RTcEKUiBkyYpOHrdXQti7is8ZfTTAhhOvo45Kasf.jpg\"]',34986000.00,32186000.00,11,29,8,'2025-08-18 13:56:36','2025-08-19 04:55:53',NULL),(134,20,'[\"product-variants\\/wWbiMsUecW6VEi4sjju6xkUzOHrpwQWg9Ck08TQR.jpg\"]',24990000.00,22990000.00,14,37,3,'2025-08-18 13:56:36','2025-08-19 04:56:14',NULL),(135,20,'[\"product-variants\\/i4qXI8N3PxmGU952F0rEVA15Gx1CVZVgMUu4Nqu8.jpg\"]',32487000.00,29887000.00,15,37,7,'2025-08-18 13:56:36','2025-08-19 04:56:24',NULL),(136,20,'[\"product-variants\\/A5OYKj9sa3J3G6oGSGGiXoP7xxkkzuPORXWmfs0W.jpg\"]',34986000.00,32186000.00,6,37,8,'2025-08-18 13:56:36','2025-08-19 04:56:32',NULL),(137,21,'[\"product-variants\\/56hBkRshG3E7bq9109l3wlC1ufzdGPvP652b64xT.jpg\"]',12990000.00,11990000.00,9,10,3,'2025-08-18 13:56:36','2025-08-19 04:48:56',NULL),(138,21,'[\"product-variants\\/lnIxwHmv8n6YxWV6rdAwN7Qcnm2O67FFSomIWIB7.jpg\"]',15588000.00,14388000.00,20,10,6,'2025-08-18 13:56:36','2025-08-19 04:49:09',NULL),(139,21,'[\"product-variants\\/6xQtAOdRqAGeWEnKu4c70MHsdEvOLWaWaNeopajS.jpg\"]',12990000.00,11990000.00,15,32,3,'2025-08-18 13:56:36','2025-08-19 04:49:26',NULL),(140,21,'[\"product-variants\\/UFpLh1NjCDEz9om0rW6muxUrgDf2h7QNqvDbV3r0.jpg\"]',15588000.00,14388000.00,15,32,6,'2025-08-18 13:56:36','2025-08-19 04:49:36',NULL),(141,21,'[\"product-variants\\/1AY8aRwgNg8k3Kl7Xgs7ArQOzIieRED9pyW7E8WJ.jpg\"]',12990000.00,11990000.00,7,41,3,'2025-08-18 13:56:36','2025-08-19 04:49:57',NULL),(142,21,'[\"product-variants\\/49TLoAMdgY9ccgAafM9iETNCQqfYTLtnZCRRCcsf.jpg\"]',15588000.00,14388000.00,15,41,6,'2025-08-18 13:56:36','2025-08-19 04:50:04',NULL),(143,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',5990000.00,4990000.00,9,5,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(144,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',6589000.00,5489000.00,19,5,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(145,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',7188000.00,5988000.00,5,5,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(146,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',5990000.00,4990000.00,14,34,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(147,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',6589000.00,5489000.00,12,34,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(148,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',7188000.00,5988000.00,8,34,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(149,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',5990000.00,4990000.00,9,37,3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(150,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',6589000.00,5489000.00,10,37,5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(151,22,'[\"nokia-g60.jpg\",\"nokia-g60-variant1.jpg\",\"nokia-g60-variant2.jpg\"]',7188000.00,5988000.00,8,37,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(152,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',9990000.00,8990000.00,9,18,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(153,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',11988000.00,10788000.00,13,18,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(154,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',9990000.00,8990000.00,12,19,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(155,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',11988000.00,10788000.00,9,19,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(156,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',9990000.00,8990000.00,10,23,4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(157,23,'[\"motorola-edge-40.jpg\",\"motorola-edge-40-variant1.jpg\",\"motorola-edge-40-variant2.jpg\"]',11988000.00,10788000.00,18,23,6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(158,24,'[\"product-variants\\/MnP4c4eJkZquMhYCO4v8OhPpFOCx31SIRPdAY6zx.webp\"]',22990000.00,20990000.00,16,3,2,'2025-08-18 13:56:36','2025-08-19 04:43:00',NULL),(159,24,'[\"product-variants\\/PPYQ6YYfocuYgFrGWKk898Wr1AAFuy45EMIg6RKB.webp\"]',27588000.00,25188000.00,19,3,6,'2025-08-18 13:56:36','2025-08-19 04:43:09',NULL),(160,24,'[\"product-variants\\/Za5lrJpd5ozfT9c8hShAvtsn6MB3NcsIYQycjV4q.webp\"]',22990000.00,20990000.00,19,25,2,'2025-08-18 13:56:36','2025-08-19 04:43:20',NULL),(161,24,'[\"product-variants\\/gFX7hidadYzS2jpL1ErXGdWVn6tfMRoqshPlxZjx.webp\"]',27588000.00,25188000.00,10,25,6,'2025-08-18 13:56:36','2025-08-19 04:43:29',NULL);
/*!40000 ALTER TABLE `product_variants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `promotion_price` decimal(15,2) DEFAULT NULL,
  `compare_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá so sánh (giá gốc)',
  `description` text COLLATE utf8mb4_unicode_ci,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thương hiệu sản phẩm',
  `model` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Model sản phẩm',
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã SKU',
  `weight` decimal(8,2) DEFAULT NULL COMMENT 'Trọng lượng (kg)',
  `length` decimal(8,1) DEFAULT NULL COMMENT 'Chiều dài (cm)',
  `width` decimal(8,1) DEFAULT NULL COMMENT 'Chiều rộng (cm)',
  `height` decimal(8,1) DEFAULT NULL COMMENT 'Chiều cao (cm)',
  `warranty` int DEFAULT NULL COMMENT 'Thời gian bảo hành (tháng)',
  `origin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Xuất xứ sản phẩm',
  `meta_keywords` text COLLATE utf8mb4_unicode_ci COMMENT 'Từ khóa SEO',
  `meta_description` text COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả SEO',
  `category_id` bigint unsigned NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `view` int DEFAULT NULL,
  `stock_quantity` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'iPhone 15 Pro Max','products/hd5gqZVMzAT7TYonVQYN32HGLI0Sc7EbuwAF1RyV.jpg',34990000.00,32990000.00,41988000.00,'iPhone 15 Pro Max với chip A17 Pro, camera 48MP, màn hình 6.7 inch Super Retina XDR OLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,1251,50,'2025-08-18 13:56:36','2025-08-19 05:59:20',NULL),(2,'iPhone 15 Pro','products/1IS42Pa8Mzjg8kqPH6uHNIWAU9oTs76Tj67eGbOo.webp',29990000.00,27990000.00,35988000.00,'iPhone 15 Pro với chip A17 Pro, camera 48MP, màn hình 6.1 inch Super Retina XDR OLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,985,45,'2025-08-18 13:56:36','2025-08-19 06:07:57',NULL),(3,'iPhone 15','products/t2gspnFbG0D24GfTcfoVt5K1CGg39xjhoOaHKHrU.webp',24990000.00,22990000.00,29988000.00,'iPhone 15 với chip A16 Bionic, camera 48MP, màn hình 6.1 inch Super Retina XDR OLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,1,850,60,'2025-08-18 13:56:36','2025-08-19 05:06:21',NULL),(4,'Samsung Galaxy S24 Ultra','products/sMHvvAb09coxcL2zOXjziJSkMPBJEWWaKvpXtOen.webp',31990000.00,29990000.00,38388000.00,'Galaxy S24 Ultra với chip Snapdragon 8 Gen 3, camera 200MP, màn hình 6.8 inch Dynamic AMOLED 2X',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,1,1101,40,'2025-08-18 13:56:36','2025-08-19 05:15:00',NULL),(5,'Samsung Galaxy S24+','products/wVK8nrIEn9jsZg2HY1ZIm1uWMZ2RCUJrZLKrs36D.jpg',26990000.00,24990000.00,32388000.00,'Galaxy S24+ với chip Snapdragon 8 Gen 3, camera 50MP, màn hình 6.7 inch Dynamic AMOLED 2X',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,1,920,55,'2025-08-18 13:56:36','2025-08-19 05:33:49','2025-08-19 05:33:49'),(6,'Samsung Galaxy A55','products/1tlpzZJsYBVp5hZylD83wcpWBR6dnYLSrAHzVMNd.jpg',12990000.00,11990000.00,15588000.00,'Galaxy A55 với chip Exynos 1480, camera 50MP, màn hình 6.6 inch Super AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,2,1,750,70,'2025-08-18 13:56:36','2025-08-19 05:13:08',NULL),(7,'Xiaomi 14 Ultra','products/aGTCqbwfe4kV3XtH2ayl9p7P1twecLrkOYfeteiM.jpg',24990000.00,22990000.00,29988000.00,'Xiaomi 14 Ultra với chip Snapdragon 8 Gen 3, camera 50MP Leica, màn hình 6.73 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,1,680,35,'2025-08-18 13:56:36','2025-08-19 05:12:46',NULL),(8,'Xiaomi 14','products/8JDXCJTGMpMWSIAxUjdVzQlME2twdBUJ9bHKrtJ6.jpg',19990000.00,17990000.00,23988000.00,'Xiaomi 14 với chip Snapdragon 8 Gen 3, camera 50MP Leica, màn hình 6.36 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,1,590,50,'2025-08-18 13:56:36','2025-08-19 05:12:19',NULL),(9,'Xiaomi Redmi Note 13 Pro+','products/bqmcPuprOYzlinzAYugzGnyUVbeOAiX24UVruwsy.jpg',8990000.00,7990000.00,10788000.00,'Redmi Note 13 Pro+ với chip Dimensity 7200 Ultra, camera 200MP, màn hình 6.67 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,3,1,820,80,'2025-08-18 13:56:36','2025-08-19 05:11:52',NULL),(10,'OPPO Find X7 Ultra','products/vGUNWSwLBwTytPswLZC0aeji3RGRw5YNZ9OtaQfa.jpg',22990000.00,20990000.00,27588000.00,'OPPO Find X7 Ultra với chip Dimensity 9300, camera 50MP Hasselblad, màn hình 6.82 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,1,450,30,'2025-08-18 13:56:36','2025-08-19 05:10:41',NULL),(11,'OPPO Find X7','products/86reBMgLSHo7urLugdIQfq8Lf4J1Hwx9ANLVrw57.jpg',18990000.00,16990000.00,22788000.00,'OPPO Find X7 với chip Dimensity 9300, camera 50MP Hasselblad, màn hình 6.78 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,1,380,45,'2025-08-18 13:56:36','2025-08-19 05:43:10','2025-08-19 05:43:10'),(12,'OPPO Reno 11','products/Zd1n6uNzsOSyP0funeY1vYY6Z2OW3XJE93fpwxKo.jpg',9990000.00,8990000.00,11988000.00,'OPPO Reno 11 với chip Dimensity 7050, camera 50MP, màn hình 6.7 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,4,1,520,65,'2025-08-18 13:56:36','2025-08-19 05:43:23','2025-08-19 05:43:23'),(13,'Vivo X100 Pro','products/cGa1RQ7l63VwnUmZpNqWVkATJVcvrJvW9bYyDZae.webp',21990000.00,19990000.00,26388000.00,'Vivo X100 Pro với chip Dimensity 9300, camera 50MP Zeiss, màn hình 6.78 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,1,423,25,'2025-08-18 13:56:36','2025-08-19 06:46:28',NULL),(14,'Vivo X100','products/V9yphC4hTWK9Eov89m57OqHNNpWVlCGxBYZo9K9z.jpg',17990000.00,15990000.00,21588000.00,'Vivo X100 với chip Dimensity 9300, camera 50MP Zeiss, màn hình 6.78 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,1,350,40,'2025-08-18 13:56:36','2025-08-19 05:47:57','2025-08-19 05:47:57'),(15,'Vivo V29','products/eKkBaaqbX4dx9ap5kOe4POawMxUEvCQon2lw9FBz.jpg',12990000.00,11990000.00,15588000.00,'Vivo V29 với chip Snapdragon 778G, camera 50MP, màn hình 6.78 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,5,1,480,55,'2025-08-18 13:56:36','2025-08-19 05:48:08','2025-08-19 05:48:08'),(16,'Realme GT Neo 5 SE','products/bFTi9xg47qlvAeSQvWKMp74v7WV4AA9tdQXBWsrZ.jpg',8990000.00,7990000.00,10788000.00,'Realme GT Neo 5 SE với chip Snapdragon 7+ Gen 2, camera 64MP, màn hình 6.74 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,1,380,60,'2025-08-18 13:56:36','2025-08-19 05:03:28',NULL),(17,'Realme 11 Pro+','products/MRQHWeg2cMRMEjGrnf1eZtp9zGvWi0mHz2aLau8Z.jpg',12990000.00,11990000.00,15588000.00,'Realme 11 Pro+ với chip Dimensity 7050, camera 200MP, màn hình 6.7 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,6,1,423,45,'2025-08-18 13:56:36','2025-08-19 05:58:40',NULL),(18,'OnePlus 12','oneplus-12.jpg',19990000.00,17990000.00,NULL,'OnePlus 12 với chip Snapdragon 8 Gen 3, camera 50MP Hasselblad, màn hình 6.82 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,1,350,30,'2025-08-18 13:56:36','2025-08-19 05:00:16','2025-08-19 05:00:16'),(19,'OnePlus 12R','products/txZiM9ELFnXTauBh6II29JPuMxrzj9oV6E0NQZqq.jpg',15990000.00,13990000.00,19188000.00,'OnePlus 12R với chip Snapdragon 8 Gen 2, camera 50MP, màn hình 6.78 inch LTPO AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,7,1,280,40,'2025-08-18 13:56:36','2025-08-19 04:57:15',NULL),(20,'Huawei Pura 70 Ultra','products/2JB2Z6O2QWeO66XVruMnsIH6PKKyixEPtZLTWRmc.jpg',24990000.00,22990000.00,29988000.00,'Huawei Pura 70 Ultra với chip Kirin 9010, camera 50MP XMAGE, màn hình 6.8 inch LTPO OLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,1,200,20,'2025-08-18 13:56:36','2025-08-19 04:54:13',NULL),(21,'Huawei Nova 12','products/zAl9aWnkfckaP4RR7tM9VEgzEjp1aKoG8NZoiMOj.jpg',12990000.00,11990000.00,15588000.00,'Huawei Nova 12 với chip Kirin 830, camera 50MP, màn hình 6.7 inch OLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,8,1,180,35,'2025-08-18 13:56:36','2025-08-19 04:50:29',NULL),(22,'Nokia G60','products/qMny9oNWMpF2jJAYLW7VczjxvaSM3XPpJ3D2d3Pn.jpg',5990000.00,4990000.00,7188000.00,'Nokia G60 với chip Snapdragon 695, camera 50MP, màn hình 6.58 inch IPS LCD',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,9,1,150,50,'2025-08-18 13:56:36','2025-08-19 05:17:31','2025-08-19 05:17:31'),(23,'Motorola Edge 40','motorola-edge-40.jpg',9990000.00,8990000.00,NULL,'Motorola Edge 40 với chip Dimensity 8020, camera 50MP, màn hình 6.55 inch pOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,10,1,220,40,'2025-08-18 13:56:36','2025-08-19 04:45:40','2025-08-19 04:45:40'),(24,'ASUS ROG Phone 8','products/FTIMNQ3I1xxLJ8gq0zo3rQU24lyDLC2HuqvQDxHU.webp',22990000.00,20990000.00,27588000.00,'ASUS ROG Phone 8 với chip Snapdragon 8 Gen 3, camera 50MP, màn hình 6.78 inch AMOLED',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,11,1,180,25,'2025-08-18 13:56:36','2025-08-19 04:42:42',NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,3,10,NULL,'Nguyễn Văn A','nguyenvana@gmail.com',5,'Sản phẩm rất tốt, chất lượng vượt mong đợi. Tôi rất hài lòng với sản phẩm này. Giao hàng nhanh, đóng gói cẩn thận.',NULL,1,NULL,'2025-07-27 13:56:36','2025-08-18 13:56:36'),(2,2,8,NULL,'Trần Thị B','tranthib@gmail.com',4,'Sản phẩm tốt, đáng tiền. Chỉ có điều hơi lâu shipping thôi. Chất lượng ổn.',NULL,1,NULL,'2025-07-25 13:56:36','2025-08-18 13:56:36'),(3,3,1,NULL,'Lê Văn C','levanc@gmail.com',3,'Sản phẩm bình thường, không có gì đặc biệt. Giá hơi cao so với chất lượng.',NULL,0,NULL,'2025-07-22 13:56:36','2025-08-18 13:56:36'),(4,2,7,NULL,'Phạm Thị D','phamthid@gmail.com',5,'Tuyệt vời! Sản phẩm chính xác như mô tả. Shop phục vụ rất chu đáo. Sẽ mua lại.',NULL,1,NULL,'2025-08-07 13:56:36','2025-08-18 13:56:36'),(5,3,9,NULL,'Hoàng Văn E','hoangvane@gmail.com',2,'Sản phẩm không như mong đợi. Chất lượng kém, màu sắc không giống hình.',NULL,0,NULL,'2025-07-24 13:56:36','2025-08-18 13:56:36'),(6,5,5,NULL,'Vũ Thị F','vuthif@gmail.com',4,'Sản phẩm khá tốt, đóng gói cẩn thận. Giao hàng đúng hẹn. Giá cả hợp lý.',NULL,1,NULL,'2025-07-31 13:56:36','2025-08-18 13:56:36'),(7,2,4,NULL,'Đinh Văn G','dinhvang@gmail.com',5,'Chất lượng tuyệt vời! Đúng như mô tả, thậm chí còn đẹp hơn. Rất hài lòng.',NULL,1,NULL,'2025-08-07 13:56:36','2025-08-18 13:56:36'),(8,2,4,NULL,'Bùi Thị H','buithih@gmail.com',1,'Rất thất vọng với sản phẩm này. Chất lượng kém, không đáng đồng tiền nào.',NULL,0,NULL,'2025-08-09 13:56:36','2025-08-18 13:56:36'),(9,1,2,2,'Quản Trị Viên','admin@winstar.com',5,'sdsdsđsdssssđs',NULL,1,NULL,'2025-08-19 04:17:19','2025-08-19 04:17:19');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint unsigned NOT NULL,
  `permission_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_permissions_role_id_foreign` (`role_id`),
  KEY `role_permissions_permission_id_foreign` (`permission_id`),
  CONSTRAINT `role_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_permissions`
--

LOCK TABLES `role_permissions` WRITE;
/*!40000 ALTER TABLE `role_permissions` DISABLE KEYS */;
INSERT INTO `role_permissions` VALUES (7,1,41,NULL,NULL),(8,1,42,NULL,NULL),(9,1,43,NULL,NULL),(10,1,44,NULL,NULL),(11,1,45,NULL,NULL),(12,1,46,NULL,NULL),(13,1,47,NULL,NULL),(14,1,48,NULL,NULL),(15,1,49,NULL,NULL),(16,1,50,NULL,NULL),(17,1,51,NULL,NULL),(18,1,52,NULL,NULL),(19,1,53,NULL,NULL),(20,1,54,NULL,NULL),(21,1,55,NULL,NULL),(22,1,56,NULL,NULL),(23,1,57,NULL,NULL),(24,1,58,NULL,NULL),(25,1,59,NULL,NULL),(26,1,60,NULL,NULL),(27,1,61,NULL,NULL),(28,1,62,NULL,NULL),(29,1,63,NULL,NULL),(30,1,64,NULL,NULL),(31,1,65,NULL,NULL),(32,1,66,NULL,NULL),(33,1,67,NULL,NULL),(34,1,68,NULL,NULL),(35,1,69,NULL,NULL),(36,1,70,NULL,NULL),(37,1,71,NULL,NULL),(38,1,72,NULL,NULL),(43,2,54,NULL,NULL),(44,2,55,NULL,NULL),(45,2,56,NULL,NULL),(46,2,58,NULL,NULL),(47,2,59,NULL,NULL),(48,2,60,NULL,NULL),(49,2,62,NULL,NULL),(50,2,63,NULL,NULL),(51,2,64,NULL,NULL),(52,2,65,NULL,NULL),(53,2,66,NULL,NULL),(54,2,67,NULL,NULL),(55,2,68,NULL,NULL),(56,2,70,NULL,NULL),(57,2,71,NULL,NULL),(58,2,72,NULL,NULL),(59,3,58,NULL,NULL),(60,3,62,NULL,NULL);
/*!40000 ALTER TABLE `role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Quản trị viên - quản lý hệ thống','2025-08-18 13:56:36','2025-08-18 13:56:36'),(2,'staff','Nhân viên - xử lý đơn hàng và quản lý sản phẩm','2025-08-18 13:56:36','2025-08-18 13:56:36'),(3,'user','Người dùng - quyền cơ bản','2025-08-18 13:56:36','2025-08-18 13:56:36');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
INSERT INTO `services` VALUES (1,'icon-basket','Mua sắm trực tuyến','Trải nghiệm mua sắm tiện lợi, dễ dàng với giao diện thân thiện và quy trình đơn giản.',1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(2,'icon-bike','Giao hàng tận nơi','Dịch vụ giao hàng nhanh chóng, đảm bảo sản phẩm đến tay khách hàng trong thời gian sớm nhất.',2,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(3,'icon-tools','Bảo hành sản phẩm','Chế độ bảo hành toàn diện, đổi trả linh hoạt đảm bảo quyền lợi tốt nhất cho khách hàng.',3,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(4,'icon-genius','Tư vấn chuyên nghiệp','Đội ngũ tư vấn viên giàu kinh nghiệm, hỗ trợ khách hàng chọn lựa sản phẩm phù hợp nhất.',4,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(5,'icon-mobile','Ứng dụng di động','Mua sắm mọi lúc mọi nơi với ứng dụng di động tiện lợi, tối ưu trải nghiệm người dùng.',5,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL),(6,'icon-lifesaver','Chăm sóc khách hàng','Dịch vụ chăm sóc khách hàng tận tâm, giải đáp mọi thắc mắc và hỗ trợ kịp thời.',6,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL);
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `storages`
--

DROP TABLE IF EXISTS `storages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `storages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `capacity` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `storages`
--

LOCK TABLES `storages` WRITE;
/*!40000 ALTER TABLE `storages` DISABLE KEYS */;
INSERT INTO `storages` VALUES (1,'128GB','2025-08-18 13:56:31','2025-08-18 13:56:31'),(2,'32GB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(3,'64GB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(4,'128GB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(5,'256GB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(6,'512GB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(7,'1TB','2025-08-18 13:56:34','2025-08-18 13:56:34'),(8,'2TB','2025-08-18 13:56:34','2025-08-18 13:56:34');
/*!40000 ALTER TABLE `storages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_point_vouchers`
--

DROP TABLE IF EXISTS `user_point_vouchers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_point_vouchers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `point_voucher_id` bigint unsigned NOT NULL,
  `voucher_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','used','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `expiry_date` date NOT NULL,
  `used_in_order_id` bigint unsigned DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_point_vouchers_voucher_code_unique` (`voucher_code`),
  KEY `user_point_vouchers_point_voucher_id_foreign` (`point_voucher_id`),
  KEY `user_point_vouchers_used_in_order_id_foreign` (`used_in_order_id`),
  KEY `user_point_vouchers_user_id_status_index` (`user_id`,`status`),
  KEY `user_point_vouchers_voucher_code_index` (`voucher_code`),
  KEY `user_point_vouchers_expiry_date_index` (`expiry_date`),
  CONSTRAINT `user_point_vouchers_point_voucher_id_foreign` FOREIGN KEY (`point_voucher_id`) REFERENCES `point_vouchers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_point_vouchers_used_in_order_id_foreign` FOREIGN KEY (`used_in_order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `user_point_vouchers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_point_vouchers`
--

LOCK TABLES `user_point_vouchers` WRITE;
/*!40000 ALTER TABLE `user_point_vouchers` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_point_vouchers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_roles`
--

DROP TABLE IF EXISTS `user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_roles_user_id_foreign` (`user_id`),
  KEY `user_roles_role_id_foreign` (`role_id`),
  CONSTRAINT `user_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_roles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_roles`
--

LOCK TABLES `user_roles` WRITE;
/*!40000 ALTER TABLE `user_roles` DISABLE KEYS */;
INSERT INTO `user_roles` VALUES (1,1,1,NULL,NULL),(2,2,2,NULL,NULL),(3,3,3,NULL,NULL),(4,4,3,NULL,NULL),(5,7,3,NULL,NULL),(8,10,3,NULL,NULL);
/*!40000 ALTER TABLE `user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `district` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ward` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_verification_code` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Quản Trị Viên','admin@winstar.com',NULL,NULL,'$2y$12$M3zhUqwYZyEt3nhpMMYjNOkpXiudRS6KyYm.zuPfYIWc83T/EkK/W',NULL,'Hà Nội, Việt Nam','Tỉnh Bắc Ninh','Thị xã Thuận Thành','Phường Xuân Lâm','0123456789',1,'2025-08-18 13:56:34','2025-08-19 03:56:32',NULL,NULL),(2,'Nhân Viên','staff@winstar.com',NULL,NULL,'$2y$12$o6BiyuMAV8BZbBCH5vWdQe8INUycbfRMyP6q3FurrZHZBtyOq3e3m',NULL,NULL,NULL,NULL,NULL,'0123456790',1,'2025-08-18 13:56:34','2025-08-18 13:56:34',NULL,NULL),(3,'Nguyễn Văn An','user1@example.com',NULL,NULL,'$2y$12$lcDJOcamNTI2pbyw6gN7yOuFctQtvZ6wuwTF8oQTQXD3OJMSrrEXi',NULL,'123 Nguyễn Trãi, Hà Nội','Hà Nội','Đống Đa','Láng Hạ','0987654321',1,'2025-08-18 13:56:35','2025-08-18 13:56:35',NULL,NULL),(4,'Trần Thị Bình','user2@example.com',NULL,NULL,'$2y$12$k6autdIYHfX1bpjrDbmWxOO5DU8zgvMrgrslCMR.BU6M.0Jx42NNe',NULL,'456 Lê Lợi, TP. Hồ Chí Minh','TP. Hồ Chí Minh','Quận 1','Bến Nghé','0987654322',1,'2025-08-18 13:56:35','2025-08-18 13:56:35',NULL,NULL),(5,'Lê Văn Cường','user3@example.com',NULL,NULL,'$2y$12$96EV/ZJkpYhGx6VMY/NnUOjFB5vZutZ33JhM1ng0Z/lLFVInkwevS',NULL,'789 Trần Hưng Đạo, Đà Nẵng','Đà Nẵng','Hải Châu','Phước Ninh','0987654323',1,'2025-08-18 13:56:35','2025-08-18 13:56:35',NULL,NULL),(6,'Phạm Thị Dung','user4@example.com',NULL,NULL,'$2y$12$.ItEIKhDFXSy8LCEsMAyse3lmR1Hq.q7iBT0mCTI/ZHCKy9VVj3P2',NULL,'321 Lý Thường Kiệt, Cần Thơ','Cần Thơ','Ninh Kiều','An Hội','0987654324',1,'2025-08-18 13:56:35','2025-08-18 13:56:35',NULL,NULL),(7,'Hoàng Văn Em','user5@example.com',NULL,NULL,'$2y$12$3SJApHB1qLwARNUbzo3XauuLhQ24kmGniAU/Bj5DQVhcLV5Vw297m',NULL,'654 Nguyễn Huệ, Huế','Thừa Thiên Huế','Thành phố Huế','Phú Hội','0987654325',1,'2025-08-18 13:56:36','2025-08-18 13:56:36',NULL,NULL),(10,'Cường Nguyễn','cuong24102004pl@gmail.com',NULL,'2025-08-19 03:37:15','$2y$12$mg/XFsXZelE1pFTg7WLSm.JuEjdy1fREShGV2uwbb.4U.t..V/2kG',NULL,NULL,NULL,NULL,NULL,'g1755574609x9b59a',1,'2025-08-19 03:36:49','2025-08-19 03:37:15',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `videos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `video_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `background` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
INSERT INTO `videos` VALUES (1,'jdhfjshf','sdsksdk','videos/6Z5QJ9od9XYiS8ET21LXByNNKle7aUfLymLRJLe5.mp4','videos/backgrounds/irUKteqXfiobr4cdNl3SEPGoH83zaS8HhTITfnZD.png','2025-08-19 06:13:33','2025-08-19 06:13:33');
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `view_average_product_rating`
--

DROP TABLE IF EXISTS `view_average_product_rating`;
/*!50001 DROP VIEW IF EXISTS `view_average_product_rating`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_average_product_rating` AS SELECT 
 1 AS `product_id`,
 1 AS `avg_rating`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_daily_paid_revenue`
--

DROP TABLE IF EXISTS `view_daily_paid_revenue`;
/*!50001 DROP VIEW IF EXISTS `view_daily_paid_revenue`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_daily_paid_revenue` AS SELECT 
 1 AS `date`,
 1 AS `paid_revenue`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_daily_revenue`
--

DROP TABLE IF EXISTS `view_daily_revenue`;
/*!50001 DROP VIEW IF EXISTS `view_daily_revenue`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_daily_revenue` AS SELECT 
 1 AS `date`,
 1 AS `revenue`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_monthly_revenue`
--

DROP TABLE IF EXISTS `view_monthly_revenue`;
/*!50001 DROP VIEW IF EXISTS `view_monthly_revenue`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_monthly_revenue` AS SELECT 
 1 AS `month`,
 1 AS `revenue`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_most_viewed_products`
--

DROP TABLE IF EXISTS `view_most_viewed_products`;
/*!50001 DROP VIEW IF EXISTS `view_most_viewed_products`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_most_viewed_products` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `view`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_order_status_count`
--

DROP TABLE IF EXISTS `view_order_status_count`;
/*!50001 DROP VIEW IF EXISTS `view_order_status_count`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_order_status_count` AS SELECT 
 1 AS `status`,
 1 AS `count`,
 1 AS `created_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_paid_revenue`
--

DROP TABLE IF EXISTS `view_paid_revenue`;
/*!50001 DROP VIEW IF EXISTS `view_paid_revenue`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_paid_revenue` AS SELECT 
 1 AS `month`,
 1 AS `paid_revenue`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_revenue_by_category`
--

DROP TABLE IF EXISTS `view_revenue_by_category`;
/*!50001 DROP VIEW IF EXISTS `view_revenue_by_category`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_revenue_by_category` AS SELECT 
 1 AS `category_id`,
 1 AS `category_name`,
 1 AS `revenue`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_stock_by_color`
--

DROP TABLE IF EXISTS `view_stock_by_color`;
/*!50001 DROP VIEW IF EXISTS `view_stock_by_color`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_stock_by_color` AS SELECT 
 1 AS `color_id`,
 1 AS `color_name`,
 1 AS `total_stock`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_storage_variants`
--

DROP TABLE IF EXISTS `view_storage_variants`;
/*!50001 DROP VIEW IF EXISTS `view_storage_variants`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_storage_variants` AS SELECT 
 1 AS `storage_id`,
 1 AS `capacity`,
 1 AS `variant_count`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_top_coupons`
--

DROP TABLE IF EXISTS `view_top_coupons`;
/*!50001 DROP VIEW IF EXISTS `view_top_coupons`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_top_coupons` AS SELECT 
 1 AS `id`,
 1 AS `code`,
 1 AS `used_count`,
 1 AS `created_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_top_customers`
--

DROP TABLE IF EXISTS `view_top_customers`;
/*!50001 DROP VIEW IF EXISTS `view_top_customers`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_top_customers` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `email`,
 1 AS `phone`,
 1 AS `total_orders`,
 1 AS `total_spent`,
 1 AS `avg_order_value`,
 1 AS `first_order_date`,
 1 AS `last_order_date`,
 1 AS `current_points`,
 1 AS `total_earned_points`,
 1 AS `vip_level`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_top_products`
--

DROP TABLE IF EXISTS `view_top_products`;
/*!50001 DROP VIEW IF EXISTS `view_top_products`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_top_products` AS SELECT 
 1 AS `id`,
 1 AS `name`,
 1 AS `total_sold`,
 1 AS `created_at`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_total_stock_per_product`
--

DROP TABLE IF EXISTS `view_total_stock_per_product`;
/*!50001 DROP VIEW IF EXISTS `view_total_stock_per_product`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_total_stock_per_product` AS SELECT 
 1 AS `product_id`,
 1 AS `product_name`,
 1 AS `total_stock`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `view_user_status_count`
--

DROP TABLE IF EXISTS `view_user_status_count`;
/*!50001 DROP VIEW IF EXISTS `view_user_status_count`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `view_user_status_count` AS SELECT 
 1 AS `status`,
 1 AS `count`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `vnpay_transactions`
--

DROP TABLE IF EXISTS `vnpay_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vnpay_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned DEFAULT NULL,
  `vnp_TxnRef` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_Amount` bigint DEFAULT NULL,
  `vnp_ResponseCode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_TransactionNo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_PayDate` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_BankCode` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_CardType` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vnp_SecureHash` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vnpay_transactions_order_id_index` (`order_id`),
  CONSTRAINT `vnpay_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vnpay_transactions`
--

LOCK TABLES `vnpay_transactions` WRITE;
/*!40000 ALTER TABLE `vnpay_transactions` DISABLE KEYS */;
INSERT INTO `vnpay_transactions` VALUES (1,1,'1_1755576500',3002000000,NULL,NULL,NULL,NULL,NULL,NULL,'pending',NULL,'{\"vnp_Amount\": 3002000000, \"vnp_IpAddr\": \"127.0.0.1\", \"vnp_Locale\": \"vn\", \"vnp_TxnRef\": \"1_1755576500\", \"vnp_Command\": \"pay\", \"vnp_TmnCode\": \"1VYBIYQP\", \"vnp_Version\": \"2.1.0\", \"vnp_CurrCode\": \"VND\", \"vnp_OrderInfo\": \"Thanh toan don hang #WS17555764995902\", \"vnp_OrderType\": \"billpayment\", \"vnp_ReturnUrl\": \"http://localhost:8000/payment/vnpay-return\", \"vnp_CreateDate\": \"20250819110820\"}','2025-08-19 04:08:20','2025-08-19 04:08:20'),(2,2,'2_1755576797',2802000000,'00','15136737','20250819111508','NCB','ATM','0c86e65aaa78e31378f1b814264e4e52467e162c494cb6e83ef10234db1182037e9d65a9da4e69ab7a115cfeba881a19bfc343f7e3e049b5c6b4112b93a74544','success','Giao dịch thành công','{\"vnp_Amount\": \"2802000000\", \"vnp_TxnRef\": \"2_1755576797\", \"vnp_PayDate\": \"20250819111508\", \"vnp_TmnCode\": \"1VYBIYQP\", \"vnp_BankCode\": \"NCB\", \"vnp_CardType\": \"ATM\", \"vnp_OrderInfo\": \"Thanh toan don hang #WS17555767974055\", \"vnp_BankTranNo\": \"VNP15136737\", \"vnp_SecureHash\": \"0c86e65aaa78e31378f1b814264e4e52467e162c494cb6e83ef10234db1182037e9d65a9da4e69ab7a115cfeba881a19bfc343f7e3e049b5c6b4112b93a74544\", \"vnp_ResponseCode\": \"00\", \"vnp_TransactionNo\": \"15136737\", \"vnp_TransactionStatus\": \"00\"}','2025-08-19 04:13:17','2025-08-19 04:14:03');
/*!40000 ALTER TABLE `vnpay_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `websockets_statistics_entries`
--

DROP TABLE IF EXISTS `websockets_statistics_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `websockets_statistics_entries` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `app_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `peak_connection_count` int NOT NULL,
  `websocket_message_count` int NOT NULL,
  `api_message_count` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `websockets_statistics_entries`
--

LOCK TABLES `websockets_statistics_entries` WRITE;
/*!40000 ALTER TABLE `websockets_statistics_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `websockets_statistics_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `view_average_product_rating`
--

/*!50001 DROP VIEW IF EXISTS `view_average_product_rating`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_average_product_rating` AS select `r`.`product_id` AS `product_id`,avg(`r`.`rating`) AS `avg_rating` from (`reviews` `r` join `products` `p` on((`r`.`product_id` = `p`.`id`))) where (`p`.`status` = 1) group by `r`.`product_id` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_daily_paid_revenue`
--

/*!50001 DROP VIEW IF EXISTS `view_daily_paid_revenue`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_daily_paid_revenue` AS select cast(`orders`.`created_at` as date) AS `date`,sum(`orders`.`total_amount`) AS `paid_revenue` from `orders` where (`orders`.`payment_status` = 'paid') group by cast(`orders`.`created_at` as date) order by `date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_daily_revenue`
--

/*!50001 DROP VIEW IF EXISTS `view_daily_revenue`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_daily_revenue` AS select cast(`orders`.`created_at` as date) AS `date`,sum(`orders`.`total_amount`) AS `revenue` from `orders` where (`orders`.`status` = 'completed') group by cast(`orders`.`created_at` as date) order by `date` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_monthly_revenue`
--

/*!50001 DROP VIEW IF EXISTS `view_monthly_revenue`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_monthly_revenue` AS select date_format(`orders`.`created_at`,'%Y-%m') AS `month`,sum(`orders`.`total_amount`) AS `revenue` from `orders` where (`orders`.`status` = 'completed') group by `month` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_most_viewed_products`
--

/*!50001 DROP VIEW IF EXISTS `view_most_viewed_products`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_most_viewed_products` AS select `products`.`id` AS `id`,`products`.`name` AS `name`,`products`.`view` AS `view` from `products` where (`products`.`status` = 1) order by `products`.`view` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_order_status_count`
--

/*!50001 DROP VIEW IF EXISTS `view_order_status_count`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_order_status_count` AS select `orders`.`status` AS `status`,count(0) AS `count`,min(`orders`.`created_at`) AS `created_at` from `orders` group by `orders`.`status` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_paid_revenue`
--

/*!50001 DROP VIEW IF EXISTS `view_paid_revenue`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_paid_revenue` AS select date_format(`orders`.`created_at`,'%Y-%m') AS `month`,sum(`orders`.`total_amount`) AS `paid_revenue` from `orders` where (`orders`.`payment_status` = 'paid') group by `month` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_revenue_by_category`
--

/*!50001 DROP VIEW IF EXISTS `view_revenue_by_category`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_revenue_by_category` AS select `cat`.`id` AS `category_id`,`cat`.`name` AS `category_name`,sum(`od`.`total`) AS `revenue` from ((`order_details` `od` join `products` `p` on((`od`.`product_id` = `p`.`id`))) join `categories` `cat` on((`p`.`category_id` = `cat`.`id`))) where (`p`.`status` = 1) group by `cat`.`id`,`cat`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_stock_by_color`
--

/*!50001 DROP VIEW IF EXISTS `view_stock_by_color`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_stock_by_color` AS select `c`.`id` AS `color_id`,`c`.`name` AS `color_name`,sum(`pv`.`stock_quantity`) AS `total_stock` from ((`product_variants` `pv` join `colors` `c` on((`pv`.`color_id` = `c`.`id`))) join `products` `p` on((`pv`.`product_id` = `p`.`id`))) where (`p`.`status` = 1) group by `c`.`id`,`c`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_storage_variants`
--

/*!50001 DROP VIEW IF EXISTS `view_storage_variants`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_storage_variants` AS select `s`.`id` AS `storage_id`,`s`.`capacity` AS `capacity`,count(`pv`.`id`) AS `variant_count` from ((`storages` `s` left join `product_variants` `pv` on((`pv`.`storage_id` = `s`.`id`))) left join `products` `p` on((`pv`.`product_id` = `p`.`id`))) where ((`p`.`status` = 1) or (`p`.`status` is null)) group by `s`.`id`,`s`.`capacity` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_top_coupons`
--

/*!50001 DROP VIEW IF EXISTS `view_top_coupons`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_top_coupons` AS select `c`.`id` AS `id`,`c`.`code` AS `code`,count(`o`.`id`) AS `used_count`,min(`o`.`created_at`) AS `created_at` from (`coupons` `c` join `orders` `o` on((`o`.`coupon_id` = `c`.`id`))) group by `c`.`id`,`c`.`code` order by `used_count` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_top_customers`
--

/*!50001 DROP VIEW IF EXISTS `view_top_customers`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_top_customers` AS select `u`.`id` AS `id`,`u`.`name` AS `name`,`u`.`email` AS `email`,`u`.`phone` AS `phone`,count(`o`.`id`) AS `total_orders`,sum(`o`.`total_amount`) AS `total_spent`,avg(`o`.`total_amount`) AS `avg_order_value`,min(`o`.`created_at`) AS `first_order_date`,max(`o`.`created_at`) AS `last_order_date`,coalesce(`p`.`total_points`,0) AS `current_points`,coalesce(`p`.`earned_points`,0) AS `total_earned_points`,coalesce(`p`.`vip_level`,'Bronze') AS `vip_level` from ((`users` `u` join `orders` `o` on((`u`.`id` = `o`.`user_id`))) left join `points` `p` on((`u`.`id` = `p`.`user_id`))) where (`o`.`status` = 'completed') group by `u`.`id`,`u`.`name`,`u`.`email`,`u`.`phone`,`p`.`total_points`,`p`.`earned_points`,`p`.`vip_level` order by `total_spent` desc,`total_orders` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_top_products`
--

/*!50001 DROP VIEW IF EXISTS `view_top_products`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_top_products` AS select `p`.`id` AS `id`,`p`.`name` AS `name`,sum(`od`.`quantity`) AS `total_sold`,min(`od`.`created_at`) AS `created_at` from (`order_details` `od` join `products` `p` on((`od`.`product_id` = `p`.`id`))) where (`p`.`status` = 1) group by `p`.`id`,`p`.`name` order by `total_sold` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_total_stock_per_product`
--

/*!50001 DROP VIEW IF EXISTS `view_total_stock_per_product`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_total_stock_per_product` AS select `p`.`id` AS `product_id`,`p`.`name` AS `product_name`,sum(`pv`.`stock_quantity`) AS `total_stock` from (`products` `p` left join `product_variants` `pv` on((`p`.`id` = `pv`.`product_id`))) where (`p`.`status` = 1) group by `p`.`id`,`p`.`name` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `view_user_status_count`
--

/*!50001 DROP VIEW IF EXISTS `view_user_status_count`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_unicode_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `view_user_status_count` AS select `users`.`status` AS `status`,count(0) AS `count` from `users` group by `users`.`status` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-08-19 15:33:58
