-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2026 at 05:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shelter_manahan`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_20_061004_create_products_table', 1),
(5, '2026_04_20_063618_add_role_to_users_table', 2),
(6, '2026_04_20_064044_create_shops_table', 3),
(7, '2026_04_20_120400_add_shop_id_to_products_table', 4),
(8, '2026_04_20_120658_fix_products_table_columns', 5),
(9, '2026_04_22_111038_create_orders_table', 6),
(10, '2026_04_28_102710_create_carts_table', 7),
(11, '2026_04_28_132219_make_product_id_nullable_on_orders_table', 8),
(12, '2026_05_01_012637_drop_unique_from_order_id_on_orders_table', 9),
(13, '2026_05_01_013424_add_operational_hours_to_shops_table', 10),
(14, '2026_05_01_014234_add_manual_status_to_shops_table', 11),
(15, '2026_05_01_014840_add_is_active_to_shops_table', 12),
(16, '2026_05_01_095447_create_order_details_table', 13),
(17, '2026_05_02_140713_add_customer_fields_to_orders_table', 14),
(18, '2026_05_02_172914_add_closed_until_to_shops_table', 15),
(19, '2026_05_02_203809_add_balance_to_shops_table', 16),
(20, '2026_05_02_212335_create_withdrawals_table', 17),
(21, '2026_05_02_213054_create_withdrawals_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` varchar(255) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_whatsapp` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `admin_fee` int(11) DEFAULT 0,
  `amount` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `snap_token` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `order_id`, `customer_name`, `customer_whatsapp`, `payment_method`, `admin_fee`, `amount`, `status`, `snap_token`, `created_at`, `updated_at`) VALUES
(49, 18, NULL, 'MOTO-69F6069F20166', 'ALDO', '08564121', 'cashier', 0, 20000, 'pending', NULL, '2026-05-02 14:13:51', '2026-05-02 14:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `qty` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `subtotal` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `qty`, `price`, `subtotal`, `created_at`, `updated_at`) VALUES
(43, 49, 9, 1, 20000, 20000, '2026-05-02 14:13:51', '2026-05-02 14:13:51');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `nama_produk` varchar(255) NOT NULL,
  `harga` int(11) NOT NULL,
  `status` enum('aktif','tidak aktif') DEFAULT 'aktif',
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `created_at`, `updated_at`, `shop_id`, `nama_produk`, `harga`, `status`, `foto`) VALUES
(7, '2026-04-20 09:37:58', '2026-04-20 09:37:58', 4, 'Nasi Goreng', 13000, 'aktif', 'produk/KSRsRIsrp5yRK8DOxaB6tAEC0rZiC6OskAce5cNX.jpg'),
(8, '2026-04-20 09:40:24', '2026-04-20 09:40:24', 4, 'nasgor bangor', 10000, 'aktif', 'produk/K3J6wkLKsDfWxiI3wkZ1B1vHHYyg5vm9OL3zA47D.png'),
(9, '2026-04-20 09:54:27', '2026-04-27 03:06:03', 5, 'bakso ulat', 20000, 'aktif', 'produk/vDdxvCaVEomM7D9h02MO4Jia6uLVznShQlX8MB8d.jpg'),
(10, '2026-04-20 09:55:45', '2026-04-27 03:05:31', 5, 'bakso borax', 15000, 'aktif', 'produk/8Tc7q9pFiJ9MObgjmwTYY33uDKzPwtqDjrrWPzRU.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('406xoUv4BjP3o5i4vY07X1UCWlQreFG5PiJlFRko', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJVSWVkM2ZnV2Y1bUExT2hqTE9xQmhIb2tBaEFsUVNES2FFOVZNSzRkIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730953),
('Eq1iQDFW7ieTEHkBFOCyI7CA8iGelU18p15h1HTq', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJKOVdKZkFtNHVPU0ttVE5VRTVkanFCNlNGYUdiZkN1TERBSFdZWGk4IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730363),
('fqng1eHLd1fMzojqphi76MMEPAhBSkz7GJRUPguv', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJoU2lDMnRNUGUxbnlXeG5jM0tIMWJ3UHFmRURNak5rYnBqOXhkSFQ0IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL2FkbWluXC93aXRoZHJhd2FscyIsInJvdXRlIjoiYWRtaW4ud2l0aGRyYXdhbHMifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1777736866),
('HPZVJkbZICALCErD3ft2KXLEiifKD80YNX9bXJ0c', 13, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36 Edg/147.0.0.0', 'eyJfdG9rZW4iOiJiZWJnMTJNVXh5ZFVjWXlvOUI4MUg1S3l5T3psZVRPZml4cnN5RndwIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDBcL293bmVyXC93aXRoZHJhdyIsInJvdXRlIjoib3duZXIud2l0aGRyYXcuaW5kZXgifSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEzfQ==', 1777736733),
('NWUnousUrNb15grPnvhGZSekzS27wzS0OcxjRf2C', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJDY2hxME5HVDJqUlkxUm4xS3o4emZZWWFBWnY3bXhjV29XZEVBdFIwIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730631),
('o7bm9wWxdKPXSEU7VgpZ2DP54bkU72aVm8iTeU0k', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJ5WWRTVDc1M1FCTVlNVTVPdkNjczNPblcwekl5bGp0OUtzaDBQRXFXIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777731191),
('PeKC1iis9x8ULB3jASMmSNTvEgzjexolwjmowgDI', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJBcUVvNU5DUHpjV0Z1S243RnpwZVBQZGoyM1Y4R3ZDeDdPWXZJc3dlIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730721),
('qChC5zy0tccUuRQAfXeuMxkuwejE3hKqylPZDRkb', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiI3dm5CbUlqWk1QTU5hclRSWlpLNm1VdGJuWXZzdDVlUEhicFVCNFBqIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730711),
('WYllEUcK0cRoeJmPoydZBrTnTp9PJKtSfvJTyk7A', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiI3em9KQzVkaWNkRjVwWWJzUDB0dVBtMEJBVzd6TEhPTTZqZGNHaUJFIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730230),
('xIz2TYuwrcxTon2bbsMP1p3PK2pxG7QDY967MRv2', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJ5ZDloVjdrUnhINXNkaEY0anVYeXAyZTVzSXlhSFNvWjFxT09sVEx2IiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730622),
('XKDHnOSQ1AgwKOZ125H0OmgoJuVb5a5r6QE5HOQ9', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJMYmplbW5jVTNaT2hJVjlnR3JPSk9Idk9GNmlJVXZjb094dk51YUMzIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730944),
('zXo2LQm50xie3kniFgWZiuEbW58s4qWJ4cvRDYZd', NULL, '127.0.0.1', 'Veritrans', 'eyJfdG9rZW4iOiJsV0kyTUtEUURsTVVtWFQyR2pDdnBFRHIyeldzdkd2OWxjY1JZMnIyIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1777730372);

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('pending','active') NOT NULL DEFAULT 'pending',
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `whatsapp` varchar(20) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `open_time` time DEFAULT NULL,
  `close_time` time DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `closed_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `user_id`, `name`, `slug`, `logo`, `description`, `image`, `status`, `balance`, `whatsapp`, `instagram`, `open_time`, `close_time`, `is_active`, `closed_until`, `created_at`, `updated_at`) VALUES
