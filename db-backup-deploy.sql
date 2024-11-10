-- MySQL dump 10.13  Distrib 8.4.3, for Linux (x86_64)
--
-- Host: localhost    Database: ct550_db
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `affiliate_links`
--

DROP TABLE IF EXISTS `affiliate_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_links` (
  `affiliate_link_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `affiliate_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`affiliate_link_id`),
  KEY `affiliate_links_affiliate_user_id_foreign` (`affiliate_user_id`),
  KEY `affiliate_links_product_id_foreign` (`product_id`),
  CONSTRAINT `affiliate_links_affiliate_user_id_foreign` FOREIGN KEY (`affiliate_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `affiliate_links_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate_links`
--

LOCK TABLES `affiliate_links` WRITE;
/*!40000 ALTER TABLE `affiliate_links` DISABLE KEYS */;
INSERT INTO `affiliate_links` VALUES (13,35,1,'https://client.dacsancamau.com:3001/product/detail/1?ref=35','2024-11-10 19:42:40','2024-11-10 19:42:40');
/*!40000 ALTER TABLE `affiliate_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate_requests`
--

DROP TABLE IF EXISTS `affiliate_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_requests` (
  `affiliate_request_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `social_media_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`affiliate_request_id`),
  KEY `affiliate_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `affiliate_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate_requests`
--

LOCK TABLES `affiliate_requests` WRITE;
/*!40000 ALTER TABLE `affiliate_requests` DISABLE KEYS */;
INSERT INTO `affiliate_requests` VALUES (23,35,'approved','https://www.facebook.com/profile.php?id=100031101393923',NULL,'2024-11-10 19:42:07','2024-11-10 19:42:11');
/*!40000 ALTER TABLE `affiliate_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate_sales`
--

DROP TABLE IF EXISTS `affiliate_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_sales` (
  `affiliate_sale_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `commission_amount` int NOT NULL,
  `order_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  PRIMARY KEY (`affiliate_sale_id`),
  KEY `affiliate_sales_affiliate_user_id_foreign` (`affiliate_user_id`),
  KEY `affiliate_sales_product_id_foreign` (`product_id`),
  KEY `affiliate_sales_order_id_foreign` (`order_id`),
  CONSTRAINT `affiliate_sales_affiliate_user_id_foreign` FOREIGN KEY (`affiliate_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `affiliate_sales_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `affiliate_sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate_sales`
--

LOCK TABLES `affiliate_sales` WRITE;
/*!40000 ALTER TABLE `affiliate_sales` DISABLE KEYS */;
INSERT INTO `affiliate_sales` VALUES (39,35,1,232,80000,'done','2024-11-10 19:43:56','2024-11-10 19:44:09',5.00);
/*!40000 ALTER TABLE `affiliate_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate_wallets`
--

DROP TABLE IF EXISTS `affiliate_wallets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_wallets` (
  `wallet_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_user_id` bigint unsigned NOT NULL,
  `balance` int NOT NULL DEFAULT '0',
  `income` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`wallet_id`),
  KEY `affiliate_wallets_affiliate_user_id_foreign` (`affiliate_user_id`),
  CONSTRAINT `affiliate_wallets_affiliate_user_id_foreign` FOREIGN KEY (`affiliate_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate_wallets`
--

LOCK TABLES `affiliate_wallets` WRITE;
/*!40000 ALTER TABLE `affiliate_wallets` DISABLE KEYS */;
INSERT INTO `affiliate_wallets` VALUES (16,35,30000,50000,'2024-11-10 19:42:11','2024-11-10 19:59:37');
/*!40000 ALTER TABLE `affiliate_wallets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `affiliate_withdrawals`
--

DROP TABLE IF EXISTS `affiliate_withdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `affiliate_withdrawals` (
  `withdrawal_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `affiliate_user_id` bigint unsigned NOT NULL,
  `amount` int NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`withdrawal_id`),
  KEY `affiliate_withdrawals_affiliate_user_id_foreign` (`affiliate_user_id`),
  CONSTRAINT `affiliate_withdrawals_affiliate_user_id_foreign` FOREIGN KEY (`affiliate_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `affiliate_withdrawals`
--

LOCK TABLES `affiliate_withdrawals` WRITE;
/*!40000 ALTER TABLE `affiliate_withdrawals` DISABLE KEYS */;
INSERT INTO `affiliate_withdrawals` VALUES (15,35,50000,'done','Ngân hàng TMCP Ngoại Thương Việt Nam','12345678','Duong Hoai An','2024-11-10 19:44:42','2024-11-10 19:59:37');
/*!40000 ALTER TABLE `affiliate_withdrawals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batch_promotions`
--

DROP TABLE IF EXISTS `batch_promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batch_promotions` (
  `batch_promotion_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `discount_price` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`batch_promotion_id`),
  KEY `batch_promotions_promotion_id_foreign` (`promotion_id`),
  KEY `batch_promotions_batch_id_foreign` (`batch_id`),
  CONSTRAINT `batch_promotions_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`batch_id`) ON DELETE CASCADE,
  CONSTRAINT `batch_promotions_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_promotions`
--

LOCK TABLES `batch_promotions` WRITE;
/*!40000 ALTER TABLE `batch_promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `batch_promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `batches`
--

DROP TABLE IF EXISTS `batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batches` (
  `batch_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Active',
  `entry_date` timestamp NOT NULL,
  `expiry_date` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `batch_cost` int DEFAULT NULL,
  `sold_quantity` int DEFAULT NULL,
  PRIMARY KEY (`batch_id`),
  KEY `batches_product_id_foreign` (`product_id`),
  KEY `batches_user_id_foreign` (`user_id`),
  CONSTRAINT `batches_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `batches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batches`
--

LOCK TABLES `batches` WRITE;
/*!40000 ALTER TABLE `batches` DISABLE KEYS */;
INSERT INTO `batches` VALUES (5,1,1,93,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 15:55:02','2024-11-10 19:43:55',25000000,7),(6,1,2,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:20:55','2024-11-10 18:03:40',2600000,1),(7,1,3,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:21:32','2024-11-10 19:43:55',50000000,2),(8,1,4,97,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:22:10','2024-11-10 19:25:14',18000000,3),(9,1,5,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:23:57','2024-11-10 18:54:29',28000000,2),(10,1,6,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:27:56','2024-10-13 16:27:56',50000000,0),(11,1,7,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:28:25','2024-10-31 08:43:37',40000000,0),(12,1,8,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:03','2024-10-13 16:29:03',30000000,0),(13,1,9,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:36','2024-11-10 19:14:16',12000000,1),(14,1,10,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:29:54','2024-11-10 19:14:16',30000000,1),(15,1,11,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:24','2024-10-13 16:30:24',52000000,0),(16,1,12,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:39','2024-10-13 16:30:39',50000000,0),(17,1,13,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:30:54','2024-10-13 16:30:54',16000000,0),(18,1,16,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:31:11','2024-10-13 16:31:11',16000000,0),(19,1,20,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:10','2024-11-10 19:10:47',150000000,1),(20,1,21,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:31','2024-11-10 17:52:09',36000000,2),(21,1,22,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:32:47','2024-11-10 17:52:09',60000000,1),(22,1,38,98,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:33:44','2024-11-10 19:40:03',8000000,2),(23,1,39,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:24','2024-11-10 18:31:21',8000000,1),(24,1,40,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:36','2024-11-10 18:31:21',7000000,1),(25,1,41,99,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:34:52','2024-11-10 19:20:24',12000000,1),(26,1,42,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:18','2024-10-13 16:35:18',14000000,0),(27,1,14,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:45','2024-10-13 16:35:45',18000000,0),(28,1,15,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:35:55','2024-10-13 16:35:55',28000000,0),(29,1,17,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:09','2024-10-13 16:36:09',18000000,0),(30,1,18,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:24','2024-10-13 16:36:24',18000000,0),(31,1,19,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:36:32','2024-10-13 16:36:32',18000000,0),(32,1,35,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:04','2024-10-13 16:43:04',50000000,0),(33,1,36,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:12','2024-10-13 16:43:12',50000000,0),(34,1,27,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:30','2024-10-13 16:43:30',85000000,0),(35,1,28,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:47','2024-10-13 16:43:47',20000000,0),(36,1,29,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:43:59','2024-10-13 16:43:59',52000000,0),(37,1,37,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:25','2024-10-13 16:44:25',30000000,0),(38,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:47','2024-10-13 16:44:47',15000000,0),(39,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:44:53','2024-10-13 16:44:53',22000000,0),(40,1,30,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:01','2024-10-13 16:45:01',25000000,0),(41,1,25,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:19','2024-10-13 16:45:19',15000000,0),(42,1,26,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:45:38','2024-10-13 16:45:38',15000000,0),(43,1,43,100,'Active','2024-10-12 00:00:00','2025-01-31 00:00:00','2024-10-13 16:46:09','2024-10-13 16:46:09',42000000,0),(48,1,1,10,'Expiring Soon','2024-11-02 00:00:00','2024-11-28 00:00:00','2024-10-13 14:00:44','2024-11-09 19:59:41',3000000,0);
/*!40000 ALTER TABLE `batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `cart_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` int NOT NULL,
  `total_weight` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_promotion_batch_applied` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cart_id`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `category_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (2,'Khô cá Cà Mau','uploads/7JqgVyDW1LLerZoGjVIxNBX7OnNIBr0l1VH5Xw6S.jpg','2024-09-17 14:46:21','2024-09-17 14:46:21'),(3,'Tôm Khô Cà Mau','uploads/Ev6plxMwSDlSdjdjRCM9bBkyYyMfOd5GMc4YqckX.jpg','2024-09-17 14:51:08','2024-09-17 14:51:08'),(4,'Bánh phồng tôm','uploads/G0IcKTKpKxAYAR9Cp0fJ90VleuALsNrVenwkswj9.jpg','2024-09-17 14:52:19','2024-09-17 14:52:19'),(5,'Khô 1 nắng','uploads/WyQI4FkgDIHsTgmw37Wwut5w8U2IGsY4hrwdWFh0.jpg','2024-09-17 14:53:03','2024-09-17 14:53:03'),(6,'Mực khô Cà Mau','uploads/ivvg9lWGWlorUf1UI7NZ38CubWfGrQHtFusbut2o.jpg','2024-09-17 14:53:34','2024-09-17 14:53:34'),(7,'Mật ong Cà Mau','uploads/bz5fjqEI9JBR7DJqDx4uscZ6R3TRLrSS84ZsCLTe.jpg','2024-09-17 14:54:20','2024-09-17 14:54:20'),(8,'Bánh-Kẹo','uploads/cf2AO9Jj69TqclRJ8wEhgJoorQDuWKf1NG1BktV2.jpg','2024-09-17 14:54:58','2024-09-17 14:54:58'),(9,'Mắm Cà Mau','uploads/NGh2yNkoQdMzEp2u94crmftzEZAg9G8IOzaDvsUq.jpg','2024-09-17 14:55:56','2024-09-17 14:55:56'),(10,'Nem-chả','uploads/RCzDEUZHzoL5SYBkdydw3nLmBZtvGdmKrGHZjlny.jpg','2024-09-17 14:57:03','2024-09-17 14:57:03'),(17,'Đặc sản khác','uploads/m4xU0RQBIs79HlQHEIUshsiCgCmI3xa7Grq3KUnc.jpg','2024-09-17 15:07:30','2024-09-17 15:07:30');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `commissions`
--

DROP TABLE IF EXISTS `commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `commissions` (
  `commission_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `commission_rate` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`commission_id`),
  KEY `commissions_product_id_foreign` (`product_id`),
  CONSTRAINT `commissions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `commissions`
--

LOCK TABLES `commissions` WRITE;
/*!40000 ALTER TABLE `commissions` DISABLE KEYS */;
INSERT INTO `commissions` VALUES (13,1,5.00,'2024-11-10 19:42:28','2024-11-10 19:42:28');
/*!40000 ALTER TABLE `commissions` ENABLE KEYS */;
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
  `favorite_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`favorite_id`),
  KEY `favorites_product_id_foreign` (`product_id`),
  KEY `favorites_user_id_foreign` (`user_id`),
  CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (29,36,3,'2024-11-10 18:00:13','2024-11-10 18:00:13'),(30,36,4,'2024-11-10 18:00:14','2024-11-10 18:00:14');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `message_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `products` json DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_receiver_id_foreign` (`receiver_id`),
  CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (130,37,1,'Xin chào',NULL,'2024-11-10 17:40:35','2024-11-10 17:36:00','2024-11-10 17:40:35'),(131,1,37,'Chào bạn. Tôi có thể giúp gì cho bạn ạ.',NULL,'2024-11-10 17:41:13','2024-11-10 17:41:04','2024-11-10 17:41:13'),(132,37,1,'Tôi thích ăn mực thì nên mua loại nào ngon ạ',NULL,'2024-11-10 17:41:42','2024-11-10 17:41:41','2024-11-10 17:41:42'),(133,1,37,'Bên em có một số sản phẩm về mực đây ạ. Mời anh xem ạ','[{\"product_id\": 36, \"product_img\": \"uploads/p3BTvynyDJRMUP92B92R78D0iQ62DweB8j9RhPCz.jpg\", \"product_name\": \"Khô mực nấu súp\", \"product_price\": 550000}, {\"product_id\": 35, \"product_img\": \"uploads/H1iwww6KjSDPHzx88lLfALaQPTTPGFOK6OXZ7JsH.jpg\", \"product_name\": \"Khô mực loại nhỏ\", \"product_price\": 550000}, {\"product_id\": 34, \"product_img\": \"uploads/whhWhWwwL32XLFHpW8YgSCCRpDJBbwdJiwGxJNDC.jpg\", \"product_name\": \"Khô mực loại 1\", \"product_price\": 1450000}]','2024-11-10 17:42:16','2024-11-10 17:42:16','2024-11-10 17:42:16'),(134,37,1,'Dạ em cảm ơn shop nhiều ạ',NULL,'2024-11-10 17:42:44','2024-11-10 17:42:44','2024-11-10 17:42:44'),(135,36,1,'Dạ shop ơi',NULL,'2024-11-10 18:00:34','2024-11-10 18:00:26','2024-11-10 18:00:34'),(136,36,1,'Cho em hỏi',NULL,'2024-11-10 18:00:34','2024-11-10 18:00:29','2024-11-10 18:00:34'),(137,1,36,'Chào bạn. Bạn muốn tư vấn sản phẩm nào ạ',NULL,'2024-11-10 18:01:01','2024-11-10 18:00:48','2024-11-10 18:01:01'),(138,36,1,'Bên shop có cá sặc không ạ',NULL,'2024-11-10 18:01:23','2024-11-10 18:01:22','2024-11-10 18:01:23'),(139,1,36,'Có cá sặc nha bạn ơi. Bạn có thể tham khảo ạ','[{\"product_id\": 2, \"product_img\": \"uploads/YJpT8X1HQZCLii0pQI3qpgzIBiDF6gGKWGHYa6rd.jpg\", \"product_name\": \"Khô cá sặc\", \"product_price\": 390000}]','2024-11-10 18:01:49','2024-11-10 18:01:48','2024-11-10 18:01:49'),(140,36,1,'Dạ em cảm ơn shop ạ',NULL,'2024-11-10 18:01:57','2024-11-10 18:01:56','2024-11-10 18:01:57'),(141,35,1,'Xin chào',NULL,'2024-11-10 18:13:53','2024-11-10 18:13:50','2024-11-10 18:13:53'),(142,1,35,'Em có thể giúp gì cho anh ạ',NULL,'2024-11-10 18:14:29','2024-11-10 18:14:05','2024-11-10 18:14:29'),(143,35,1,'Có cá chạch đồng không ạ',NULL,'2024-11-10 18:14:54','2024-11-10 18:14:53','2024-11-10 18:14:54'),(144,1,35,'Có nhé bạn',NULL,'2024-11-10 18:15:49','2024-11-10 18:14:58','2024-11-10 18:15:49'),(145,1,35,'Đây là các chạch đồng, bạn có thể tham khảo thử ạ','[{\"product_id\": 3, \"product_img\": \"uploads/sUOejlMdPDhPm2OYf3kSaw7at5voqg1kZWOYArTH.jpg\", \"product_name\": \"Khô cá chạch đồng\", \"product_price\": 700000}]','2024-11-10 18:15:49','2024-11-10 18:15:38','2024-11-10 18:15:49'),(146,35,1,'Em cảm ơn nhiều ạ',NULL,'2024-11-10 18:17:51','2024-11-10 18:17:50','2024-11-10 18:17:51'),(147,1,35,'Không có chi ạ',NULL,'2024-11-10 18:17:59','2024-11-10 18:17:58','2024-11-10 18:17:59'),(148,34,1,'Xin chào ạ',NULL,'2024-11-10 18:23:47','2024-11-10 18:23:42','2024-11-10 18:23:47'),(149,1,34,'Bạn cần giúp gì ạ',NULL,'2024-11-10 18:24:00','2024-11-10 18:23:54','2024-11-10 18:24:00'),(150,34,1,'Có cá cơm không ạ',NULL,'2024-11-10 18:24:19','2024-11-10 18:24:17','2024-11-10 18:24:19'),(151,1,34,'Cá cơm đây nhé bạn','[{\"product_id\": 4, \"product_img\": \"uploads/uim58xjhaZ6evVGrsE3A98vJUh6eZEKQ4AlXVcZ7.jpg\", \"product_name\": \"Khô cá cơm\", \"product_price\": 220000}]','2024-11-10 18:24:33','2024-11-10 18:24:32','2024-11-10 18:24:33'),(152,34,1,'Dạ em cảm ơn nhiều ạ',NULL,'2024-11-10 18:24:42','2024-11-10 18:24:41','2024-11-10 18:24:42'),(153,29,1,'Xin chào',NULL,'2024-11-10 19:21:04','2024-11-10 19:21:01','2024-11-10 19:21:04'),(154,1,29,'Bạn cần giúp gì',NULL,'2024-11-10 19:21:11','2024-11-10 19:21:10','2024-11-10 19:21:11'),(155,29,1,'Có mắm không ạ',NULL,'2024-11-10 19:21:27','2024-11-10 19:21:26','2024-11-10 19:21:27'),(156,1,29,'Mắm đây bạn nhé','[{\"product_id\": 30, \"product_img\": \"uploads/qNNWb15Q73ci9C8Bal9xpVCGY8kCxgXLoU19g7P3.jpg\", \"product_name\": \"Mắm ba khía\", \"product_price\": 175000}, {\"product_id\": 31, \"product_img\": \"uploads/b6ugstjo70opUExBKRwOBuIj6QfA5Mw0k3dqKdHm.jpg\", \"product_name\": \"Mắm cá lóc\", \"product_price\": 250000}, {\"product_id\": 32, \"product_img\": \"uploads/jw6j5bSJga3eQwQppgKLjHxxRVI0ruY5qLqQRTfU.jpg\", \"product_name\": \"Mắm tép Cà Mau\", \"product_price\": 270000}]','2024-11-10 19:21:48','2024-11-10 19:21:48','2024-11-10 19:21:48'),(157,29,1,'Cảm ơn ạ',NULL,'2024-11-10 19:21:57','2024-11-10 19:21:56','2024-11-10 19:21:57');
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
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_reset_tokens_table',1),(3,'2019_08_19_000000_create_failed_jobs_table',1),(4,'2019_12_14_000001_create_personal_access_tokens_table',1),(5,'2024_06_27_103432_modify_users_table',1),(6,'2024_07_11_100850_add_google_fields_to_users_table',1),(7,'2024_07_28_123320_create_category_table',1),(8,'2024_07_29_214830_create_products_table',1),(9,'2024_07_30_185504_create_carts_table',1),(10,'2024_07_30_185612_create_orders_table',1),(11,'2024_07_30_185823_create_order_detail_table',1),(12,'2024_07_30_185911_create_favorites_table',1),(13,'2024_07_30_185958_create_reviews_table',1),(14,'2024_08_12_155137_modify_orders_table',1),(15,'2024_08_12_160538_modify_order_detail_table',1),(16,'2024_08_12_161315_modify_user_table',1),(17,'2024_08_16_112553_create_permission_tables',1),(18,'2024_08_16_115625_remove_roles_column_from_users_table',1),(19,'2024_08_16_120053_update_favorites_table',1),(20,'2024_08_16_125224_update_orders_table',1),(21,'2024_08_17_134127_add_description_to_roles_table',1),(22,'2024_08_17_173449_add_description_to_permissions_table',1),(23,'2024_08_19_104748_add_weight_to_products_table',1),(24,'2024_08_19_105424_create_batches_table',1),(25,'2024_08_19_112950_delete_product_quantity_from_products_table',1),(26,'2024_08_19_123147_add_user_id_to_batches_table',1),(27,'2024_08_19_124910_add_batch_id_to_order_detail_table',1),(28,'2024_08_19_155410_add_import_cost_and_sold_quantity_to_batches_table',1),(29,'2024_08_20_112241_modify_batches_table',1),(30,'2024_08_26_135129_create_order_detail_batches_table',1),(31,'2024_08_26_145902_delete_batch_id_from_order_detail_table',1),(32,'2024_08_27_134228_create_notifications_table',1),(33,'2024_08_28_233619_modify_notifications_table',1),(34,'2024_08_30_163355_add_pay_method_to_orders_table',1),(35,'2024_08_31_155114_create_refund_requests_table',1),(36,'2024_09_02_143837_create_promotions_table',1),(37,'2024_09_02_144004_create_product_promotions_table',1),(38,'2024_09_02_230854_create_messages_table',1),(39,'2024_09_14_153924_modify_carts_table',1),(40,'2024_09_16_230705_modify_image_column_in_users_table',1),(42,'2024_09_17_140920_modify_categories_table',2),(47,'2024_09_18_192728_create_affiliate_links_table',3),(48,'2024_09_18_193242_create_affiliate_sales_table',3),(49,'2024_09_18_193440_create_commissions_table',3),(50,'2024_09_18_193655_create_affiliate_requests_table',3),(51,'2024_09_19_223615_modify_user_table',4),(52,'2024_09_20_103616_modify_users_table',5),(53,'2024_09_24_131835_modify_affiliate_sales_table',6),(54,'2024_09_24_204950_modify_products_table',7),(55,'2024_09_24_214405_modify_affiliate_sales',8),(56,'2024_09_24_224916_modify_affiliate_sales',9),(57,'2024_09_25_205544_create_affiliate_wallets_table',10),(58,'2024_09_25_205908_create_affiliate_withdrawals_table',10),(59,'2024_09_27_000312_add_read_at_to_messages_table',11),(61,'2024_10_07_134106_modify_batches_tables',12),(62,'2024_10_11_213649_add_last_password_reset_to_users_table',13),(63,'2024_10_14_154609_modify_affiliate_requests_table',14),(64,'2024_10_27_124441_modify_review_tables',15),(65,'2024_11_02_155858_add_apply_to_column_to_promotions_tables',16),(66,'2024_11_02_160903_create_batch_promotions_table',17),(67,'2024_11_05_191502_add_is_promotion_batch_applied_to_carts_table',18),(68,'2024_11_07_140745_add_products_to_messages_table',19),(69,'2024_11_07_225044_add_point_expiration_date_to_users_table',20),(70,'2024_11_10_155812_modify_reviews_table',21),(71,'2024_11_10_195555_modify_affiliate_wallets_table',22);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (6,'App\\Models\\User',1),(2,'App\\Models\\User',27),(2,'App\\Models\\User',28),(2,'App\\Models\\User',29),(2,'App\\Models\\User',30),(2,'App\\Models\\User',31),(2,'App\\Models\\User',32),(2,'App\\Models\\User',33),(2,'App\\Models\\User',34),(2,'App\\Models\\User',35),(4,'App\\Models\\User',35),(2,'App\\Models\\User',36),(2,'App\\Models\\User',37),(5,'App\\Models\\User',38),(5,'App\\Models\\User',39);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `message` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('user','admin') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin_read` tinyint(1) NOT NULL DEFAULT '0',
  `is_user_read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `notifications_user_id_foreign` (`user_id`),
  CONSTRAINT `notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=246 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES (233,37,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 17:50:59','2024-11-10 17:52:52',1,1),(234,37,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 17:52:44','2024-11-10 17:52:55',1,1),(235,36,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 18:03:41','2024-11-10 18:31:31',1,1),(236,36,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 18:05:19','2024-11-10 18:31:31',1,1),(237,34,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 18:26:44','2024-11-10 18:31:31',1,1),(238,34,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 18:31:22','2024-11-10 18:51:46',1,1),(239,33,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 18:54:29','2024-11-10 19:10:52',1,1),(240,33,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:10:48','2024-11-10 19:11:12',1,1),(241,32,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:14:16','2024-11-10 19:20:39',1,1),(242,29,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:20:24','2024-11-10 19:20:39',1,1),(243,29,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:25:14','2024-11-10 20:03:44',1,1),(244,31,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:40:03','2024-11-10 20:03:44',1,0),(245,37,'Đơn hàng đã đặt thành công','order','admin','2024-11-10 19:43:56','2024-11-10 20:03:44',1,0);
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_detail`
--

DROP TABLE IF EXISTS `order_detail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_detail` (
  `order_detail_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `quantity` int NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_cost_detail` int NOT NULL,
  PRIMARY KEY (`order_detail_id`),
  KEY `order_detail_order_id_foreign` (`order_id`),
  KEY `order_detail_product_id_foreign` (`product_id`),
  CONSTRAINT `order_detail_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `order_detail_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_detail`
--

LOCK TABLES `order_detail` WRITE;
/*!40000 ALTER TABLE `order_detail` DISABLE KEYS */;
INSERT INTO `order_detail` VALUES (273,1,220,21,'2024-11-10 17:50:58','2024-11-10 17:50:58',400000),(274,1,220,4,'2024-11-10 17:50:58','2024-11-10 17:50:58',220000),(275,1,221,21,'2024-11-10 17:52:09','2024-11-10 17:52:09',400000),(276,1,221,22,'2024-11-10 17:52:09','2024-11-10 17:52:09',640000),(277,1,222,2,'2024-11-10 18:03:40','2024-11-10 18:03:40',390000),(278,2,223,1,'2024-11-10 18:05:19','2024-11-10 18:05:19',640000),(279,1,224,4,'2024-11-10 18:26:43','2024-11-10 18:26:43',220000),(280,1,224,3,'2024-11-10 18:26:43','2024-11-10 18:26:43',700000),(281,1,225,40,'2024-11-10 18:31:21','2024-11-10 18:31:21',85000),(282,1,225,39,'2024-11-10 18:31:21','2024-11-10 18:31:21',90000),(283,2,226,5,'2024-11-10 18:54:29','2024-11-10 18:54:29',640000),(284,1,227,20,'2024-11-10 19:10:47','2024-11-10 19:10:47',1600000),(285,1,228,10,'2024-11-10 19:14:16','2024-11-10 19:14:16',390000),(286,1,228,9,'2024-11-10 19:14:16','2024-11-10 19:14:16',180000),(287,1,229,41,'2024-11-10 19:20:24','2024-11-10 19:20:24',130000),(288,1,230,4,'2024-11-10 19:25:14','2024-11-10 19:25:14',198000),(289,2,231,38,'2024-11-10 19:40:03','2024-11-10 19:40:03',180000),(290,5,232,1,'2024-11-10 19:43:55','2024-11-10 19:43:55',1440000),(291,1,232,3,'2024-11-10 19:43:55','2024-11-10 19:43:55',630000);
/*!40000 ALTER TABLE `order_detail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_detail_batches`
--

DROP TABLE IF EXISTS `order_detail_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_detail_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_detail_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_detail_batches_order_detail_id_foreign` (`order_detail_id`),
  KEY `order_detail_batches_batch_id_foreign` (`batch_id`),
  CONSTRAINT `order_detail_batches_batch_id_foreign` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`batch_id`) ON DELETE CASCADE,
  CONSTRAINT `order_detail_batches_order_detail_id_foreign` FOREIGN KEY (`order_detail_id`) REFERENCES `order_detail` (`order_detail_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=293 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_detail_batches`
--

LOCK TABLES `order_detail_batches` WRITE;
/*!40000 ALTER TABLE `order_detail_batches` DISABLE KEYS */;
INSERT INTO `order_detail_batches` VALUES (274,273,20,1,NULL,NULL),(275,274,8,1,NULL,NULL),(276,275,20,1,NULL,NULL),(277,276,21,1,NULL,NULL),(278,277,6,1,NULL,NULL),(279,278,5,2,NULL,NULL),(280,279,8,1,NULL,NULL),(281,280,7,1,NULL,NULL),(282,281,24,1,NULL,NULL),(283,282,23,1,NULL,NULL),(284,283,9,2,NULL,NULL),(285,284,19,1,NULL,NULL),(286,285,14,1,NULL,NULL),(287,286,13,1,NULL,NULL),(288,287,25,1,NULL,NULL),(289,288,8,1,NULL,NULL),(290,289,22,2,NULL,NULL),(291,290,5,5,NULL,NULL),(292,291,7,1,NULL,NULL);
/*!40000 ALTER TABLE `order_detail_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bill_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_address` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `paid` tinyint(1) NOT NULL DEFAULT '0',
  `total_cost` int NOT NULL,
  `pay_method` enum('cod','vnpay') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `shipping_fee` int NOT NULL,
  `point_used_order` int NOT NULL,
  PRIMARY KEY (`order_id`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=233 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (220,'2024111017505237','delivered',37,'{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tăng Duy Tân\", \"phone\": \"0902888334\", \"commue\": \"Phường 13\", \"address\": \"12 Tôn Đản\", \"district\": \"Quận 4\"}','2024-11-10 17:50:58','2024-11-10 17:51:26',0,650000,'cod',30000,0),(221,'2024111017515637','delivered',37,'{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tăng Duy Tân\", \"phone\": \"0902888334\", \"commue\": \"Phường 13\", \"address\": \"12 Tôn Đản\", \"district\": \"Quận 4\"}','2024-11-10 17:52:09','2024-11-10 17:53:23',1,1070000,'vnpay',30000,0),(222,'2024111018033836','delivered',36,'{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tiểu Long Nữ\", \"phone\": \"0336363636\", \"commue\": \"Phường Bến Nghé\", \"address\": \"số 4 Đ. Hồ Tùng Mậu\", \"district\": \"Quận 1\"}','2024-11-10 18:03:40','2024-11-10 18:03:53',0,420000,'cod',30000,0),(223,'2024111018051636','delivered',36,'{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tiểu Long Nữ\", \"phone\": \"0336363636\", \"commue\": \"Phường Bến Nghé\", \"address\": \"số 4 Đ. Hồ Tùng Mậu\", \"district\": \"Quận 1\"}','2024-11-10 18:05:19','2024-11-10 18:05:29',0,670000,'cod',30000,0),(224,'2024111018263534','delivered',34,'{\"city\": \"Tỉnh Kiên Giang\", \"name\": \"Trần Thái Đăng\", \"phone\": \"0911636663\", \"commue\": \"Phường Vĩnh Thanh\", \"address\": \"225 Điện Biên Phủ\", \"district\": \"Thành phố Rạch Giá\"}','2024-11-10 18:26:43','2024-11-10 18:26:59',0,950000,'cod',30000,0),(225,'2024111018311834','delivered',34,'{\"city\": \"Tỉnh Kiên Giang\", \"name\": \"Trần Thái Đăng\", \"phone\": \"0911636663\", \"commue\": \"Phường Vĩnh Thanh\", \"address\": \"225 Điện Biên Phủ\", \"district\": \"Thành phố Rạch Giá\"}','2024-11-10 18:31:21','2024-11-10 18:31:40',0,205000,'cod',30000,0),(226,'2024111018542633','delivered',33,'{\"city\": \"Tỉnh Đồng Tháp\", \"name\": \"Phạm Viết Thanh\", \"phone\": \"0947852560\", \"commue\": \"Xã Tân Nghĩa\", \"address\": \"số 83\", \"district\": \"Huyện Cao Lãnh\"}','2024-11-10 18:54:29','2024-11-10 18:55:15',0,675000,'cod',35000,0),(227,'2024111019104533','delivered',33,'{\"city\": \"Tỉnh Đồng Tháp\", \"name\": \"Phạm Viết Thanh\", \"phone\": \"0947852560\", \"commue\": \"Xã Tân Nghĩa\", \"address\": \"số 83\", \"district\": \"Huyện Cao Lãnh\"}','2024-11-10 19:10:47','2024-11-10 19:11:00',0,1635000,'cod',35000,0),(228,'2024111019141232','delivered',32,'{\"city\": \"Tỉnh Đồng Tháp\", \"name\": \"Phạm Nguyễn Gia Bảo\", \"phone\": \"0944131321\", \"commue\": \"Xã Tân Hòa\", \"address\": \"Hòa bình\", \"district\": \"Huyện Lai Vung\"}','2024-11-10 19:14:16','2024-11-10 19:14:29',0,600000,'cod',30000,0),(229,'2024111019202129','delivered',29,'{\"city\": \"Tỉnh Cà Mau\", \"name\": \"Dương Hoài Ân\", \"phone\": \"0943808107\", \"commue\": \"Xã Khánh Tiến\", \"address\": \"ấp 10\", \"district\": \"Huyện U Minh\"}','2024-11-10 19:20:24','2024-11-10 19:20:33',0,165000,'cod',35000,0),(230,'2024111019251129','delivered',29,'{\"city\": \"Tỉnh Cà Mau\", \"name\": \"Dương Hoài Ân\", \"phone\": \"0943808107\", \"commue\": \"Xã Khánh Tiến\", \"address\": \"ấp 10\", \"district\": \"Huyện U Minh\"}','2024-11-10 19:25:14','2024-11-10 19:25:35',0,233000,'cod',35000,0),(231,'2024111019394531','delivered',31,'{\"city\": \"Thành phố Cần Thơ\", \"name\": \"Lê Thanh Nam\", \"phone\": \"0944393939\", \"commue\": \"Phường Xuân Khánh\", \"address\": \"số 94\", \"district\": \"Quận Ninh Kiều\"}','2024-11-10 19:40:03','2024-11-10 19:40:14',0,196500,'cod',16500,0),(232,'2024111019435237','delivered',37,'{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tăng Duy Tân\", \"phone\": \"0902888334\", \"commue\": \"Phường 13\", \"address\": \"12 Tôn Đản\", \"district\": \"Quận 4\"}','2024-11-10 19:43:55','2024-11-10 19:44:09',0,2100000,'cod',30000,0);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
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
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (3,'add to cart','api','2024-09-17 13:01:33','2024-10-18 13:23:11','Thêm sản phẩm vào giỏ hàng'),(5,'order','api','2024-09-17 13:01:33','2024-10-18 21:51:41','Đặt hàng'),(7,'cancel order','api','2024-09-17 13:01:33','2024-10-18 13:23:45','Hủy đơn hàng'),(8,'message store','api','2024-09-17 13:01:33','2024-10-18 13:23:56','Gửi tin nhắn'),(9,'review products','api','2024-09-17 13:01:33','2024-10-18 13:24:07','Đánh giá sản phẩm'),(10,'update profile','api','2024-09-17 13:01:33','2024-10-18 13:24:21','Cập nhật thông tin tài khoản'),(12,'affiliate register','api','2024-09-17 13:01:33','2024-10-18 21:52:24','Đăng ký tiếp thị'),(15,'manage products','api','2024-09-17 13:01:33','2024-10-18 13:25:07','Quản lý sản phẩm'),(17,'manage orders','api','2024-09-17 13:01:33','2024-10-18 13:25:26','Quản lý đơn hàng'),(18,'view sales reports','api','2024-09-17 13:01:33','2024-10-18 13:25:35','Xem thống kê'),(19,'manage customers','api','2024-09-17 13:01:33','2024-10-18 13:25:41','Quản lý khách hàng'),(20,'manage reviews','api','2024-09-17 13:01:33','2024-10-18 13:25:52','Quản lý đánh giá'),(22,'manage promotions','api','2024-09-17 13:01:33','2024-10-18 13:26:16','Quản lý khuyến mãi'),(24,'manage staff','api','2024-09-17 13:01:33','2024-10-18 13:26:34','Quản lý nhân viên'),(25,'manage affiliate marketers','api','2024-09-17 13:01:33','2024-10-18 13:26:47','Quản lý người tiếp thị'),(26,'increase cart','api','2024-10-18 21:10:42','2024-10-18 21:10:42','Tăng số lượng sản phẩm giỏ hàng'),(27,'decrease cart','api','2024-10-18 21:14:32','2024-10-18 21:14:32','Giảm số lượng sản phẩm giỏ hàng'),(28,'delete cart','api','2024-10-18 21:16:50','2024-10-18 21:16:50','Xóa sản phẩm trong giỏ hàng'),(29,'favorite product','api','2024-10-18 21:26:28','2024-10-18 21:26:28','Sản phẩm yêu thích'),(30,'update password','api','2024-10-18 21:35:11','2024-10-18 21:35:11','Cập nhật mật khẩu'),(31,'manage address','api','2024-10-18 21:43:02','2024-10-18 21:43:35','Quản lý địa chỉ nhận hàng'),(32,'manage commission','api','2024-10-19 15:22:41','2024-10-19 15:22:41','Quản lý hoa hồng'),(33,'get affiliate link','api','2024-10-19 15:24:11','2024-10-19 15:24:11','Lấy link liên kết'),(34,'create withdrawal request','api','2024-10-19 15:27:39','2024-10-19 15:27:39','Tạo yêu cầu rút tiền');
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
-- Table structure for table `product_promotions`
--

DROP TABLE IF EXISTS `product_promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `promotion_id` bigint unsigned NOT NULL,
  `discount_price` int unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_promotions_product_id_foreign` (`product_id`),
  KEY `product_promotions_promotion_id_foreign` (`promotion_id`),
  CONSTRAINT `product_promotions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `product_promotions_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_promotions`
--

LOCK TABLES `product_promotions` WRITE;
/*!40000 ALTER TABLE `product_promotions` DISABLE KEYS */;
INSERT INTO `product_promotions` VALUES (31,3,27,70000,'2024-11-10 19:24:56','2024-11-10 19:24:56'),(32,4,27,22000,'2024-11-10 19:24:56','2024-11-10 19:24:56'),(33,2,27,39000,'2024-11-10 19:24:56','2024-11-10 19:24:56'),(34,1,27,32000,'2024-11-10 19:24:56','2024-11-10 19:24:56');
/*!40000 ALTER TABLE `product_promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_img` json NOT NULL,
  `product_des` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `product_price` int NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `product_views` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Khô cá đuối đen','[\"uploads/S2OK79lJuwgXloqlwZMy65k7N1nyJrbjv578TrOm.jpg\", \"uploads/d4Cxno2I1309fFq3adSC579ohL7rjdbX4YwNws22.jpg\", \"uploads/cPXd1swpw9fKIdNrnmn8yqVLrzeZ3eSacXsTEKQG.jpg\"]','Khô cá đuối đen, đuối ó thượng hạng.\r\nLà đặc sản đặc biệt dùng để làm quà.\r\n Miếng cá đuối khô xòe như nan quạt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nHút chân không nhãn mác mang nước ngoài.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,320000,0.50,369,'2024-09-21 17:04:23','2024-11-10 19:43:28'),(2,'Khô cá sặc','[\"uploads/YJpT8X1HQZCLii0pQI3qpgzIBiDF6gGKWGHYa6rd.jpg\", \"uploads/Mh64TUzPGCv1ZEbm1VwKD0W3gWYIBpDkc6HYQbb1.jpg\", \"uploads/TqkBAClPJJsgDRon250GjrDh7xMMGlHh3Duus2Py.jpg\"]','Khô cá sặc lạt vừa ăn, không mặn không cứng.\r\nCá to đẹp thích hợp làm quà biếu, tặng.\r\nKHÔNG chất bảo quản, KHÔNG phẩm màu.\r\nCá khô hẳn có thể mang đi xa, đi nước ngoài.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,390000,1.00,74,'2024-09-21 18:43:33','2024-11-10 18:04:04'),(3,'Khô cá chạch đồng','[\"uploads/sUOejlMdPDhPm2OYf3kSaw7at5voqg1kZWOYArTH.jpg\", \"uploads/2IsVWa2stTLrsbQ2Ba8rfgn2UMyEVXFXUbKtd0vr.jpg\", \"uploads/w2BamsUQdUGjBWeAGJ8X0HyTFfwAyf4jbQCA8xnc.jpg\"]','Khô cá chạch đồng 100% tự nhiên.\r\nLàm thủ công HOÀN TOÀN.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nĐược phơi 3-4 nắng nên có thể mang đi nước ngoài.\r\nLàm mồi nhắm bia rượu ngon, trị bệnh gan.\r\nHút chân không bao bì dùng để làm quà.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,700000,1.00,62,'2024-10-05 23:42:49','2024-11-10 18:29:29'),(4,'Khô cá cơm','[\"uploads/uim58xjhaZ6evVGrsE3A98vJUh6eZEKQ4AlXVcZ7.jpg\", \"uploads/Qeun2wM8XuTYb3syiPFYvOtKhftOAMe8w4D9Pkhz.jpg\", \"uploads/hyx7x1qsbPEmb4JVh98MKvWNdQHM6Y4WZJLSOzoZ.jpg\"]','Khô cá cơm Cà Mau loại to nhất 4cm - 6cm.\r\nLàm từ cá tươi - Hoàn toàn thủ công\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,220000,1.00,30,'2024-10-05 23:47:17','2024-11-10 18:27:25'),(5,'Khô cá đù','[\"uploads/Y8edZ5piSX2Hc4VXhFivUXpFvepMlWJjmv1uulMU.jpg\", \"uploads/dvugTdaOedVynuVn71qWawFNkeI8ni8FCb89xfJf.jpg\", \"uploads/CS89GsJcAAr4uGMmOrxC2TWDrSJfdpMaFjJ2mEGK.jpg\"]','Khô cá đù nhiều nắng loại vừa ăn không mặn.\r\nLoại nhiều nắng có thể mang đi nước ngoài\r\nLàm thủ công gia truyền làng biển.\r\nChiên hoặc nướng ăn ngon với cơm và canh, rau.\r\nKhông hoá chất - Đảm bảo vệ sinh ATTP.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,320000,1.00,78,'2024-10-05 23:48:20','2024-11-10 18:55:37'),(6,'Khô cá dứa','[\"uploads/0EeyA8K17SCcV6Hgjbuix95BS5MqJTesUYqJUcEY.jpg\", \"uploads/YxRGM4Xnm479VDnArQ86kQvoKGqQ38ce4f51Gz9P.jpg\", \"uploads/rvRmWGivD4XfcdmUVziqbAcPiDJvwHFzsbIFF6Bb.jpg\"]','Khô cá dứa loại ngon thật 100%.\r\nDứa loại to KHÔNG ĐẦU từ 800gr-1.6kg/con.\r\nChỉ bán theo con không bán theo ký\r\nLoại cá khô hẳn (3 nắng) có thể mang đi xa.\r\nĐảm bảo ATVSTP và làm thủ công.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,580000,1.00,28,'2024-10-05 23:57:05','2024-11-09 20:58:30'),(7,'Khô cá kết','[\"uploads/FC1yt5OyHpGKX7vmqQlvETaUQCCuTNx7szTl0rla.jpg\", \"uploads/6GzjwLaf3mkd1uTOJrFq5naFbV4lEU6ZXmIzfXeC.jpg\", \"uploads/ZGIG5UtHNZ2rH1jAzYsLNYRByOuoah9VsGfZlLpI.jpg\"]','Khô cá kết, khô cá trèn bầu Miền Tây.\r\nĐược lấy trực tiếp từ nông dân vùng sông nước.\r\nĐược sản xuất thủ công và hoàn toàn tự nhiên.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nSản phẩm thích hợp làm quà tặng.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,500000,1.00,4,'2024-10-05 23:58:39','2024-11-09 20:58:32'),(8,'Khô cá lóc non','[\"uploads/6liJEZWb0RQome2SX96Y739WclaEWN3iquWL1za8.jpg\", \"uploads/DxvmA5yCyHJWn8EhEti9fWNvx4BYR8MTYphssAXU.jpg\", \"uploads/6Q8kq5TXOXh3BFAovuBLbTEXyLQK6ylMH5NUaEIC.jpg\"]','Kích thước nhỏ nhưng cực ngon (15-25con).\r\nLoại nguyên con, không tẩm vị, lóc đồng tự nhiên.\r\nThịt chắc, mềm, ngọt, thơm ngon tự nhiên.\r\nLoại 3 nắng có thể mang đi xa, đi nước ngoài.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC',2,360000,1.00,3,'2024-10-06 00:04:42','2024-11-09 20:58:34'),(9,'Khô cá mối 1','[\"uploads/VYoGsnC9dSriiObrdlOg1iTtUxY6yu175ZA9G1oF.jpg\", \"uploads/2U5JheUxAfR8R6yqh6w4IwEJGzUBMfQIKWbXIADy.jpg\", \"uploads/tdXD2QEShilhQ1qInitBugVX8BKKRsmOUc8F6jRS.jpg\"]','Khô cá mối 1 nắng vừa ăn không tẩm ướp.\r\nKHÔNG hóa chất - Không phẩm màu.\r\nSản phẩm nhà làm - Thủ công hoàn toàn.\r\nFree ship khi mua từ 2kg hoặc đơn >=800k.\r\nGiao hàng thu tiền Tận nơi TPHCM.',2,180000,1.00,3,'2024-10-06 00:06:25','2024-11-10 19:16:51'),(10,'Khô cá sặc rằn','[\"uploads/OH4QzKlAP74beg4xFbbhSi3BU4may9XXi6LldA9t.jpg\", \"uploads/mQhKhOb0oaMe97kYDp06Ill7lCulElEROAww7bXo.jpg\", \"uploads/RAE8hGclZV5oD1S068O5aX946Lyv3unvT7rzljNm.jpg\"]','Khô cá sặc lạt vừa ăn, không mặn không cứng.\r\nCá to đẹp thích hợp làm quà biếu, tặng.\r\nKHÔNG chất bảo quản, KHÔNG phẩm màu.\r\nCá khô hẳn có thể mang đi xa, đi nước ngoài.\r\nGiao hàng và thu tiền tận nơi TOÀN QUỐC.',2,390000,1.00,3,'2024-10-06 00:19:50','2024-11-10 19:16:30'),(11,'Khô cá thiều','[\"uploads/9QtVHTbumHXxdhaPUHtM5lSCiZ4oWNvDqCjxJG2W.jpg\", \"uploads/e3HTXoV9FZ0iTOiOgG1saP64asS1UebkhiNRAgx0.jpg\", \"uploads/A6AxkHas27EfzHvN6qfKfbrDHT7Qiq2bOA7izjoG.jpg\"]','Khô cá thiều miếng tiêu sọ Phú Quốc loại thượng hạng.\r\nĐược làm hoàn toàn 100% cá thiều tươi sống.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nĐóng gói hút chân không, bao bì đầy đủ.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,620000,1.00,1,'2024-10-06 00:21:00','2024-11-09 20:47:23'),(12,'Khô cá thòi lòi','[\"uploads/99AxxnYA0fEHsbNzJDMLrDInwB5ynCC6okBDhABj.jpg\", \"uploads/NcW7tmw5KgKJ23g8nqbHRkZP0MKV6pSicUnMPsK8.jpg\", \"uploads/TnOtFDfQbV1UczfgCXd9r7kT0SYOEe8sY9WsAWyg.jpg\"]','Khô cá thòi lòi nguyên còn Cà Mau\r\nĐặc sản Quái Thú Leo Cây Cà Mau.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nXuất xứ: Rạch Gốc - Cà Mau.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,550000,1.00,1,'2024-10-06 00:22:03','2024-11-09 20:47:24'),(13,'Khô cá thu','[\"uploads/Pu5TUYED3nGypICFQdcOeVogpQr3XzDGUn7QONh8.jpg\", \"uploads/adcRVjiZRgnPd2Bk9XhqtHONK75zQHdbZxDrKf4P.jpg\", \"uploads/LyMgxDIyZ9PsGGsEvp8B4R8EgP1vAFeOmvTnG1UN.jpg\"]','Khô cá thu thịt bùi, vị tươi ngon vừa ăn. \r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nĐảm bảo vệ sinh và an toàn thực phẩm.\r\nCá có nhiều đạm là món ăn đổi vị tuyệt vời.\r\nHút chân không, nhãn hiệu mang đi nước ngoài hoặc biếu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',2,200000,1.00,1,'2024-10-06 00:22:51','2024-11-09 20:47:26'),(14,'Cá sặc 1 nắng','[\"uploads/t933nmMSbuIctVzRTnERUVDEf2eH1EJZK2SfPXXQ.jpg\", \"uploads/5olyoT8WdlweLNhoo6saVbEEBd7kDwIgkCGgRaUB.jpg\", \"uploads/tGR39pjL8mZtN4EFIAq4lofVRvK3su27zKTrQMHQ.jpg\"]','Cá sặc 1 nắng ướp sả ớt được làm từ cá sống 100%.\r\nCá có size 8-10con/kg đóng gói 1kg như hình.\r\nLàm bằng phương pháp thủ công không hoá chất.\r\nKhô được đóng gói 1kg và bảo quản lạnh.',5,200000,1.00,0,'2024-10-06 00:24:46','2024-10-06 00:26:25'),(15,'Gò má cá lóc 1 nắng','[\"uploads/Nmhgv35YbtphEzVU6DZ9frvrR1j9MRFOUitHGt0u.jpg\", \"uploads/vCIRDzFcqYLBFf9Ests14qC7sjpeLksiReiszE3p.jpg\", \"uploads/qwc8zlm0EdGm0UK4suXEQBUt1YFz57hbwzo685Wn.jpg\"]','Thịt má được lấy ra từ phần thịt 2 bên đầu cá.\r\nChỉ có những con cá lóc đồng to mới có thịt má.\r\nĐược ướp gia vị vừa ăn, phơi 1 nắng tốt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.',5,300000,1.00,0,'2024-10-06 00:26:04','2024-10-06 00:26:04'),(16,'Khô cá đổng','[\"uploads/wpY9DQEow1fYKsV9wYRTViqop9Zjl2qbhOK95Mts.jpg\", \"uploads/7j6HIF9Q2e0xJyN4AwoCnPcEjOY80spEKvBy9Iyw.jpg\", \"uploads/l7NL4zue7QqgfyswR34yrBsIUDW75xTFKRVboiGp.jpg\"]','Khô cá đù 1 nắng vừa ăn mềm vị lạt.\r\nĐổng 1 nắng loại nguyên con 6-10con/kg\r\nKhông hóa chất - Không phẩm màu.\r\nBảo quản cấp đông không quá 30 ngày.\r\nGiao hàng thu tiền tận nơi toàn quốc.',2,200000,1.00,3,'2024-10-06 00:28:09','2024-10-15 00:08:48'),(17,'Khô cá lưỡi trâu','[\"uploads/NWqQpFuK2OUvE4akRoACDMm56SClIFLoXHARF0Xt.jpg\", \"uploads/IzLz3eRv3JNatFUIoXySjsj6Mk0NIEtbDCdXqUvA.jpg\", \"uploads/zgr4dgx0JEymHM38d0PxUcIAmvX5xl9sNGjb20yw.jpg\"]','Khô cá lưỡi trâu 1 nắng loại 12-20con/kg\r\nTẩm ướp gia vị vừa ăn không mặn.\r\nLoại cá lớn ít xương nhiều thịt.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nMiễn phí giao hàng nội ô TPHCM khi mua từ 2kg.\r\nChỉ giao hàng TPHCM và các tỉnh lân cận.',5,190000,1.00,0,'2024-10-06 00:29:43','2024-10-06 00:29:43'),(18,'Khô cá sặc trứng','[\"uploads/ZZcmqcJy6EOGRUnMrt4o8whNIULcFW77ckJo3CnG.jpg\", \"uploads/zBNRuf93GpYMU4eIdgAJkuM6osZ1OUC6NWcwc561.jpg\", \"uploads/IdbcxGK8yE38E0GRY9avHXpwEldfPBikuQXjCJmo.jpg\"]','Khô cá sặc trứng 1 nắng loại vừa ăn ngon.\r\nSặc trứng 1 nắng loại  6 - 8 con/kg.\r\nXuất xứ: Huyện U Minh, Cà Mau.\r\nTHỊT thơm ngon - KHÔNG bở - ÍT xương.\r\nKhông chất bảo quản - Đảm bảo VSATTP.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.\r\nXem thêm: Khô cá sặc 1 nắng ướp sả ớt.',5,200000,1.00,0,'2024-10-06 00:31:01','2024-10-06 00:31:01'),(19,'Khô cá đù 1 nắng','[\"uploads/Q6VwC6zR4DeGwliPS75QquYY8stzB8uA1CaefEkB.jpg\", \"uploads/VZBZND1lZAFIsv4RWOPO25W2fUYg9xRJiQ7BKNYS.jpg\", \"uploads/uVBeIt9jdlAAGmRBEos5yQorpX4Ow4dLzH322VjN.jpg\"]','Khô cá đù 1 nắng vừa ăn mềm vị lạt.\r\nCó loại xẻ rút xương và loại nguyên con.\r\nKhông hóa chất - Không phẩm màu.\r\nBảo quản cấp đông không quá 30 ngày.\r\nGiao hàng thu tiền tận nơi toàn quốc.',5,200000,1.00,1,'2024-10-06 00:32:44','2024-10-15 16:47:06'),(20,'Tôm đất khô','[\"uploads/IHNfqyxuqNn3ehHfjDlKeknpjSPnVkyg7gXaLPFz.jpg\", \"uploads/eIXhA8jyfiVeRMB557tpWpgLIF8LkMMUqwi6dhLd.jpg\", \"uploads/G5znnk31cNhjOpq2kZwbrD9It7Drz8tDZ5STivAB.jpg\"]','Tôm khô làm THỦ CÔNG từ tôm đất sống.\r\nXuất xứ: Rạch Gốc, Năm Căn - Cà Mau.\r\nMàu sắc tự nhiên KHÔNG chất tạo màu.\r\nĐảm bảo, Hoàn tiền nếu khách không vừa ý.\r\nĐóng gói - Hút chân - Bao bì để gửi nước ngoài.\r\nHộp đẹp cho khách làm quà tặng biếu TẾT.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,1600000,1.00,2,'2024-10-06 00:33:55','2024-11-10 19:11:17'),(21,'Tôm khô chà bông','[\"uploads/6Aw7FbwMyOTzqqEIm9Kwkl1KhLD4JniMmBxASCE0.jpg\", \"uploads/ByGt3zcVWnqjh8G45u6Mlcq8XoGycHHgZ3rHjwDS.jpg\", \"uploads/M7n7i5NnnsoKNcz3pJyXuqO2ZlQKFe6Pka7UXYjU.jpg\"]','100% làm từ tôm đất sống Cà Mau.\r\nQuy trình làm công phu, thủ công hoàn toàn.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nSản phẩm có nhãn hiệu, bao bì đẹp làm quà.\r\nĐặc sản Cà Mau làm quà tặng ngày Lễ, Tết.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,400000,0.25,4,'2024-10-06 00:35:28','2024-11-10 17:55:16'),(22,'Tôm khô nấu canh','[\"uploads/MsHcRevrqhOfqorJNt256jtSC6KZbjaxfBBBcWFR.jpg\", \"uploads/xs4nXLzv3qfK1ysTZ3jpHDokSv10kg6nJcysC3ka.jpg\", \"uploads/QgYbgD6bs67xQpdkS9Xp0cUWIuhVq2oxzqbOjdlD.jpg\"]','Tôm nõn khô nấu canh làm từ tôm thẻ.\r\nDùng để chế biến món ngon như tôm khô canh bầu bí, tôm khô kho quẹt, tôm khô sốt cà, tôm khô kho thịt.\r\nLàm THỦ CÔNG từ tôm tươi sống.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nĐặc biệt tôm khô có thể ăn sống hoặc ăn cùng củ kiệu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,640000,1.00,4,'2024-10-06 00:36:53','2024-11-10 17:57:05'),(23,'Tôm khô nguyên vỏ','[\"uploads/moMvQqLFPBwIOc5Mx5AkxLde4t7xZIRQfoKdd9No.jpg\", \"uploads/HnLHLFSQsLcjl99T6GhBqxlPeWO88JLnJD99OaXY.jpg\", \"uploads/plkcEPMWKa6NejasmyLvjTkfif19iCjXheTryU1K.jpg\"]','Làm từ 100% tôm đất sống vùng sinh thái.\r\nThích hợp làm mồi nhắm rượu bia.\r\nSản phẩm đẹp - ngon thích hợp làm quà biếu.\r\nLàm thủ công, nguyên liệu tươi sống.\r\nKhông hóa chất - Không phẩm màu.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',3,900000,1.00,1,'2024-10-06 00:37:40','2024-10-12 17:09:35'),(24,'Tôm tích khô','[\"uploads/GVQvOrDR4xnri7jIyRWJ8nrqEgQE3O4cbAQIOmwI.jpg\", \"uploads/x4a5XDF5kd65WIOv1G7olLbqI9oYJouzHw05c0UU.jpg\", \"uploads/MRcO5QHiJolRt4P4bXgL2R5V5XuoLais7oNg5pJS.jpg\"]','Khô tôm tít Cà Mau loại ngon không tẩm ướp.\r\nLàm từ tôm tươi sống - Hoàn toàn thủ công.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',3,660000,1.00,1,'2024-10-06 00:38:34','2024-10-09 19:49:38'),(25,'Chả tôm Cà Mau','[\"uploads/UD6UBTx2WpKJDsxVsGe0LQ9awhyuAkdTJMkBVtBF.jpg\", \"uploads/Ez5g8J8rzAD7s0pAks14YRjd3lPaG0KtLDbbiwGY.jpg\", \"uploads/EhCT3gi7XKgGaFERudoQLMPgqZsZzHFtRA1GisnA.jpg\"]','Chả tôm tươi, Đặc sản Cà Mau.\r\nLàm 100% từ tôm tươi vùng sinh thái Cà Mau.\r\nKHÔNG chất bảo quản - KHÔNG phẩm màu.\r\nChả đậm vị tôm tươi, thơm ngon bao dai.\r\nGiao hàng tận nơi TOÀN QUỐC.',10,180000,0.50,1,'2024-10-06 00:39:43','2024-10-29 14:44:02'),(26,'Lạp xưởng tôm','[\"uploads/98fgBn7bX4mjPQdxrVu9W3u2URB9CdKCADoAg8m1.jpg\", \"uploads/KLq791AlqKKYm3HPKWFHvZUrEFWXwJbzDBjprxoy.jpg\", \"uploads/8gBjbNq74TdewuLHna7BpeuBzZOa62r5a4r686AW.jpg\"]','Lạp xưởng tươi vị tôm đậm chất Cà Mau.\r\nĐược làm từ tôm tươi sống vùng sinh thái Cà Mau.\r\nNgọt dịu - Thơm ngon - Không ngán, ngấy.\r\nĐặc sản không thể thiếu trong mâm cổ ngày Tết.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.',10,180000,1.00,0,'2024-10-06 00:40:36','2024-10-06 00:40:36'),(27,'Mật ong rừng nguyên tổ','[\"uploads/N1xT0MUFCkcPww08DwcZi0q1GWnNshqIT2zTCpW7.jpg\", \"uploads/ucLDxuNTCXOgU8MegzwH4FvOfOUoBP7Hv5jT29kL.jpg\", \"uploads/Ee2xgwtJ16zA8z1mhDKhIKEkdTGAHjSivZ1sQEDJ.jpg\"]','Ong ruồi nguyên tổ (sáp và mật).\r\nVắt lấy mật hoặc có thể ăn cả sáp và mật.\r\nMật ong hoàn toàn TỰ NHIÊN.\r\nĐược lấy bởi nông dân lấy ong chuyên nghiệp.\r\nSau khi vắt có thể lấy sáp dùng để ngâm rượu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,900000,1.00,40,'2024-10-06 00:41:42','2024-10-06 15:56:46'),(28,'Mật ong rừng U Minh','[\"uploads/bQyQrBTr22XswmnI5vkij3tkbioWcOrTHwYH8Ity.jpg\", \"uploads/1LwAH5ewDqKWRhzS9mZVZwTy4uspv5CaC6k7aBZo.jpg\", \"uploads/OqlzYRj7yPxMDFRnYWyzCYPCIC0dyIQfrXjm1Te4.jpg\"]','Xuất xứ: rừng tràm U Minh Hạ - Cà Mau.\r\nMật ong thật 100%; Hoàn tiền nếu mật giả.\r\nLấy trực tiếp từ nông dân sống tại rừng U Minh.\r\nThích hợp cho việc làm đẹp và tốt cho sức khỏe.\r\nSản phẩm đảm bảo và cam kết hoàn tiền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,250000,0.50,0,'2024-10-06 00:42:42','2024-10-06 00:42:42'),(29,'Mật ong ruồi','[\"uploads/IHrpIkg2iIhoOiG1uxYmCQrAhXgwTaaCldMaSRqX.jpg\", \"uploads/SNePQdqbpIJ3gOopxiAucMBedMh9vXRv8a5ytyCE.jpg\", \"uploads/UH18FWDTjGFZovYTirBFlw1O6Ht1lwCsVSCSrouS.jpg\"]','Mật ong thật 100%; Hoàn tiền nếu mật giả.\r\nThích hợp cho việc làm đẹp và tốt cho sức khỏe.\r\nLấy trực tiếp từ nông dân bắt ong chuyên nghiệp.\r\nSản phẩm đảm bảo và cam kết hoàn tiền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',7,600000,0.50,0,'2024-10-06 00:43:45','2024-10-06 00:43:45'),(30,'Mắm ba khía','[\"uploads/qNNWb15Q73ci9C8Bal9xpVCGY8kCxgXLoU19g7P3.jpg\", \"uploads/2OXtFbc21JS8ZXgDUFqsUFDAxAsN2a8snWocEyyO.jpg\", \"uploads/HruhUtyLf4bnT3LAH7Bt6R1Tym6z2Fz62xZUXMRR.jpg\"]','Mắm ba khía Rạch Gốc - Cà Mau.\r\nLàm vị theo yêu cầu (ít ngọt hoặc ít cay).\r\nĐược chế biến Thủ công - Truyền thống.\r\nGiao hàng tận nơi TOÀN QUỐC.',9,175000,0.60,2,'2024-10-06 00:44:51','2024-11-07 23:08:37'),(31,'Mắm cá lóc','[\"uploads/b6ugstjo70opUExBKRwOBuIj6QfA5Mw0k3dqKdHm.jpg\", \"uploads/5fpkiOdluPe03ootgm6FABFXcqWH4qsKKES3fNcS.jpg\", \"uploads/ObFb5RwjRX6Isd36DrRVBxlvwLJ0westKbLG1hyp.jpg\"]','Mắm cá lóc đồng U Minh - Cà Mau.\r\n1kg giá 250.000đ; hũ nửa ký 130.000đ.\r\nLàm từ cá lóc đồng chính gốc U Minh.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',9,250000,1.00,0,'2024-10-06 00:45:41','2024-10-06 00:45:41'),(32,'Mắm tép Cà Mau','[\"uploads/jw6j5bSJga3eQwQppgKLjHxxRVI0ruY5qLqQRTfU.jpg\", \"uploads/siBLFOu4hVvFbyqGa2lFddumhw3SdQkcOCenqOr9.jpg\", \"uploads/Q5jB8b8sLqZk8qv4OAIjPOFLrWNUvObSZSOWNGsj.jpg\"]','Mắm tép Cà Mau được làm từ tôm tươi sống.\r\nHoàn toàn THỦ CÔNG - KHÔNG PHẨM MÀU.\r\nĐảm bảo vệ sinh - ANTT.\r\nCông thức gia truyền, dai ngon, tôm còn thịt.\r\nHương vị miền Nam chua cay.\r\nNgon hơn khi trộn với đu đủ (12 tiếng là ăn được).',9,270000,1.00,0,'2024-10-06 00:46:39','2024-10-06 00:46:39'),(33,'Mắm tép trộn đu đủ','[\"uploads/3nJdngAa3fuPtEdXkIQgeALkdd5O01gwqxofb0Qw.jpg\", \"uploads/n7y65Ey0IFkQYE0vyYvIb7C54uK00TJvVH6ZvzBf.jpg\", \"uploads/tDco85cH59FxOqjhXG1VhWjsy5NBLNN3qzzEEyKF.jpg\"]','Mắm tép trộn đu đủ Cà Mau thơm ngon.\r\nNguyên liệu: 1 nửa mắm tép, 1 nửa đu đủ.\r\nMắm tép chua ngọt đậm mùi tôm Cà Mau.\r\nĂn cực ngon cùng cơm trắng và các món khác.\r\nMiễn phí giao hàng nội ô khi mua 2 hũ.',9,170000,1.00,0,'2024-10-06 00:47:33','2024-10-06 00:47:33'),(34,'Khô mực loại 1','[\"uploads/whhWhWwwL32XLFHpW8YgSCCRpDJBbwdJiwGxJNDC.jpg\", \"uploads/k3iZQ8rAgJhNXNnEYXaYLw2BvDKo8gbRKmZaon4U.jpg\", \"uploads/PCZtEDMcVfC2xSP7eZzCp2rrcVf9aUydoHKkMimB.jpg\"]','Xuất xứ: Sông Đốc Cà Mau.\r\nLà loại khô câu, ngon nhất Cà Mau.\r\nMực con TO - DÀY - NGỌT THỊT.\r\nLàm từ mực tươi sống - Khô chế biến thủ công.\r\nKHÔNG hoá chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',6,1450000,1.00,1,'2024-10-06 00:48:35','2024-11-09 20:08:18'),(35,'Khô mực loại nhỏ','[\"uploads/H1iwww6KjSDPHzx88lLfALaQPTTPGFOK6OXZ7JsH.jpg\", \"uploads/P3v98JlJeIRjwTyM9hSN2cooUjFTbAx5ReDYz3H2.jpg\", \"uploads/ZUumkQBEJ7PDhhXl1IHLPUG9VuJMuVXNbxtCXfJ0.jpg\"]','Khô mực chính gốc Sông Đốc Cà Mau.\r\nKhô mực loại nhỏ các loại.\r\nLàm thủ công từ mực tươi sống.\r\nKhông hoá chất - Không phẩm màu.\r\nDùng cho các món rim, nướng hoặc chiên.\r\nGiao hàng thu tền tận nơi Toàn Quốc.',6,550000,1.00,1,'2024-10-06 00:49:30','2024-11-09 20:08:24'),(36,'Khô mực nấu súp','[\"uploads/p3BTvynyDJRMUP92B92R78D0iQ62DweB8j9RhPCz.jpg\", \"uploads/tI07dSuGs5RLVmUA6stU0dNAAcYTvp5nTaEMLqpc.jpg\", \"uploads/uAJbgQXmS9x424zxbiu2CDOBc92TCn3Xae2681YT.jpg\"]','Khô mực nấu súp, mực đồng tiền Sông Đốc.\r\nMực tươi, ngọt nước dùng để nấu súp.\r\nMực có size >100con/kg con to tròn không đen.\r\nKHÔNG hóa chất - KHÔNG phẩm màu.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',6,550000,1.00,0,'2024-10-06 00:50:21','2024-10-06 00:50:21'),(37,'Hồng treo gió','[\"uploads/ZWmcHEaUlhCsxaHowGCi75Ajps8J00XIWBJXtDoq.jpg\", \"uploads/4raSDS2yPgNnHq08C7nDuFGWupaX6kCvgcgcjxEn.jpg\", \"uploads/onOZVOCtbXCsm9o4nO8MeTwsfVfDhjA3VVwIx6Dv.jpg\"]','Chọn từ Hồng Trứng đặc trưng của Đà Lạt.\r\nTreo khô hồng thủ công bằng gió tự nhiên.\r\nVị hồng trứng giữ nguyên bản, ngọt dịu.\r\nThủ công KHÔNG sử dụng chất bảo quản.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',8,350000,1.00,0,'2024-10-06 00:51:30','2024-10-06 00:51:30'),(38,'Bánh phồng chuối','[\"uploads/Zk1HM8d4em9lMaApjZVuiAUh5yWMdjeHiJqIWA5h.jpg\", \"uploads/VgMlOTPcDHm5ImlNleoB5zBgmtp3zMivEU8YnWIb.jpg\", \"uploads/hLnnDpRoj55pZSl73s0MTOek4fwD3LKaqUQmFCBd.jpg\"]','Bánh phồng tôm chay vị chuối Cà Mau thơm ngon.\r\nXuất xứ: Năm Căn, Cà Mau.\r\nBánh có vị chuối đặc trưng thơm ngon không ngán.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nLàm thủ công HOÀN TOÀN.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,90000,0.50,0,'2024-10-06 00:52:28','2024-10-06 00:52:28'),(39,'Bánh phồng môn','[\"uploads/Ao3b81dXdtEdafA90Yo8z4z26b8sBy4XOQOibnuO.jpg\", \"uploads/wzJIMRmCrPlbGONVdaLTwdqG0n3qylZHrdL6PaPN.jpg\", \"uploads/1n877gZNxirHce9LhaxwiIWam0kod26Cxh9wxU7M.jpg\"]','Bánh phồng tôm chay vị khoai môn thơm ngon.\r\nKhoai môn hay còn gọi là khoai sọ (miền bắc).\r\nXuất xứ: Hàng Vịnh, Năm Căn, Cà Mau.\r\nCó vị môn đặc trưng thơm ngon không ngán.\r\nKHÔNG hóa chất, KHÔNG phẩm màu.\r\nLàm thủ công bằng công thức gia truyền.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,90000,0.50,9,'2024-10-06 00:53:17','2024-11-10 18:32:09'),(40,'Bánh phồng tôm','[\"uploads/F8vO1KSYMiAOCdDhQg96itYdfeUGQtH5GPjcO8AX.jpg\", \"uploads/oGyTVV2mi3eu8BPDbzlUozPMCXgTQsgIUd3gKTYv.jpg\", \"uploads/aydf1xXv3UXBR714jjc2XNJzGVUvE1qJdWaNCHMS.jpg\"]','Bánh có màu đỏ thẵm do tỷ lệ tôm trong bánh cao.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nLàm mồi nhắm bia rượu hoặc ăn cùng món gỏi, nộm.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,85000,0.50,1,'2024-10-06 00:54:01','2024-11-10 18:31:45'),(41,'Bánh phồng tôm cao cấp','[\"uploads/SPN2bLAGYpHfLu1pb1fE8SgZSP3BvCACoaqNzr01.jpg\", \"uploads/dJeagKVo8ylsCDo0V69kqjeuDN3EBg2cPgNxI6xs.jpg\", \"uploads/6XnioRu9RqjLfduIr628K8vP0LBploqOqEdetUIK.jpg\"]','Loại bánh đặc biệt với tỉ lệ tôm hơn 38%.\r\nĐược làm từ tôm đất sống vùng sinh thái.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nĐặc sản có thể làm quà cho người thân đối tác.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,130000,0.50,2,'2024-10-06 00:54:47','2024-11-10 19:22:02'),(42,'Bánh phồng tôm đặc biệt','[\"uploads/yzDWPBTy1m9hTS9LcZZf25iyEoUWWog0s1zdSjqQ.jpg\", \"uploads/XMYjYWm3BQxc4MDfIAo8RvzTLIqnii3x8soRP3Xh.jpg\", \"uploads/lzH5JFms3YvCecLuYwVN8hHahWuWxMoCwzuOne2F.jpg\"]','Loại bánh đặc biệt với tỉ lệ tôm hơn 50%.\r\nĐược làm từ tôm đất sống vùng sinh thái.\r\nBánh có hình vuông góc cạnh đặc thù.\r\nĐặc sản có thể làm quà cho người thân đối tác.\r\nĐược làm thủ công - Đảm bảo vệ sinh.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',4,160000,0.50,2,'2024-10-06 00:55:39','2024-11-07 15:29:14'),(43,'Khô rắn đồng','[\"uploads/1mXbsUI0WE0BBRIt3GLtFA2tHTIMCwsNGmOglW3L.jpg\", \"uploads/Y8WHZL9d3tzf10LZbWXqwlHhCurfhDk0ikxacXGP.jpg\", \"uploads/agsTWCpuJ8ua4g6Am2nR7kWYBUKzDNixZh9gHxXn.jpg\"]','Khô rắn đồng loại đặc biệt 100% rắn tự nhiên\r\nĐược làm tự rắn nước rắn bông súng vùng An Giang.\r\nĐặc sản trứ danh vùng sông nước.\r\nQuy trình sản xuất sạch sẽ KHÔNG hóa chất.\r\nGiao hàng thu tiền tận nơi TOÀN QUỐC.',17,450000,1.00,0,'2024-10-06 00:56:40','2024-10-06 00:56:40'),(44,'Rượu trái giác','[\"uploads/vS2BQDv0dMuDxh1h8uTW66ERYlX4LRFbFaa96Pcl.jpg\", \"uploads/yZUCTkh3FzGx6MtJxEUOziSnK98MlCiLNITtdpiC.jpg\", \"uploads/JhdGID9eotneLQXvQXfQI164zD29Bq7pCwpwgAfd.jpg\"]','Rượu trái giác đặc sản trứ danh Cà Mau.\r\nLoại chai 30% vol giá 250.000đ\r\nXuất xứ: rừng U Minh hạ, Cà Mau.\r\nSản phẩm cao cấp làm quà đặc biệt.\r\nGiao hàng thu tiền tận nơi Toàn Quốc.',17,250000,0.75,0,'2024-10-06 00:57:34','2024-10-06 00:57:34');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `promotion_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_percentage` int unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `apply_to` enum('batch','product_group') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'product_group',
  `user_group` json DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`promotion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
INSERT INTO `promotions` VALUES (27,'Khuyến mãi tháng 11',10,'active','product_group','[\"normal_user\", \"loyal_customer\"]','2024-11-01','2024-11-30','2024-11-10 19:22:50','2024-11-10 19:22:50');
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refund_requests`
--

DROP TABLE IF EXISTS `refund_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refund_requests` (
  `refund_request_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `bill_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `refund_request_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`refund_request_id`),
  KEY `refund_requests_order_id_foreign` (`order_id`),
  KEY `refund_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `refund_requests_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  CONSTRAINT `refund_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refund_requests`
--

LOCK TABLES `refund_requests` WRITE;
/*!40000 ALTER TABLE `refund_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `refund_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `rating` int NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`review_id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_product_id_foreign` (`product_id`),
  CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (15,5,'Sản phẩm quá chất lượng luôn shop ơi. Ngon quá ạ.','Cảm ơn bạn nhoa.',37,21,'2024-11-10 17:55:07','2024-11-10 17:58:48'),(16,5,'Em mua về để nấu canh cho cả nhà ăn. Ai cũng khen ngon hết.','Cảm ơn bạn nhoa.',37,22,'2024-11-10 17:57:31','2024-11-10 17:59:07'),(17,5,'Cá ngon lắm shop ơi','Cảm ơn bạn nhiều ạ.',36,2,'2024-11-10 18:04:31','2024-11-10 18:07:24'),(18,5,'Ngon lắm ạ','Cảm ơn bạn nha.',36,1,'2024-11-10 18:05:50','2024-11-10 18:07:10'),(19,4,'Khá hợp vị',NULL,34,4,'2024-11-10 18:27:36','2024-11-10 18:27:36'),(20,3,'Hơi mặn shop ơi',NULL,34,3,'2024-11-10 18:29:41','2024-11-10 18:29:41'),(21,5,'Chiên lên ăn thơm lắm ạ','Cảm ơn bạn nha',34,40,'2024-11-10 18:31:54','2024-11-10 18:51:14'),(22,5,'Thơm lắm shop ơi','Cảm ơn bạn nha',34,39,'2024-11-10 18:50:30','2024-11-10 18:51:00'),(23,5,'Ngon lắm nhoa shop',NULL,33,5,'2024-11-10 18:55:47','2024-11-10 18:55:47'),(24,4,'Ngon lắm shop ơi',NULL,33,20,'2024-11-10 19:11:29','2024-11-10 19:11:29'),(25,5,'Ngon quá xá',NULL,32,10,'2024-11-10 19:16:39','2024-11-10 19:16:39'),(26,5,'Okie nhoa, rẻ và rất ngon',NULL,32,9,'2024-11-10 19:17:05','2024-11-10 19:17:05'),(27,4,'Ngon quá đi',NULL,29,41,'2024-11-10 19:22:11','2024-11-10 19:22:11');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (3,2),(5,2),(7,2),(8,2),(9,2),(10,2),(12,2),(26,2),(27,2),(28,2),(29,2),(30,2),(31,2),(3,3),(5,3),(7,3),(8,3),(9,3),(10,3),(12,3),(26,3),(27,3),(28,3),(29,3),(30,3),(31,3),(33,4),(34,4),(3,5),(5,5),(7,5),(8,5),(9,5),(10,5),(15,5),(17,5),(18,5),(19,5),(20,5),(22,5),(26,5),(27,5),(28,5),(29,5),(30,5),(31,5),(3,6),(5,6),(7,6),(8,6),(9,6),(10,6),(12,6),(15,6),(17,6),(18,6),(19,6),(20,6),(22,6),(24,6),(25,6),(26,6),(27,6),(28,6),(29,6),(30,6),(31,6),(32,6),(33,6),(34,6);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
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
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (2,'normal_user','api','2024-09-17 13:01:33','2024-10-18 13:22:12','Khách hàng có tài khoản'),(3,'loyal_customer','api','2024-09-17 13:01:33','2024-10-18 13:22:20','Khách hàng thân thiết'),(4,'affiliate_marketer','api','2024-09-17 13:01:33','2024-10-18 13:22:29','Khách hàng tiếp thị'),(5,'staff','api','2024-09-17 13:01:33','2024-10-18 13:22:34','Nhân viên'),(6,'admin','api','2024-09-17 13:01:33','2024-10-18 13:22:41','Quản trị viên');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
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
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` json DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `point_used` int NOT NULL DEFAULT '0',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `online_payment_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int NOT NULL DEFAULT '0',
  `point_expiration_date` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_password_reset` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Dương Hoài Ân','tempcontact2024@gmail.com','100729030619044004033','https://lh3.googleusercontent.com/a/ACg8ocLBklt70PDMWQbs6y5pGyzhiCPz3mZzPAlo5rFkEfHKm1aa8w=s96-c',NULL,'$2y$12$ShUiM2rim42IhdCgQLdYNORoCDXjQdWQvkA5fFspm8aAE9Kcye17.',NULL,'2024-09-17 13:03:41','2024-10-14 21:02:04','[{\"city\": \"Tỉnh Cà Mau\", \"name\": \"Nguyễn Hoàng Kha\", \"phone\": \"0835355424\", \"commue\": \"Xã Khánh Tiến\", \"address\": \"ấp 11\", \"district\": \"Huyện U Minh\"}]',NULL,0,'hoaian2646',0.00,0.00,0,NULL,'0976146523','2024-10-12 03:49:31'),(28,'Duong Hoai An B2014552','anb2014552@student.ctu.edu.vn','108271613683039466406','https://lh3.googleusercontent.com/a/ACg8ocJWw1P-U7otBXrx_iGcf09sIious1VfR2xcvlc9iKDQRyOW3do=s96-c',NULL,'$2y$12$YRsVwHc4pr1YZBHXUAdtguNa2Jdv9bkI1CaILfbVK6kvh65yzl3Fa',NULL,'2024-11-10 16:35:44','2024-11-10 16:35:44',NULL,NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(29,'An Duong','duonghoaian1978@gmail.com','113153578844966315743','https://lh3.googleusercontent.com/a/ACg8ocKilBTDK5lYT4gTcFhcPrvdBk09NB2tRNeU-IFE1jRLK0rayiIO=s96-c',NULL,'$2y$12$7dFs590O6ZGaZ69RmtrLmOutRtd963NTa57jNxkO9BETyCYLIe4ki',NULL,'2024-11-10 16:37:36','2024-11-10 19:20:16','[{\"city\": \"Tỉnh Cà Mau\", \"name\": \"Dương Hoài Ân\", \"phone\": \"0943808107\", \"commue\": \"Xã Khánh Tiến\", \"address\": \"ấp 10\", \"district\": \"Huyện U Minh\"}]',NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(30,'Nguyễn Văn Hào','nguyenvanhao4914@gmail.com',NULL,NULL,NULL,'$2y$12$vPPBgci/ZX6Bc98QvZdTuOezNy0CsvkDt3FhJINU1wJA9UlAkKMJS',NULL,'2024-11-10 17:07:36','2024-11-10 17:07:36',NULL,NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(31,'Lê Thanh Nam','nam1234@gmail.com',NULL,NULL,NULL,'$2y$12$8/yZxTZ.ZTd2AHiIrqH1FO5EAMyYBKFN/5tox5TwI6JvvgElzA6P.',NULL,'2024-11-10 17:09:33','2024-11-10 19:39:41','[{\"city\": \"Thành phố Cần Thơ\", \"name\": \"Lê Thanh Nam\", \"phone\": \"0944393939\", \"commue\": \"Phường Xuân Khánh\", \"address\": \"số 94\", \"district\": \"Quận Ninh Kiều\"}]',NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(32,'Phạm Nguyễn Gia Bảo','bao1234@gmail.com',NULL,NULL,NULL,'$2y$12$yOw7kLPf2/Gxw7JePnB9d.wXJBENypTHra3pRSuXz2i2d5QQyHise',NULL,'2024-11-10 17:10:24','2024-11-10 19:14:09','[{\"city\": \"Tỉnh Đồng Tháp\", \"name\": \"Phạm Nguyễn Gia Bảo\", \"phone\": \"0944131321\", \"commue\": \"Xã Tân Hòa\", \"address\": \"Hòa bình\", \"district\": \"Huyện Lai Vung\"}]',NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(33,'Phạm Viết Thanh','thanh1234@gmail.com',NULL,NULL,NULL,'$2y$12$AtA0pj70UgIclNulMCAux.WTXzn2NfQEv9LmiwhUqoHNsTCXjR3qG',NULL,'2024-11-10 17:12:09','2024-11-10 19:11:00','[{\"city\": \"Tỉnh Đồng Tháp\", \"name\": \"Phạm Viết Thanh\", \"phone\": \"0947852560\", \"commue\": \"Xã Tân Nghĩa\", \"address\": \"số 83\", \"district\": \"Huyện Cao Lãnh\"}]',NULL,0,NULL,0.00,0.00,10,'2024-12-10 19:11:00',NULL,NULL),(34,'Trần Thái Đăng','dang1234@gmail.com',NULL,NULL,NULL,'$2y$12$vZsR95sRsSauISLzcMbEsuOoziqRTA8YL4auTMeyt06qhGocj8aeu',NULL,'2024-11-10 17:14:18','2024-11-10 18:26:29','[{\"city\": \"Tỉnh Kiên Giang\", \"name\": \"Trần Thái Đăng\", \"phone\": \"0911636663\", \"commue\": \"Phường Vĩnh Thanh\", \"address\": \"225 Điện Biên Phủ\", \"district\": \"Thành phố Rạch Giá\"}]',NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(35,'Mai Lê Anh Thịnh','thinh1234@gmail.com',NULL,NULL,NULL,'$2y$12$jrugXCbW1kt2UDMZxM7J5utiXcTYVjYak.U68iPNFP41s6QhuhNnW',NULL,'2024-11-10 17:15:11','2024-11-10 19:42:07',NULL,NULL,0,NULL,0.00,0.00,0,NULL,'0947508208',NULL),(36,'Tiểu Long Nữ','tieulongnu1234@gmail.com',NULL,NULL,NULL,'$2y$12$IqXH7XwhtPFUf/1HnPc.VuGbrftuncGW/Jx39eZzTswo89oeYqIdi',NULL,'2024-11-10 17:17:32','2024-11-10 18:03:33','[{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tiểu Long Nữ\", \"phone\": \"0336363636\", \"commue\": \"Phường Bến Nghé\", \"address\": \"số 4 Đ. Hồ Tùng Mậu\", \"district\": \"Quận 1\"}]',NULL,0,NULL,0.00,0.00,0,NULL,NULL,NULL),(37,'Tăng Duy Tân','tan1234@gmail.com',NULL,NULL,NULL,'$2y$12$mkxMkBPUPv.M7xTsV1meMOx048fCNRoG2blt2RZTDBPrGp5GSIEfG',NULL,'2024-11-10 17:18:23','2024-11-10 19:44:09','[{\"city\": \"Thành phố Hồ Chí Minh\", \"name\": \"Tăng Duy Tân\", \"phone\": \"0902888334\", \"commue\": \"Phường 13\", \"address\": \"12 Tôn Đản\", \"district\": \"Quận 4\"}]',NULL,0,NULL,0.00,0.00,20,'2024-12-10 17:53:23',NULL,NULL),(38,'Nguyễn Nhật Hào','nhathao1234@gmail.com',NULL,NULL,NULL,'$2y$12$BHrkx0hu31w5LMoobePadedhmJme5zHG5M4MmpdMaC2jnxVqA.w/6',NULL,'2024-11-10 17:23:12','2024-11-10 17:23:12',NULL,NULL,0,'hao1234',0.00,0.00,0,NULL,NULL,NULL),(39,'Nguyễn Hoàng Kha','kha1234@gmail.com',NULL,NULL,NULL,'$2y$12$/4mr7lofOchSzpqyD4eMNexm4bdquOaFYITBpo1MUMixBPAruZkKK',NULL,'2024-11-10 17:23:29','2024-11-10 17:23:29',NULL,NULL,0,'kha1234',0.00,0.00,0,NULL,NULL,NULL);
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

-- Dump completed on 2024-11-10 17:08:51
