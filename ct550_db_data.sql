-- MySQL dump 10.13  Distrib 8.4.2, for Linux (x86_64)
--
-- Host: localhost    Database: ct550_db
-- ------------------------------------------------------
-- Server version	8.4.2

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
-- Dumping data for table `affiliate_links`
--

LOCK TABLES `affiliate_links` WRITE;
/*!40000 ALTER TABLE `affiliate_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `affiliate_requests`
--

LOCK TABLES `affiliate_requests` WRITE;
/*!40000 ALTER TABLE `affiliate_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `affiliate_sales`
--

LOCK TABLES `affiliate_sales` WRITE;
/*!40000 ALTER TABLE `affiliate_sales` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `affiliate_wallets`
--

LOCK TABLES `affiliate_wallets` WRITE;
/*!40000 ALTER TABLE `affiliate_wallets` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `affiliate_withdrawals`
--

LOCK TABLES `affiliate_withdrawals` WRITE;
/*!40000 ALTER TABLE `affiliate_withdrawals` DISABLE KEYS */;
/*!40000 ALTER TABLE `affiliate_withdrawals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (5,1,1,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 15:55:02','2024-10-13 15:55:02',25000000,0),(6,1,2,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:20:55','2024-10-13 16:20:55',26000000,0),(7,1,3,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:21:32','2024-10-13 16:21:32',50000000,0),(8,1,4,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:22:10','2024-10-13 16:22:10',18000000,0),(9,1,5,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:23:57','2024-10-13 16:23:57',28000000,0),(10,1,6,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:27:56','2024-10-13 16:27:56',50000000,0),(11,1,7,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:28:25','2024-10-13 16:28:25',40000000,0),(12,1,8,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:03','2024-10-13 16:29:03',30000000,0),(13,1,9,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:36','2024-10-13 16:29:36',12000000,0),(14,1,10,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:54','2024-10-13 16:29:54',30000000,0),(15,1,11,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:24','2024-10-13 16:30:24',52000000,0),(16,1,12,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:39','2024-10-13 16:30:39',50000000,0),(17,1,13,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:54','2024-10-13 16:30:54',16000000,0),(18,1,16,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:31:11','2024-10-13 16:31:11',16000000,0),(19,1,20,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:10','2024-10-13 16:32:10',150000000,0),(20,1,21,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:31','2024-10-13 16:32:31',36000000,0),(21,1,22,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:47','2024-10-13 16:32:47',60000000,0),(22,1,38,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:33:44','2024-10-13 16:33:44',8000000,0),(23,1,39,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:24','2024-10-13 16:34:24',8000000,0),(24,1,40,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:36','2024-10-13 16:34:36',7000000,0),(25,1,41,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:52','2024-10-13 16:34:52',12000000,0),(26,1,42,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:18','2024-10-13 16:35:18',14000000,0),(27,1,14,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:45','2024-10-13 16:35:45',18000000,0),(28,1,15,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:55','2024-10-13 16:35:55',28000000,0),(29,1,17,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:09','2024-10-13 16:36:09',18000000,0),(30,1,18,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:24','2024-10-13 16:36:24',18000000,0),(31,1,19,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:32','2024-10-13 16:36:32',18000000,0),(32,1,35,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:04','2024-10-13 16:43:04',50000000,0),(33,1,36,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:12','2024-10-13 16:43:12',50000000,0),(34,1,27,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:30','2024-10-13 16:43:30',85000000,0),(35,1,28,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:47','2024-10-13 16:43:47',20000000,0),(36,1,29,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:59','2024-10-13 16:43:59',52000000,0),(37,1,37,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:25','2024-10-13 16:44:25',30000000,0),(38,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:47','2024-10-13 16:44:47',15000000,0),(39,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:53','2024-10-13 16:44:53',22000000,0),(40,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:01','2024-10-13 16:45:01',25000000,0),(41,1,25,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:19','2024-10-13 16:45:19',15000000,0),(42,1,26,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:38','2024-10-13 16:45:38',15000000,0),(43,1,43,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:46:09','2024-10-13 16:46:09',42000000,0);
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Khô cá Cà Mau','uploads/7JqgVyDW1LLerZoGjVIxNBX7OnNIBr0l1VH5Xw6S.jpg','2024-09-17 14:46:21','2024-09-17 14:46:21'),(3,'Tôm Khô Cà Mau','uploads/Ev6plxMwSDlSdjdjRCM9bBkyYyMfOd5GMc4YqckX.jpg','2024-09-17 14:51:08','2024-09-17 14:51:08'),(4,'Bánh phồng tôm','uploads/G0IcKTKpKxAYAR9Cp0fJ90VleuALsNrVenwkswj9.jpg','2024-09-17 14:52:19','2024-09-17 14:52:19'),(5,'Khô 1 nắng','uploads/WyQI4FkgDIHsTgmw37Wwut5w8U2IGsY4hrwdWFh0.jpg','2024-09-17 14:53:03','2024-09-17 14:53:03'),(6,'Mực khô Cà Mau','uploads/ivvg9lWGWlorUf1UI7NZ38CubWfGrQHtFusbut2o.jpg','2024-09-17 14:53:34','2024-09-17 14:53:34'),(7,'Mật ong Cà Mau','uploads/bz5fjqEI9JBR7DJqDx4uscZ6R3TRLrSS84ZsCLTe.jpg','2024-09-17 14:54:20','2024-09-17 14:54:20'),(8,'Bánh-Kẹo','uploads/cf2AO9Jj69TqclRJ8wEhgJoorQDuWKf1NG1BktV2.jpg','2024-09-17 14:54:58','2024-09-17 14:54:58'),(9,'Mắm Cà Mau','uploads/NGh2yNkoQdMzEp2u94crmftzEZAg9G8IOzaDvsUq.jpg','2024-09-17 14:55:56','2024-09-17 14:55:56'),(10,'Nem-chả','uploads/RCzDEUZHzoL5SYBkdydw3nLmBZtvGdmKrGHZjlny.jpg','2024-09-17 14:57:03','2024-09-17 14:57:03'),(17,'Đặc sản khác','uploads/m4xU0RQBIs79HlQHEIUshsiCgCmI3xa7Grq3KUnc.jpg','2024-09-17 15:07:30','2024-09-17 15:07:30');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_06_27_103432_modify_users_table',1),(6,'2024_07_11_100850_add_google_fields_to_users_table',1),(7,'2024_07_28_123320_create_category_table',1),(8,'2024_07_29_214830_create_products_table',1),(9,'2024_07_30_185504_create_carts_table',1),(10,'2024_07_30_185612_create_orders_table',1),(11,'2024_07_30_185823_create_order_detail_table',1),(12,'2024_07_30_185911_create_favorites_table',1),(13,'2024_07_30_185958_create_reviews_table',1),(14,'2024_08_12_155137_modify_orders_table',1),(15,'2024_08_12_160538_modify_order_detail_table',1),(16,'2024_08_12_161315_modify_user_table',1),(17,'2024_08_16_112553_create_permission_tables',1),(18,'2024_08_16_115625_remove_roles_column_from_users_table',1),(19,'2024_08_16_120053_update_favorites_table',1),(20,'2024_08_16_125224_update_orders_table',1),(21,'2024_08_17_134127_add_description_to_roles_table',1),(22,'2024_08_17_173449_add_description_to_permissions_table',1),(23,'2024_08_19_104748_add_weight_to_products_table',1),(24,'2024_08_19_105424_create_batches_table',1),(25,'2024_08_19_112950_delete_product_quantity_from_products_table',1),(26,'2024_08_19_123147_add_user_id_to_batches_table',1),(27,'2024_08_19_124910_add_batch_id_to_order_detail_table',1),(28,'2024_08_19_155410_add_import_cost_and_sold_quantity_to_batches_table',1),(29,'2024_08_20_112241_modify_batches_table',1),(30,'2024_08_26_135129_create_order_detail_batches_table',1),(31,'2024_08_26_145902_delete_batch_id_from_order_detail_table',1),(32,'2024_08_27_134228_create_notifications_table',1),(33,'2024_08_28_233619_modify_notifications_table',1),(34,'2024_08_30_163355_add_pay_method_to_orders_table',1),(35,'2024_08_31_155114_create_refund_requests_table',1),(36,'2024_09_02_143837_create_promotions_table',1),(37,'2024_09_02_144004_create_product_promotions_table',1),(38,'2024_09_02_230854_create_messages_table',1),(39,'2024_09_14_153924_modify_carts_table',1),(40,'2024_09_16_230705_modify_image_column_in_users_table',1),(42,'2024_09_17_140920_modify_categories_table',2),(47,'2024_09_18_192728_create_affiliate_links_table',3),(48,'2024_09_18_193242_create_affiliate_sales_table',3),(49,'2024_09_18_193440_create_commissions_table',3),(50,'2024_09_18_193655_create_affiliate_requests_table',3),(51,'2024_09_19_223615_modify_user_table',4),(52,'2024_09_20_103616_modify_users_table',5),(53,'2024_09_24_131835_modify_affiliate_sales_table',6),(54,'2024_09_24_204950_modify_products_table',7),(55,'2024_09_24_214405_modify_affiliate_sales',8),(56,'2024_09_24_224916_modify_affiliate_sales',9),(57,'2024_09_25_205544_create_affiliate_wallets_table',10),(58,'2024_09_25_205908_create_affiliate_withdrawals_table',10),(59,'2024_09_27_000312_add_read_at_to_messages_table',11),(61,'2024_10_07_134106_modify_batches_tables',12),(62,'2024_10_11_213649_add_last_password_reset_to_users_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (6,'App\\Models\\User',1),(2,'App\\Models\\User',13);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `order_detail`
--

LOCK TABLES `order_detail` WRITE;
/*!40000 ALTER TABLE `order_detail` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `order_detail_batches`
--

LOCK TABLES `order_detail_batches` WRITE;
/*!40000 ALTER TABLE `order_detail_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_detail_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(2,'search products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(3,'add to cart','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(4,'manage wishlist','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(5,'place order','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(6,'make payment','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(7,'cancel order','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(8,'message store','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(9,'review products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(10,'update profile','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(11,'view order history','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(12,'register as affiliate marketer','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(13,'manage affiliate products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(14,'share affiliate links','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(15,'manage products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(16,'delete products','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(17,'manage orders','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(18,'view sales reports','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(19,'manage customers','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(20,'manage reviews','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(21,'manage inventory','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(22,'manage promotions','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(23,'manage shipping','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(24,'manage staff','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(25,'manage affiliate marketers','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `product_promotions`
--

LOCK TABLES `product_promotions` WRITE;
/*!40000 ALTER TABLE `product_promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Khô cá đuối đen','[\"uploads/S2OK79lJuwgXloqlwZMy65k7N1nyJrbjv578TrOm.jpg\", \"uploads/d4Cxno2I1309fFq3adSC579ohL7rjdbX4YwNws22.jpg\", \"uploads/cPXd1swpw9fKIdNrnmn8yqVLrzeZ3eSacXsTEKQG.jpg\"]','Khô cá đuối đen, đuối ó thượng hạng.\r\nLà đặc sản đặc biệt dùng để làm quà.\r\n Miếng cá đuối khô xòe như nan quạt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nHút chân không nhãn mác mang nước ngoài.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,320000,0.50,163,'2024-09-21 17:04:23','2024-10-12 17:01:10'),(2,'Khô cá sặc','[\"uploads/YJpT8X1HQZCLii0pQI3qpgzIBiDF6gGKWGHYa6rd.jpg\", \"uploads/Mh64TUzPGCv1ZEbm1VwKD0W3gWYIBpDkc6HYQbb1.jpg\", \"uploads/TqkBAClPJJsgDRon250GjrDh7xMMGlHh3Duus2Py.jpg\"]','Khô cá sặc lạt vừa ăn, không mặn không cứng.\r\nCá to đẹp thích hợp làm quà biếu, tặng.\r\nKHÔNG chất bảo quản, KHÔNG phẩm màu.\r\nCá khô hẳn có thể mang đi xa, đi nước ngoài.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,390000,1.00,6,'2024-09-21 18:43:33','2024-10-10 22:27:15'),(3,'Khô cá chạch đồng','[\"uploads/sUOejlMdPDhPm2OYf3kSaw7at5voqg1kZWOYArTH.jpg\", \"uploads/2IsVWa2stTLrsbQ2Ba8rfgn2UMyEVXFXUbKtd0vr.jpg\", \"uploads/w2BamsUQdUGjBWeAGJ8X0HyTFfwAyf4jbQCA8xnc.jpg\"]','Khô cá chạch đồng 100% tự nhiên.\r\nLàm thủ công HOÀN TOÀN.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nĐược phơi 3-4 nắng nên có thể mang đi nước ngoài.\r\nLàm mồi nhắm bia rượu ngon, trị bệnh gan.\r\nHút chân không bao bì dùng để làm quà.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,700000,1.00,3,'2024-10-05 23:42:49','2024-10-09 19:48:43'),(4,'Khô cá cơm','[\"uploads/uim58xjhaZ6evVGrsE3A98vJUh6eZEKQ4AlXVcZ7.jpg\", \"uploads/Qeun2wM8XuTYb3syiPFYvOtKhftOAMe8w4D9Pkhz.jpg\", \"uploads/hyx7x1qsbPEmb4JVh98MKvWNdQHM6Y4WZJLSOzoZ.jpg\"]','Khô cá cơm Cà Mau loại to nhất 4cm - 6cm.\r\nLàm từ cá tươi - Hoàn toàn thủ công\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,220000,1.00,0,'2024-10-05 23:47:17','2024-10-05 23:54:19'),(5,'Khô cá đù','[\"uploads/Y8edZ5piSX2Hc4VXhFivUXpFvepMlWJjmv1uulMU.jpg\", \"uploads/dvugTdaOedVynuVn71qWawFNkeI8ni8FCb89xfJf.jpg\", \"uploads/CS89GsJcAAr4uGMmOrxC2TWDrSJfdpMaFjJ2mEGK.jpg\"]','Khô cá đù nhiều nắng loại vừa ăn không mặn.\r\nLoại nhiều nắng có thể mang đi nước ngoài\r\nLàm thủ công gia truyền làng biển.\r\nChiên hoặc nướng ăn ngon với cơm và canh, rau.\r\nKhông hoá chất - Đảm bảo vệ sinh ATTP.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,320000,1.00,0,'2024-10-05 23:48:20','2024-10-05 23:54:57'),(6,'Khô cá dứa','[\"uploads/0EeyA8K17SCcV6Hgjbuix95BS5MqJTesUYqJUcEY.jpg\", \"uploads/YxRGM4Xnm479VDnArQ86kQvoKGqQ38ce4f51Gz9P.jpg\", \"uploads/rvRmWGivD4XfcdmUVziqbAcPiDJvwHFzsbIFF6Bb.jpg\"]','Khô cá dứa loại ngon thật 100%.\r\nDứa loại to KHÔNG ĐẦU từ 800gr-1.6kg/con.\r\nChỉ bán theo con không bán theo ký\r\nLoại cá khô hẳn (3 nắng) có thể mang đi xa.\r\nĐảm bảo ATVSTP và làm thủ công.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,580000,1.00,0,'2024-10-05 23:57:05','2024-10-05 23:57:05'),(7,'Khô cá kết','[\"uploads/FC1yt5OyHpGKX7vmqQlvETaUQCCuTNx7szTl0rla.jpg\", \"uploads/6GzjwLaf3mkd1uTOJrFq5naFbV4lEU6ZXmIzfXeC.jpg\", \"uploads/ZGIG5UtHNZ2rH1jAzYsLNYRByOuoah9VsGfZlLpI.jpg\"]','Khô cá kết, khô cá trèn bầu Miền Tây.\r\nĐược lấy trực tiếp từ nông dân vùng sông nước.\r\nĐược sản xuất thủ công và hoàn toàn tự nhiên.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nSản phẩm thích hợp làm quà tặng.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,500000,1.00,0,'2024-10-05 23:58:39','2024-10-05 23:58:39'),(8,'Khô cá lóc non','[\"uploads/6liJEZWb0RQome2SX96Y739WclaEWN3iquWL1za8.jpg\", \"uploads/DxvmA5yCyHJWn8EhEti9fWNvx4BYR8MTYphssAXU.jpg\", \"uploads/6Q8kq5TXOXh3BFAovuBLbTEXyLQK6ylMH5NUaEIC.jpg\"]','Kích thước nhỏ nhưng cực ngon (15-25con).\r\nLoại nguyên con, không tẩm vị, lóc đồng tự nhiên.\r\nThịt chắc, mềm, ngọt, thơm ngon tự nhiên.\r\nLoại 3 nắng có thể mang đi xa, đi nước ngoài.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC',2,360000,1.00,0,'2024-10-06 00:04:42','2024-10-06 00:04:42'),(9,'Khô cá mối 1','[\"uploads/VYoGsnC9dSriiObrdlOg1iTtUxY6yu175ZA9G1oF.jpg\", \"uploads/2U5JheUxAfR8R6yqh6w4IwEJGzUBMfQIKWbXIADy.jpg\", \"uploads/tdXD2QEShilhQ1qInitBugVX8BKKRsmOUc8F6jRS.jpg\"]','Khô cá mối 1 nắng vừa ăn không tẩm ướp.\r\nKHÔNG hóa chất - Không phẩm màu.\r\nSản phẩm nhà làm - Thủ công hoàn toàn.\r\nFree ship khi mua từ 2kg hoặc đơn >=800k.\r\nGiao hàng thu tiền Tận nơi TPHCM.',2,180000,1.00,0,'2024-10-06 00:06:25','2024-10-06 00:06:25'),(10,'Khô cá sặc rằn','[\"uploads/OH4QzKlAP74beg4xFbbhSi3BU4may9XXi6LldA9t.jpg\", \"uploads/mQhKhOb0oaMe97kYDp06Ill7lCulElEROAww7bXo.jpg\", \"uploads/RAE8hGclZV5oD1S068O5aX946Lyv3unvT7rzljNm.jpg\"]','Khô cá sặc lạt vừa ăn, không mặn không cứng.\r\nCá to đẹp thích hợp làm quà biếu, tặng.\r\nKHÔNG chất bảo quản, KHÔNG phẩm màu.\r\nCá khô hẳn có thể mang đi xa, đi nước ngoài.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,390000,1.00,1,'2024-10-06 00:19:50','2024-10-10 22:26:55'),(11,'Khô cá thiều','[\"uploads/9QtVHTbumHXxdhaPUHtM5lSCiZ4oWNvDqCjxJG2W.jpg\", \"uploads/e3HTXoV9FZ0iTOiOgG1saP64asS1UebkhiNRAgx0.jpg\", \"uploads/A6AxkHas27EfzHvN6qfKfbrDHT7Qiq2bOA7izjoG.jpg\"]','Khô cá thiều miếng tiêu sọ Phú Quốc loại thượng hạng.\r\nĐược làm hoàn toàn 100% cá thiều tươi sống.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nĐóng gói hút chân không, bao bì đầy đủ.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,620000,1.00,0,'2024-10-06 00:21:00','2024-10-06 00:21:00'),(12,'Khô cá thòi lòi','[\"uploads/99AxxnYA0fEHsbNzJDMLrDInwB5ynCC6okBDhABj.jpg\", \"uploads/NcW7tmw5KgKJ23g8nqbHRkZP0MKV6pSicUnMPsK8.jpg\", \"uploads/TnOtFDfQbV1UczfgCXd9r7kT0SYOEe8sY9WsAWyg.jpg\"]','Khô cá thòi lòi nguyên còn Cà Mau\r\nĐặc sản Quái Thú Leo Cây Cà Mau.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nXuất xứ: Rạch Gốc - Cà Mau.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,550000,1.00,0,'2024-10-06 00:22:03','2024-10-06 00:22:03'),(13,'Khô cá thu','[\"uploads/Pu5TUYED3nGypICFQdcOeVogpQr3XzDGUn7QONh8.jpg\", \"uploads/adcRVjiZRgnPd2Bk9XhqtHONK75zQHdbZxDrKf4P.jpg\", \"uploads/LyMgxDIyZ9PsGGsEvp8B4R8EgP1vAFeOmvTnG1UN.jpg\"]','Khô cá thu thịt bùi, vị tươi ngon vừa ăn. \r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nĐảm bảo vệ sinh và an toàn thực phẩm.\r\nCá có nhiều đạm là món ăn đổi vị tuyệt vời.\r\nHút chân không, nhãn hiệu mang đi nước ngoài hoặc biếu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,200000,1.00,0,'2024-10-06 00:22:51','2024-10-06 00:22:51'),(14,'Cá sặc 1 nắng','[\"uploads/t933nmMSbuIctVzRTnERUVDEf2eH1EJZK2SfPXXQ.jpg\", \"uploads/5olyoT8WdlweLNhoo6saVbEEBd7kDwIgkCGgRaUB.jpg\", \"uploads/tGR39pjL8mZtN4EFIAq4lofVRvK3su27zKTrQMHQ.jpg\"]','Cá sặc 1 nắng ướp sả ớt được làm từ cá sống 100%.\r\nCá có size 8-10con/kg đóng gói 1kg như hình.\r\nLàm bằng phương pháp thủ công không hoá chất.\r\nKhô được đóng gói 1kg và bảo quản lạnh.',5,200000,1.00,0,'2024-10-06 00:24:46','2024-10-06 00:26:25'),(15,'Gò má cá lóc 1 nắng','[\"uploads/Nmhgv35YbtphEzVU6DZ9frvrR1j9MRFOUitHGt0u.jpg\", \"uploads/vCIRDzFcqYLBFf9Ests14qC7sjpeLksiReiszE3p.jpg\", \"uploads/qwc8zlm0EdGm0UK4suXEQBUt1YFz57hbwzo685Wn.jpg\"]','Thịt má được lấy ra từ phần thịt 2 bên đầu cá.\r\nChỉ có những con cá lóc đồng to mới có thịt má.\r\nĐược ướp gia vị vừa ăn, phơi 1 nắng tốt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.',5,300000,1.00,0,'2024-10-06 00:26:04','2024-10-06 00:26:04'),(16,'Khô cá đổng','[\"uploads/wpY9DQEow1fYKsV9wYRTViqop9Zjl2qbhOK95Mts.jpg\", \"uploads/7j6HIF9Q2e0xJyN4AwoCnPcEjOY80spEKvBy9Iyw.jpg\", \"uploads/l7NL4zue7QqgfyswR34yrBsIUDW75xTFKRVboiGp.jpg\"]','Khô cá đù 1 nắng vừa ăn mềm vị lạt.\r\nĐổng 1 nắng loại nguyên con 6-10con/kg\r\nKhông hóa chất - Không phẩm màu.\r\nBảo quản cấp đông không quá 30 ngày.\r\nGiao hàng thu tiền tận nơi toàn quốc.',2,200000,1.00,2,'2024-10-06 00:28:09','2024-10-10 22:27:30'),(17,'Khô cá lưỡi trâu','[\"uploads/NWqQpFuK2OUvE4akRoACDMm56SClIFLoXHARF0Xt.jpg\", \"uploads/IzLz3eRv3JNatFUIoXySjsj6Mk0NIEtbDCdXqUvA.jpg\", \"uploads/zgr4dgx0JEymHM38d0PxUcIAmvX5xl9sNGjb20yw.jpg\"]','Khô cá lưỡi trâu 1 nắng loại 12-20con/kg\r\nTẩm ướp gia vị vừa ăn không mặn.\r\nLoại cá lớn ít xương nhiều thịt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nMiễn phí giao hàng nội ô TPHCM khi mua từ 2kg.\r\nChỉ giao hàng TPHCM và các tỉnh lân cận.',5,190000,1.00,0,'2024-10-06 00:29:43','2024-10-06 00:29:43'),(18,'Khô cá sặc trứng','[\"uploads/ZZcmqcJy6EOGRUnMrt4o8whNIULcFW77ckJo3CnG.jpg\", \"uploads/zBNRuf93GpYMU4eIdgAJkuM6osZ1OUC6NWcwc561.jpg\", \"uploads/IdbcxGK8yE38E0GRY9avHXpwEldfPBikuQXjCJmo.jpg\"]','Khô cá sặc trứng 1 nắng loại vừa ăn ngon.\r\nSặc trứng 1 nắng loại  6 - 8 con/kg.\r\nXuất xứ: Huyện U Minh, Cà Mau.\r\nTHỊT thơm ngon - KHÔNG bở - ÍT xương.\r\nKhông chất bảo quản - Đảm bảo VSATTP.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.\r\nXem thêm: Khô cá sặc 1 nắng ướp sả ớt.',5,200000,1.00,0,'2024-10-06 00:31:01','2024-10-06 00:31:01'),(19,'Khô cá đù 1 nắng','[\"uploads/Q6VwC6zR4DeGwliPS75QquYY8stzB8uA1CaefEkB.jpg\", \"uploads/VZBZND1lZAFIsv4RWOPO25W2fUYg9xRJiQ7BKNYS.jpg\", \"uploads/uVBeIt9jdlAAGmRBEos5yQorpX4Ow4dLzH322VjN.jpg\"]','Khô cá đù 1 nắng vừa ăn mềm vị lạt.\r\nCó loại xẻ rút xương và loại nguyên con.\r\nKhông hóa chất - Không phẩm màu.\r\nBảo quản cấp đông không quá 30 ngày.\r\nGiao hàng thu tiền tận nơi toàn quốc.',5,200000,1.00,0,'2024-10-06 00:32:44','2024-10-06 00:32:44'),(20,'Tôm đất khô','[\"uploads/IHNfqyxuqNn3ehHfjDlKeknpjSPnVkyg7gXaLPFz.jpg\", \"uploads/eIXhA8jyfiVeRMB557tpWpgLIF8LkMMUqwi6dhLd.jpg\", \"uploads/G5znnk31cNhjOpq2kZwbrD9It7Drz8tDZ5STivAB.jpg\"]','Tôm khô làm THỦ CÔNG từ tôm đất sống.\r\nXuất xứ: Rạch Gốc, Năm Căn - Cà Mau.\r\nMàu sắc tự nhiên KHÔNG chất tạo màu.\r\nĐảm bảo, Hoàn tiền nếu khách không vừa ý.\r\nĐóng gói - Hút chân - Bao bì để gửi nước ngoài.\r\nHộp đẹp cho khách làm quà tặng biếu TẾT.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,1600000,1.00,0,'2024-10-06 00:33:55','2024-10-06 00:33:55'),(21,'Tôm khô chà bông','[\"uploads/6Aw7FbwMyOTzqqEIm9Kwkl1KhLD4JniMmBxASCE0.jpg\", \"uploads/ByGt3zcVWnqjh8G45u6Mlcq8XoGycHHgZ3rHjwDS.jpg\", \"uploads/M7n7i5NnnsoKNcz3pJyXuqO2ZlQKFe6Pka7UXYjU.jpg\"]','100% làm từ tôm đất sống Cà Mau.\r\nQuy trình làm công phu, thủ công hoàn toàn.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nSản phẩm có nhãn hiệu, bao bì đẹp làm quà.\r\nĐặc sản Cà Mau làm quà tặng ngày Lễ, Tết.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,400000,0.25,0,'2024-10-06 00:35:28','2024-10-06 00:35:28'),(22,'Tôm khô nấu canh','[\"uploads/MsHcRevrqhOfqorJNt256jtSC6KZbjaxfBBBcWFR.jpg\", \"uploads/xs4nXLzv3qfK1ysTZ3jpHDokSv10kg6nJcysC3ka.jpg\", \"uploads/QgYbgD6bs67xQpdkS9Xp0cUWIuhVq2oxzqbOjdlD.jpg\"]','Tôm nõn khô nấu canh làm từ tôm thẻ.\r\nDùng để chế biến món ngon như tôm khô canh bầu bí, tôm khô kho quẹt, tôm khô sốt cà, tôm khô kho thịt.\r\nLàm THỦ CÔNG từ tôm tươi sống.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nĐặc biệt tôm khô có thể ăn sống hoặc ăn cùng củ kiệu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,640000,1.00,1,'2024-10-06 00:36:53','2024-10-10 00:08:54'),(23,'Tôm khô nguyên vỏ','[\"uploads/moMvQqLFPBwIOc5Mx5AkxLde4t7xZIRQfoKdd9No.jpg\", \"uploads/HnLHLFSQsLcjl99T6GhBqxlPeWO88JLnJD99OaXY.jpg\", \"uploads/plkcEPMWKa6NejasmyLvjTkfif19iCjXheTryU1K.jpg\"]','Làm từ 100% tôm đất sống vùng sinh thái.\r\nThích hợp làm mồi nhắm rượu bia.\r\nSản phẩm đẹp - ngon thích hợp làm quà biếu.\r\nLàm thủ công, nguyên liệu tươi sống.\r\nKhông hóa chất - Không phẩm màu.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',3,900000,1.00,1,'2024-10-06 00:37:40','2024-10-12 17:09:35'),(24,'Tôm tích khô','[\"uploads/GVQvOrDR4xnri7jIyRWJ8nrqEgQE3O4cbAQIOmwI.jpg\", \"uploads/x4a5XDF5kd65WIOv1G7olLbqI9oYJouzHw05c0UU.jpg\", \"uploads/MRcO5QHiJolRt4P4bXgL2R5V5XuoLais7oNg5pJS.jpg\"]','Khô tôm tít Cà Mau loại ngon không tẩm ướp.\r\nLàm từ tôm tươi sống - Hoàn toàn thủ công.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,660000,1.00,1,'2024-10-06 00:38:34','2024-10-09 19:49:38'),(25,'Chả tôm Cà Mau','[\"uploads/UD6UBTx2WpKJDsxVsGe0LQ9awhyuAkdTJMkBVtBF.jpg\", \"uploads/Ez5g8J8rzAD7s0pAks14YRjd3lPaG0KtLDbbiwGY.jpg\", \"uploads/EhCT3gi7XKgGaFERudoQLMPgqZsZzHFtRA1GisnA.jpg\"]','Chả tôm tươi, Đặc sản Cà Mau.\r\nLàm 100% từ tôm tươi vùng sinh thái Cà Mau.\r\nKHÔNG chất bảo quản - KHÔNG phẩm màu.\r\nChả đậm vị tôm tươi, thơm ngon bao dai.\r\nGiao hàng tận nơi TOÀN QUỐC.',10,180000,0.50,0,'2024-10-06 00:39:43','2024-10-06 00:39:43'),(26,'Lạp xưởng tôm','[\"uploads/98fgBn7bX4mjPQdxrVu9W3u2URB9CdKCADoAg8m1.jpg\", \"uploads/KLq791AlqKKYm3HPKWFHvZUrEFWXwJbzDBjprxoy.jpg\", \"uploads/8gBjbNq74TdewuLHna7BpeuBzZOa62r5a4r686AW.jpg\"]','Lạp xưởng tươi vị tôm đậm chất Cà Mau.\r\nĐược làm từ tôm tươi sống vùng sinh thái Cà Mau.\r\nNgọt dịu - Thơm ngon - Không ngán, ngấy.\r\nĐặc sản không thể thiếu trong mâm cổ ngày Tết.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.',10,180000,1.00,0,'2024-10-06 00:40:36','2024-10-06 00:40:36'),(27,'Mật ong rừng nguyên tổ','[\"uploads/N1xT0MUFCkcPww08DwcZi0q1GWnNshqIT2zTCpW7.jpg\", \"uploads/ucLDxuNTCXOgU8MegzwH4FvOfOUoBP7Hv5jT29kL.jpg\", \"uploads/Ee2xgwtJ16zA8z1mhDKhIKEkdTGAHjSivZ1sQEDJ.jpg\"]','Ong ruồi nguyên tổ (sáp và mật).\r\nVắt lấy mật hoặc có thể ăn cả sáp và mật.\r\nMật ong hoàn toàn TỰ NHIÊN.\r\nĐược lấy bởi nông dân lấy ong chuyên nghiệp.\r\nSau khi vắt có thể lấy sáp dùng để ngâm rượu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,900000,1.00,40,'2024-10-06 00:41:42','2024-10-06 15:56:46'),(28,'Mật ong rừng U Minh','[\"uploads/bQyQrBTr22XswmnI5vkij3tkbioWcOrTHwYH8Ity.jpg\", \"uploads/1LwAH5ewDqKWRhzS9mZVZwTy4uspv5CaC6k7aBZo.jpg\", \"uploads/OqlzYRj7yPxMDFRnYWyzCYPCIC0dyIQfrXjm1Te4.jpg\"]','Xuất xứ: rừng tràm U Minh Hạ - Cà Mau.\r\nMật ong thật 100%; Hoàn tiền nếu mật giả.\r\nLấy trực tiếp từ nông dân sống tại rừng U Minh.\r\nThích hợp cho việc làm đẹp và tốt cho sức khỏe.\r\nSản phẩm đảm bảo và cam kết hoàn tiền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,250000,0.50,0,'2024-10-06 00:42:42','2024-10-06 00:42:42'),(29,'Mật ong ruồi','[\"uploads/IHrpIkg2iIhoOiG1uxYmCQrAhXgwTaaCldMaSRqX.jpg\", \"uploads/SNePQdqbpIJ3gOopxiAucMBedMh9vXRv8a5ytyCE.jpg\", \"uploads/UH18FWDTjGFZovYTirBFlw1O6Ht1lwCsVSCSrouS.jpg\"]','Mật ong thật 100%; Hoàn tiền nếu mật giả.\r\nThích hợp cho việc làm đẹp và tốt cho sức khỏe.\r\nLấy trực tiếp từ nông dân bắt ong chuyên nghiệp.\r\nSản phẩm đảm bảo và cam kết hoàn tiền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,600000,0.50,0,'2024-10-06 00:43:45','2024-10-06 00:43:45'),(30,'Mắm ba khía','[\"uploads/qNNWb15Q73ci9C8Bal9xpVCGY8kCxgXLoU19g7P3.jpg\", \"uploads/2OXtFbc21JS8ZXgDUFqsUFDAxAsN2a8snWocEyyO.jpg\", \"uploads/HruhUtyLf4bnT3LAH7Bt6R1Tym6z2Fz62xZUXMRR.jpg\"]','Mắm ba khía Rạch Gốc - Cà Mau.\r\nLàm vị theo yêu cầu (ít ngọt hoặc ít cay).\r\nĐược chế biến Thủ công - Truyền thống.\r\nGiao hàng tận nơi TOÀN QUỐC.',9,175000,0.60,0,'2024-10-06 00:44:51','2024-10-06 00:44:51'),(31,'Mắm cá lóc','[\"uploads/b6ugstjo70opUExBKRwOBuIj6QfA5Mw0k3dqKdHm.jpg\", \"uploads/5fpkiOdluPe03ootgm6FABFXcqWH4qsKKES3fNcS.jpg\", \"uploads/ObFb5RwjRX6Isd36DrRVBxlvwLJ0westKbLG1hyp.jpg\"]','Mắm cá lóc đồng U Minh - Cà Mau.\r\n1kg giá 250.000đ; hũ nửa ký 130.000đ.\r\nLàm từ cá lóc đồng chính gốc U Minh.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',9,250000,1.00,0,'2024-10-06 00:45:41','2024-10-06 00:45:41'),(32,'Mắm tép Cà Mau','[\"uploads/jw6j5bSJga3eQwQppgKLjHxxRVI0ruY5qLqQRTfU.jpg\", \"uploads/siBLFOu4hVvFbyqGa2lFddumhw3SdQkcOCenqOr9.jpg\", \"uploads/Q5jB8b8sLqZk8qv4OAIjPOFLrWNUvObSZSOWNGsj.jpg\"]','Mắm tép Cà Mau được làm từ tôm tươi sống.\r\nHoàn toàn THỦ CÔNG - KHÔNG PHẨM MÀU.\r\nĐảm bảo vệ sinh - ANTT.\r\nCông thức gia truyền, dai ngon, tôm còn thịt.\r\nHương vị miền Nam chua cay.\r\nNgon hơn khi trộn với đu đủ (12 tiếng là ăn được).',9,270000,1.00,0,'2024-10-06 00:46:39','2024-10-06 00:46:39'),(33,'Mắm tép trộn đu đủ','[\"uploads/3nJdngAa3fuPtEdXkIQgeALkdd5O01gwqxofb0Qw.jpg\", \"uploads/n7y65Ey0IFkQYE0vyYvIb7C54uK00TJvVH6ZvzBf.jpg\", \"uploads/tDco85cH59FxOqjhXG1VhWjsy5NBLNN3qzzEEyKF.jpg\"]','Mắm tép trộn đu đủ Cà Mau thơm ngon.\r\nNguyên liệu: 1 nửa mắm tép, 1 nửa đu đủ.\r\nMắm tép chua ngọt đậm mùi tôm Cà Mau.\r\nĂn cực ngon cùng cơm trắng và các món khác.\r\nMiễn phí giao hàng nội ô khi mua 2 hũ.',9,170000,1.00,0,'2024-10-06 00:47:33','2024-10-06 00:47:33'),(34,'Khô mực loại 1','[\"uploads/whhWhWwwL32XLFHpW8YgSCCRpDJBbwdJiwGxJNDC.jpg\", \"uploads/k3iZQ8rAgJhNXNnEYXaYLw2BvDKo8gbRKmZaon4U.jpg\", \"uploads/PCZtEDMcVfC2xSP7eZzCp2rrcVf9aUydoHKkMimB.jpg\"]','Xuất xứ: Sông Đốc Cà Mau.\r\nLà loại khô câu, ngon nhất Cà Mau.\r\nMực con TO - DÀY - NGỌT THỊT.\r\nLàm từ mực tươi sống - Khô chế biến thủ công.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',6,1450000,1.00,0,'2024-10-06 00:48:35','2024-10-06 00:48:35'),(35,'Khô mực loại nhỏ','[\"uploads/H1iwww6KjSDPHzx88lLfALaQPTTPGFOK6OXZ7JsH.jpg\", \"uploads/P3v98JlJeIRjwTyM9hSN2cooUjFTbAx5ReDYz3H2.jpg\", \"uploads/ZUumkQBEJ7PDhhXl1IHLPUG9VuJMuVXNbxtCXfJ0.jpg\"]','Khô mực chính gốc Sông Đốc Cà Mau.\r\nKhô mực loại nhỏ các loại.\r\nLàm thủ công từ mực tươi sống.\r\nKhông hoá chất - Không phẩm màu.\r\nDùng cho các món rim, nướng hoặc chiên.\r\nGiao hàng thu tền tận nơi Toàn Quốc.',6,550000,1.00,0,'2024-10-06 00:49:30','2024-10-06 00:49:30'),(36,'Khô mực nấu súp','[\"uploads/p3BTvynyDJRMUP92B92R78D0iQ62DweB8j9RhPCz.jpg\", \"uploads/tI07dSuGs5RLVmUA6stU0dNAAcYTvp5nTaEMLqpc.jpg\", \"uploads/uAJbgQXmS9x424zxbiu2CDOBc92TCn3Xae2681YT.jpg\"]','Khô mực nấu súp, mực đồng tiền Sông Đốc.\r\nMực tươi, ngọt nước dùng để nấu súp.\r\nMực có size >100con/kg con to tròn không đen.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',6,550000,1.00,0,'2024-10-06 00:50:21','2024-10-06 00:50:21'),(37,'Hồng treo gió','[\"uploads/ZWmcHEaUlhCsxaHowGCi75Ajps8J00XIWBJXtDoq.jpg\", \"uploads/4raSDS2yPgNnHq08C7nDuFGWupaX6kCvgcgcjxEn.jpg\", \"uploads/onOZVOCtbXCsm9o4nO8MeTwsfVfDhjA3VVwIx6Dv.jpg\"]','Chọn từ Hồng Trứng đặc trưng của Đà Lạt.\r\nTreo khô hồng thủ công bằng gió tự nhiên.\r\nVị hồng trứng giữ nguyên bản, ngọt dịu.\r\nThủ công KHÔNG sử dụng chất bảo quản.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',8,350000,1.00,0,'2024-10-06 00:51:30','2024-10-06 00:51:30'),(38,'Bánh phồng chuối','[\"uploads/Zk1HM8d4em9lMaApjZVuiAUh5yWMdjeHiJqIWA5h.jpg\", \"uploads/VgMlOTPcDHm5ImlNleoB5zBgmtp3zMivEU8YnWIb.jpg\", \"uploads/hLnnDpRoj55pZSl73s0MTOek4fwD3LKaqUQmFCBd.jpg\"]','Bánh phồng tôm chay vị chuối Cà Mau thơm ngon.\r\nXuất xứ: Năm Căn, Cà Mau.\r\nBánh có vị chuối đặc trưng thơm ngon không ngán.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nLàm thủ công HOÀN TOÀN.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,90000,0.50,0,'2024-10-06 00:52:28','2024-10-06 00:52:28'),(39,'Bánh phồng môn','[\"uploads/Ao3b81dXdtEdafA90Yo8z4z26b8sBy4XOQOibnuO.jpg\", \"uploads/wzJIMRmCrPlbGONVdaLTwdqG0n3qylZHrdL6PaPN.jpg\", \"uploads/1n877gZNxirHce9LhaxwiIWam0kod26Cxh9wxU7M.jpg\"]','Bánh phồng tôm chay vị khoai môn thơm ngon.\r\nKhoai môn hay còn gọi là khoai sọ (miền bắc).\r\nXuất xứ: Hàng Vịnh, Năm Căn, Cà Mau.\r\nCó vị môn đặc trưng thơm ngon không ngán.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nLàm thủ công bằng công thức gia truyền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,90000,0.50,0,'2024-10-06 00:53:17','2024-10-06 00:53:17'),(40,'Bánh phồng tôm','[\"uploads/F8vO1KSYMiAOCdDhQg96itYdfeUGQtH5GPjcO8AX.jpg\", \"uploads/oGyTVV2mi3eu8BPDbzlUozPMCXgTQsgIUd3gKTYv.jpg\", \"uploads/aydf1xXv3UXBR714jjc2XNJzGVUvE1qJdWaNCHMS.jpg\"]','Bánh có màu đỏ thẵm do tỷ lệ tôm trong bánh cao.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nLàm mồi nhắm bia rượu hoặc ăn cùng món gỏi, nộm.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,85000,0.50,0,'2024-10-06 00:54:01','2024-10-06 00:54:01'),(41,'Bánh phồng tôm cao cấp','[\"uploads/SPN2bLAGYpHfLu1pb1fE8SgZSP3BvCACoaqNzr01.jpg\", \"uploads/dJeagKVo8ylsCDo0V69kqjeuDN3EBg2cPgNxI6xs.jpg\", \"uploads/6XnioRu9RqjLfduIr628K8vP0LBploqOqEdetUIK.jpg\"]','Loại bánh đặc biệt với tỉ lệ tôm hơn 38%.\r\nĐược làm từ tôm đất sống vùng sinh thái.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nĐặc sản có thể làm quà cho người thân đối tác.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,130000,0.50,0,'2024-10-06 00:54:47','2024-10-06 00:54:47'),(42,'Bánh phồng tôm đặc biệt','[\"uploads/yzDWPBTy1m9hTS9LcZZf25iyEoUWWog0s1zdSjqQ.jpg\", \"uploads/XMYjYWm3BQxc4MDfIAo8RvzTLIqnii3x8soRP3Xh.jpg\", \"uploads/lzH5JFms3YvCecLuYwVN8hHahWuWxMoCwzuOne2F.jpg\"]','Loại bánh đặc biệt với tỉ lệ tôm hơn 50%.\r\nĐược làm từ tôm đất sống vùng sinh thái.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nĐặc sản có thể làm quà cho người thân đối tác.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,160000,0.50,1,'2024-10-06 00:55:39','2024-10-13 16:35:20'),(43,'Khô rắn đồng','[\"uploads/1mXbsUI0WE0BBRIt3GLtFA2tHTIMCwsNGmOglW3L.jpg\", \"uploads/Y8WHZL9d3tzf10LZbWXqwlHhCurfhDk0ikxacXGP.jpg\", \"uploads/agsTWCpuJ8ua4g6Am2nR7kWYBUKzDNixZh9gHxXn.jpg\"]','Khô rắn đồng loại đặc biệt 100% rắn tự nhiên\r\nĐược làm tự rắn nước rắn bông súng vùng An Giang.\r\nĐặc sản trứ danh vùng sông nước.\r\nQuy trình sản xuất sạch sẽ KHÔNG hóa chất.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',17,450000,1.00,0,'2024-10-06 00:56:40','2024-10-06 00:56:40'),(44,'Rượu trái giác','[\"uploads/vS2BQDv0dMuDxh1h8uTW66ERYlX4LRFbFaa96Pcl.jpg\", \"uploads/yZUCTkh3FzGx6MtJxEUOziSnK98MlCiLNITtdpiC.jpg\", \"uploads/JhdGID9eotneLQXvQXfQI164zD29Bq7pCwpwgAfd.jpg\"]','Rượu trái giác đặc sản trứ danh Cà Mau.\r\nLoại chai 30% vol giá 250.000đ\r\nXuất xứ: rừng U Minh hạ, Cà Mau.\r\nSản phẩm cao cấp làm quà đặc biệt.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',17,250000,0.75,0,'2024-10-06 00:57:34','2024-10-06 00:57:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `refund_requests`
--

LOCK TABLES `refund_requests` WRITE;
/*!40000 ALTER TABLE `refund_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(1,2),(2,2),(3,2),(4,2),(5,2),(6,2),(7,2),(8,2),(9,2),(10,2),(11,2),(12,2),(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(9,3),(10,3),(11,3),(12,3),(1,4),(2,4),(3,4),(4,4),(5,4),(6,4),(7,4),(8,4),(9,4),(10,4),(11,4),(12,4),(13,4),(14,4),(15,5),(16,5),(17,5),(18,5),(1,6),(2,6),(3,6),(4,6),(5,6),(6,6),(7,6),(8,6),(9,6),(10,6),(11,6),(12,6),(13,6),(14,6),(15,6),(16,6),(17,6),(18,6),(19,6),(20,6),(21,6),(22,6),(23,6),(24,6),(25,6);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'guest','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(2,'normal_user','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(3,'loyal_customer','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(4,'affiliate_marketer','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(5,'staff','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL),(6,'admin','api','2024-09-17 13:01:33','2024-09-17 13:01:33',NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Dương Hoài Ân','tempcontact2024@gmail.com',NULL,NULL,NULL,'$2y$12$STxn9lHqwVkaOSNTkWcL7euCDdG0hShoXhTDEcaHvHE/OzGKb5iuO',NULL,'2024-09-17 13:03:41','2024-10-12 03:49:31',NULL,NULL,0,'hoaian2646',0.00,0.00,0,'0976146523','2024-10-12 03:49:31'),(13,'Lê Thị Ngọc Hân','duonghoaian1978@gmail.com',NULL,NULL,NULL,'$2y$12$ldoRNauPcI8EZF0HZOGO7.ObijORqRewXiJp/FHVZ08Xr2A5ROfIO',NULL,'2024-10-12 03:19:00','2024-10-12 03:53:07',NULL,NULL,0,NULL,0.00,0.00,0,NULL,'2024-10-12 03:53:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-13 15:29:48