(4, 12, 'gondong warungku', 'gondong-warungku', 'logos/4pDInnhGIpkER3CLJzNK207tcfCcJZmjq0R4eCr5.jpg', NULL, NULL, 'active', 0.00, '6285870536367', 'msdrhlmi_', NULL, NULL, 1, NULL, '2026-04-20 09:37:30', '2026-04-20 09:49:23'),
(5, 13, 'Shelter 2 \"pak gendut\"', 'shelter-2-pak-gendut', 'logos/SVFcLF6KmHI8qu5l0IA1y0T8aNmMmJrzkAzxRXeV.png', NULL, NULL, 'active', 100000.00, '62857263113000', 'fathinnryfsa', '09:30:00', '23:59:00', 1, NULL, '2026-04-20 09:52:11', '2026-05-02 15:39:16'),
(7, 15, 'shelter 2', 'shelter-2', 'logos/N1wkWCng1B3ytzCVbPrDyJfz2kYFf9ov3ZutoosV.jpg', NULL, NULL, 'active', 0.00, '6285870536367', 'msdrhlmi_', NULL, NULL, 1, NULL, '2026-04-21 18:32:27', '2026-04-21 18:32:27'),
(8, 16, 'shelter 12', NULL, 'logos/Ym3PZk0IrJfjljvVQrJ28BQ26ONPiy8fVQXM3DvQ.jpg', NULL, NULL, 'pending', 0.00, '2341', 'fathinnryfsa', '20:04:00', '20:06:00', 1, NULL, '2026-04-22 01:12:44', '2026-05-02 15:29:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@gmail.com', NULL, '$2y$12$PYHLtncMCVacx4iqs3MNo.xE8exHJI28i5W1jxQK6jZvSkGJs9Wkm', 'admin', NULL, '2026-04-19 23:36:48', '2026-04-19 23:36:48'),
(12, 'gondrong', 'gondrong@gmail.com', NULL, '$2y$12$ZnCulsJosW.gw2YYdyipV.hOfo33EjPKHUD22SevSo9edWxtrOc5u', 'owner', NULL, '2026-04-20 09:36:34', '2026-04-20 09:36:34'),
(13, 'fatin', 'fathin@gmail.com', NULL, '$2y$12$7ZKGUKGk/EETnphh/K8kqOV/VisVRTo2/BS.yMPntRgRDG/WvAmxW', 'owner', NULL, '2026-04-20 09:51:39', '2026-04-20 09:51:39'),
(15, 'shelter 2', 'tomi@gmail.com', NULL, '$2y$12$eXc2Fydx.bQr98sFdfbSJOdRuPrrMJrXwe.F9qY2AtxXqb1s0XHIy', 'owner', NULL, '2026-04-21 18:30:57', '2026-04-21 18:30:57'),
(16, 'aldo', 'aldo@gmail.com', NULL, '$2y$12$VLSL0ilUXoAOyf297FNO/OykjzUE0Vwyhu.uGP1HT/T9omUipGh/C', 'owner', NULL, '2026-04-22 01:11:21', '2026-04-22 01:11:21'),
(17, 'Masdar Helmi', 'masdarhelmi23@gmail.com', NULL, '$2y$12$j35ivsraFdZZooa2oylOfOoiJZ9NmIK3jdGH2fmogyTyeV7yQbCLK', 'customer', NULL, '2026-04-22 05:12:39', '2026-04-27 19:22:56'),
(18, 'helmi', 'masdarhelmi2301@gmail.com', NULL, '$2y$12$vvZutpzSMDh33JhIVTEqDeur1TOvnJdHmZPo0lOqhykPNmDGX6jsi', 'customer', NULL, '2026-04-22 11:08:22', '2026-05-02 13:56:36');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shop_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `bank_info` text NOT NULL,
  `status` enum('pending','success','rejected') NOT NULL DEFAULT 'pending',
  `transferred_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `shop_id`, `amount`, `bank_info`, `status`, `transferred_at`, `created_at`, `updated_at`) VALUES
(1, 5, 50000.00, 'BCA - 0854545456-masdar', 'success', NULL, '2026-05-02 14:39:47', '2026-05-02 14:58:35'),
(2, 5, 50000.00, 'BCA - 0865654 - Masdar helmi', 'rejected', NULL, '2026-05-02 14:40:12', '2026-05-02 14:57:56'),
(3, 5, 50000.00, 'BRI - 12361723- helmi', 'success', NULL, '2026-05-02 15:26:05', '2026-05-02 15:39:16'),
(4, 5, 30000.00, 'vgg', 'pending', NULL, '2026-05-02 15:45:33', '2026-05-02 15:45:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_product_id_foreign` (`product_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_product_id_foreign` (`product_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_foreign` (`order_id`),
  ADD KEY `order_details_product_id_foreign` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_shop_id_foreign` (`shop_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shops_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `withdrawals_shop_id_foreign` (`shop_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shops`
--
ALTER TABLE `shops`
  ADD CONSTRAINT `shops_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD CONSTRAINT `withdrawals_shop_id_foreign` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
