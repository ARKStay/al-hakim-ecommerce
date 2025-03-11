-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 11, 2025 at 12:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kkp`
--

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `banners`
--

INSERT INTO `banners` (`id`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'product-banners/ELeo4UlLsTqgUomxAee4cZE8JQopW82aNSR2ZVkk.png', 'on', '2025-02-24 13:28:18', '2025-02-24 13:28:18'),
(2, 'product-banners/qBxKa7V1UBtVuycThOLRe3Nb4JQVknp6bVpXU8v7.png', 'on', '2025-02-24 13:29:16', '2025-02-24 13:29:16');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `price` int NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 10, 13338, 'completed', '2025-02-24 09:23:51', '2025-02-24 09:25:17'),
(2, 10, 1101, 'completed', '2025-02-24 09:26:58', '2025-02-24 09:27:23'),
(3, 10, 1060, 'completed', '2025-02-24 09:27:43', '2025-02-24 09:28:00'),
(4, 10, 254900, 'completed', '2025-02-24 13:39:55', '2025-02-24 13:40:47'),
(5, 10, 237600, 'completed', '2025-02-24 13:45:53', '2025-02-26 16:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `cart__items`
--

CREATE TABLE `cart__items` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart__items`
--

INSERT INTO `cart__items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 19, 13338, '2025-02-24 09:23:51', '2025-02-24 09:23:51'),
(2, 2, 2, 1, 90, '2025-02-24 09:26:58', '2025-02-24 09:26:58'),
(3, 2, 3, 3, 1011, '2025-02-24 09:27:07', '2025-02-24 09:27:07'),
(4, 3, 4, 4, 1060, '2025-02-24 09:27:43', '2025-02-24 09:27:43'),
(5, 4, 4, 1, 33900, '2025-02-24 13:39:55', '2025-02-24 13:39:55'),
(6, 4, 10, 2, 221000, '2025-02-24 13:40:06', '2025-02-24 13:40:06'),
(7, 5, 2, 1, 69600, '2025-02-24 13:45:53', '2025-02-24 13:45:53'),
(8, 5, 7, 5, 168000, '2025-02-26 16:22:57', '2025-02-26 16:22:57');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Prayer Mat', '2025-02-24 09:20:04', '2025-02-24 12:14:28'),
(2, 'Thobe', '2025-02-24 09:20:04', '2025-02-24 12:14:43'),
(3, 'Sirwal Pants', '2025-02-24 09:20:04', '2025-02-24 12:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_11_05_000000_create_categories_table', 1),
(5, '2024_11_05_000001_create_sizes_table', 1),
(6, '2024_11_06_144614_create_products_table', 1),
(7, '2024_12_16_073722_create_banners_table', 1),
(8, '2024_12_29_162904_create_carts_table', 1),
(9, '2024_12_31_100133_create_cart__items_table', 1),
(10, '2025_01_04_173927_create_orders_table', 1),
(11, '2025_01_06_144424_create_ratings_table', 1),
(12, '2025_02_23_175137_create_order_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `total_price` int NOT NULL,
  `shipping_method` varchar(255) NOT NULL,
  `shipping_cost` int NOT NULL DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `order_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `cart_id`, `total_price`, `shipping_method`, `shipping_cost`, `image`, `payment_status`, `order_status`, `created_at`, `updated_at`) VALUES
(3, 10, 2, 13101, 'J&T', 12000, 'payment-proofs/zP8QkgHVLHyyplUMGyCA7YTbB7pwcelP1kY8sEyB.png', 'approved', 'complete', '2025-02-24 09:27:23', '2025-02-24 12:03:46'),
(4, 10, 3, 12060, 'Sicepat', 11000, 'payment-proofs/xyeQJTX3iTHr2iJqOmkrwoB7h10q1GG6iiM0dlW3.png', 'rejected', 'pending', '2025-02-24 09:28:00', '2025-02-24 10:43:42'),
(5, 10, 4, 266900, 'J&T', 12000, 'payment-proofs/VH74JQdhkITQzGSrfawcJcVungth4rIL3fJ1qB83.svg', 'approved', 'complete', '2025-02-24 13:40:47', '2025-02-24 13:41:12'),
(6, 10, 5, 252600, 'JNE', 15000, 'payment-proofs/x2S7vJgj26iLg78sKDh6PPY8FVp54BstqzICfity.jpg', 'approved', 'pending', '2025-02-26 16:33:31', '2025-02-26 16:48:19');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` int NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `sizes_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `color`, `sizes_id`, `created_at`, `updated_at`) VALUES
(2, 3, 2, 1, 90, 'ut dolor ad', 2, '2025-02-24 09:27:23', '2025-02-24 09:27:23'),
(3, 3, 3, 3, 337, 'necessitatibus dolorem molestiae', 4, '2025-02-24 09:27:23', '2025-02-24 09:27:23'),
(4, 4, 4, 4, 265, 'similique inventore dolorem', 2, '2025-02-24 09:28:00', '2025-02-24 09:28:00'),
(5, 5, 4, 1, 33900, 'Flowers', 5, '2025-02-24 13:40:47', '2025-02-24 13:40:47'),
(6, 5, 10, 2, 110500, 'Mocca', 3, '2025-02-24 13:40:47', '2025-02-24 13:40:47'),
(7, 6, 2, 1, 69600, 'Grey White', 3, '2025-02-26 16:33:31', '2025-02-26 16:33:31'),
(8, 6, 7, 5, 33600, 'Tartan-03', 5, '2025-02-26 16:33:31', '2025-02-26 16:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `color` varchar(255) NOT NULL,
  `description` text,
  `price` int NOT NULL,
  `stock` int UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `sizes_id` bigint UNSIGNED DEFAULT NULL,
  `average_rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_ratings` bigint UNSIGNED NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `image`, `color`, `description`, `price`, `stock`, `category_id`, `sizes_id`, `average_rating`, `total_ratings`, `created_at`, `updated_at`) VALUES
(1, 'Premium Combination Thobe', 'premium-combination-thobe-8030', 'product-images/AGf8u2PzpYcnoTzwbyYtXqfb8Ymz5z8ZD639dWEN.webp', 'Brown Black', '<div><strong>🕌 Premium Combination Thobe – Stylish Men\'s Gamis</strong></div><div>✨ <strong>Material:</strong> Bestway Premium Grade A – Cool, comfortable, and high quality.</div><div>📌 <strong>Product Details:</strong><br>✔️ Modern two-tone design<br>✔️ Elegant button closure on top<br>✔️ Practical side pocket</div><div>📏 <strong>Sizes:</strong></div><ul><li><strong>L</strong> → Chest 116 cm | Sleeve length 56 cm | Thobe length 130 cm</li></ul><div>🎨 <strong>Color Variants:</strong><br>✅ Brown-Black</div><div>💎 <strong>Key Features:</strong><br>✔️ Real product photos 📸<br>✔️ Neat stitching &amp; comfortable fit<br>✔️ Lightweight &amp; breathable fabric</div><div>🔥 <strong>A modern thobe with an elegant look, perfect for daily wear!</strong></div>', 69600, 50, 2, 3, '0.00', 0, '2025-02-24 09:20:04', '2025-02-24 12:18:56'),
(2, 'Premium Combination Thobe', 'premium-combination-thobe-2227', 'product-images/nWeIh1IEmV5PngxxxywhPkm8fzkHQh0dSoiKQc0Y.webp', 'Grey White', '<div><strong>🕌 Premium Combination Thobe – Stylish Men\'s Gamis</strong></div><div>✨ <strong>Material:</strong> Bestway Premium Grade A – Cool, comfortable, and high quality.</div><div>📌 <strong>Product Details:</strong><br>✔️ Modern two-tone design<br>✔️ Elegant button closure on top<br>✔️ Practical side pocket</div><div>📏 <strong>Sizes:</strong></div><ul><li><strong>L</strong> → Chest 116 cm | Sleeve length 56 cm | Thobe length 130 cm</li></ul><div>🎨 <strong>Color Variants:</strong><br>✅ Grey White</div><div>💎 <strong>Key Features:</strong><br>✔️ Real product photos 📸<br>✔️ Neat stitching &amp; comfortable fit<br>✔️ Lightweight &amp; breathable fabric</div><div>🔥 <strong>A modern thobe with an elegant look, perfect for daily wear!</strong></div>', 69600, 49, 2, 3, '5.00', 1, '2025-02-24 09:20:04', '2025-02-26 16:33:31'),
(3, 'Premium Combination Thobe', 'premium-combination-thobe-8903', 'product-images/Cdi2ixrcLQYFay57T8VwOhZ0ugcLWvXf6AZlkU6X.webp', 'Navy Grey', '<div><strong>🕌 Premium Combination Thobe – Stylish Men\'s Gamis</strong></div><div>✨ <strong>Material:</strong> Bestway Premium Grade A – Cool, comfortable, and high quality.</div><div>📌 <strong>Product Details:</strong><br>✔️ Modern two-tone design<br>✔️ Elegant button closure on top<br>✔️ Practical side pocket</div><div>📏 <strong>Sizes:</strong></div><ul><li><strong>L</strong> → Chest 116 cm | Sleeve length 56 cm | Thobe length 130 cm</li></ul><div>🎨 <strong>Color Variants:</strong><br>✅ Navy Grey</div><div>💎 <strong>Key Features:</strong><br>✔️ Real product photos 📸<br>✔️ Neat stitching &amp; comfortable fit<br>✔️ Lightweight &amp; breathable fabric</div><div>🔥 <strong>A modern thobe with an elegant look, perfect for daily wear!</strong></div>', 69600, 50, 2, 3, '5.00', 1, '2025-02-24 09:20:04', '2025-02-24 13:42:28'),
(4, 'Travel Prayer Mat', 'travel-prayer-mat-6750', 'product-images/MdIxIWUkKxt15sL1Urg9gpCheSZw2n7wV3vJgx1D.webp', 'Flowers', '<div><strong>🕌 Travel Prayer Mat – Compact &amp; Stylish</strong></div><div>Practical and lightweight, easy to carry anywhere and fits perfectly in your bag. Ideal as a <strong>gift for Hajj, Umrah, weddings, gatherings, and religious events.</strong></div><div>📌 <strong>Specifications:</strong><br>✔️ <strong>Mat Size:</strong> 107 x 55 cm<br>✔️ <strong>Pouch Size:</strong> 4 x 14 cm<br>✔️ <strong>Ultrasonic Edge Cutting</strong> for a sleek finish<br>✔️ <strong>Material:</strong> Premium Pollymicro<br>✔️ <strong>Pouch Model:</strong> Roll-up with a hanging loop</div><div>✨ <strong>A perfect blend of convenience, elegance, and comfort!</strong></div>', 33900, 89, 1, 5, '5.00', 1, '2025-02-24 09:20:04', '2025-02-24 13:41:30'),
(5, 'Travel Prayer Mat', 'travel-prayer-mat-7067', 'product-images/aEewJMWn7CFKlOy1pBdR5MeTNsDEfXa5g1bjQN7b.webp', 'Tartan-01', '<div><strong>🕌 Travel Prayer Mat – Compact &amp; Stylish</strong></div><div>Practical and lightweight, easy to carry anywhere and fits perfectly in your bag. Ideal as a <strong>gift for Hajj, Umrah, weddings, gatherings, and religious events.</strong></div><div>📌 <strong>Specifications:</strong><br>✔️ <strong>Mat Size:</strong> 107 x 55 cm<br>✔️ <strong>Pouch Size:</strong> 4 x 14 cm<br>✔️ <strong>Ultrasonic Edge Cutting</strong> for a sleek finish<br>✔️ <strong>Material:</strong> Premium Pollymicro<br>✔️ <strong>Pouch Model:</strong> Roll-up with a hanging loop</div><div>✨ <strong>A perfect blend of convenience, elegance, and comfort!</strong></div>', 33900, 90, 1, 5, '0.00', 0, '2025-02-24 09:20:04', '2025-02-24 13:08:00'),
(6, 'Travel Prayer Mat', 'travel-prayer-mat-1302', 'product-images/xwAE24bbKdM4u4DPryTlB5ER7rkaB3CjfeYuL5qV.webp', 'Tartan-02', '<div><strong>🕌 Travel Prayer Mat – Compact &amp; Stylish</strong></div><div>Practical and lightweight, easy to carry anywhere and fits perfectly in your bag. Ideal as a <strong>gift for Hajj, Umrah, weddings, gatherings, and religious events.</strong></div><div>📌 <strong>Specifications:</strong><br>✔️ <strong>Mat Size:</strong> 107 x 55 cm<br>✔️ <strong>Pouch Size:</strong> 4 x 14 cm<br>✔️ <strong>Ultrasonic Edge Cutting</strong> for a sleek finish<br>✔️ <strong>Material:</strong> Premium Pollymicro<br>✔️ <strong>Pouch Model:</strong> Roll-up with a hanging loop</div><div>✨ <strong>A perfect blend of convenience, elegance, and comfort!</strong></div>', 33600, 90, 1, 5, '0.00', 0, '2025-02-24 09:20:04', '2025-02-24 13:12:56'),
(7, 'Travel Prayer Mat', 'travel-prayer-mat-6580', 'product-images/SPkKmXkHiz8NBOftg3u2leji9fTuNSp3l8gVgN7u.webp', 'Tartan-03', '<div><strong>🕌 Travel Prayer Mat – Compact &amp; Stylish</strong></div><div>Practical and lightweight, easy to carry anywhere and fits perfectly in your bag. Ideal as a <strong>gift for Hajj, Umrah, weddings, gatherings, and religious events.</strong></div><div>📌 <strong>Specifications:</strong><br>✔️ <strong>Mat Size:</strong> 107 x 55 cm<br>✔️ <strong>Pouch Size:</strong> 4 x 14 cm<br>✔️ <strong>Ultrasonic Edge Cutting</strong> for a sleek finish<br>✔️ <strong>Material:</strong> Premium Pollymicro<br>✔️ <strong>Pouch Model:</strong> Roll-up with a hanging loop</div><div>✨ <strong>A perfect blend of convenience, elegance, and comfort!</strong></div>', 33600, 85, 1, 5, '0.00', 0, '2025-02-24 09:20:04', '2025-02-26 16:33:31'),
(8, 'Chino Sirwal Pants', 'chino-sirwal-pants-6146', 'product-images/BAmyapWwYpduzpYqnxWSpWppdrUgIcP8gX2CYpo8.webp', 'Hitam', '<div><strong>🕌 Chino Sirwal Pants – Modest &amp; Stylish</strong></div><div>Designed for young men embracing a <strong>modern yet Islamic lifestyle</strong>, combining fashion with comfort. Perfect for <strong>daily wear, campus, study groups, mosque visits, casual hangouts, and formal events</strong> while staying modest.</div><div>📌 <strong>Product Features:</strong><br>✔️ 2 front pockets<br>✔️ 2 back cargo pockets</div><div>🎨 <strong>Colors:</strong><br>✅ Black</div><div>✨ <strong>Material:</strong> Premium Stretch Twill Cotton – <strong>Thick, soft, smooth, and flexible, yet breathable.</strong></div><div>📏 <strong>Size L:</strong></div><ul><li>Waist: <strong>78-92 cm (adjustable)</strong></li><li>Thigh: <strong>62 cm</strong></li><li>Length: <strong>89 cm</strong></li><li>Leg opening: <strong>38 cm</strong></li></ul><div>⚡ <strong>Guaranteed high-quality fabric &amp; neat stitching!</strong></div>', 110500, 50, 3, 3, '0.00', 0, '2025-02-24 09:20:04', '2025-02-24 13:20:38'),
(9, 'Chino Sirwal Pants', 'chino-sirwal-pants-1698', 'product-images/tbQQoxLHZksKaj1NRDl51KD75bXMnjxRLX7h8vbz.webp', 'Cream', '<div><strong>🕌 Chino Sirwal Pants – Modest &amp; Stylish</strong></div><div>Designed for young men embracing a <strong>modern yet Islamic lifestyle</strong>, combining fashion with comfort. Perfect for <strong>daily wear, campus, study groups, mosque visits, casual hangouts, and formal events</strong> while staying modest.</div><div>📌 <strong>Product Features:</strong><br>✔️ 2 front pockets<br>✔️ 2 back cargo pockets</div><div>🎨 <strong>Colors:</strong><br>✅ Cream</div><div>✨ <strong>Material:</strong> Premium Stretch Twill Cotton – <strong>Thick, soft, smooth, and flexible, yet breathable.</strong></div><div>📏 <strong>Size L:</strong></div><ul><li>Waist: <strong>78-92 cm (adjustable)</strong></li><li>Thigh: <strong>62 cm</strong></li><li>Length: <strong>89 cm</strong></li><li>Leg opening: <strong>38 cm</strong></li></ul><div>⚡ <strong>Guaranteed high-quality fabric &amp; neat stitching!</strong></div>', 110500, 50, 3, 3, '0.00', 0, '2025-02-24 09:20:04', '2025-02-24 13:22:36'),
(10, 'Chino Sirwal Pants', 'chino-sirwal-pants-7877', 'product-images/nMlHCqHFTskkgza9VNLass5prW6sIAyATe0CAaka.webp', 'Mocca', '<div><strong>🕌 Chino Sirwal Pants – Modest &amp; Stylish</strong></div><div>Designed for young men embracing a <strong>modern yet Islamic lifestyle</strong>, combining fashion with comfort. Perfect for <strong>daily wear, campus, study groups, mosque visits, casual hangouts, and formal events</strong> while staying modest.</div><div>📌 <strong>Product Features:</strong><br>✔️ 2 front pockets<br>✔️ 2 back cargo pockets</div><div>🎨 <strong>Colors:</strong><br>✅ Mocca</div><div>✨ <strong>Material:</strong> Premium Stretch Twill Cotton – <strong>Thick, soft, smooth, and flexible, yet breathable.</strong></div><div>📏 <strong>Size L:</strong></div><ul><li>Waist: <strong>78-92 cm (adjustable)</strong></li><li>Thigh: <strong>62 cm</strong></li><li>Length: <strong>89 cm</strong></li><li>Leg opening: <strong>38 cm</strong></li></ul><div>⚡ <strong>Guaranteed high-quality fabric &amp; neat stitching!</strong></div>', 110500, 48, 3, 3, '5.00', 1, '2025-02-24 09:20:04', '2025-02-24 13:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint UNSIGNED NOT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(3, 10, 2, 5, 'Great quality and stylish design! The materials are comfortable, durable, and exactly as described. Perfect for daily wear and special occasions. Will definitely purchase again!', '2025-02-24 13:42:28', '2025-02-24 13:42:28'),
(4, 10, 3, 5, 'Great quality and stylish design! The materials are comfortable, durable, and exactly as described. Perfect for daily wear and special occasions. Will definitely purchase again!', '2025-02-24 13:42:28', '2025-02-24 13:42:28');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6tTFPROUYYdLyeQjMFBUnpVKqcYT7NRxvK6zSkYe', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMzRaVFBWUXI5MklkS3R5ZG5jSDc0ZTdiaWpESDZXMk1mNE56RlZYayI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozODoiaHR0cDovL3Byb2play1ra3AudGVzdC9kYXNoYm9hcmQvdXNlcnMiO319', 1740411531),
('7bF6MQzQFfK1xuiPzpzR6T1pGml8FVxHZXdCoyjW', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid05lZ3pOU2hiM3kzUDBpTno3eXJLV0QzU2ZNa1BzS2VIaG9waXRUdyI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9wcm9qZWsta2twLnRlc3QvdXNlci9oaXN0b3J5Ijt9fQ==', 1740589804),
('beR4pZUrPmi0M85BtYv3EIuMQ3GRL5qGt0daBAf7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib3lRaHJYdmpMUTE5VFdZY1BXaFVLSWlvWHE3ZFlWS0E5eVB0SG54cyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzE6Imh0dHA6Ly9wcm9qZWsta2twLnRlc3QvcHJvZHVjdHMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1740623999),
('lLMu2l4YfwBNf5bdj2GJpbikvsUHbXn5oGX4DcjS', 10, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSlNLMFg4cWJmV2owNXJZNEtwT1JlcDVFSkYwazNzMXkxSEFJNnlaMCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTA7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njc6Imh0dHA6Ly9wcm9qZWsta2twLnRlc3QvdXNlci9wcm9kdWN0cy9wcmVtaXVtLWNvbWJpbmF0aW9uLXRob2JlLTIyMjciO319', 1741455480),
('QogEZepnOEC5QJ7FUfYV6SwffGNfcheawnesrots', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/133.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVUhkTDFYa2xld3dIV01kUjl5VVlSYUZJWG1hcnFvR0htRTNkQUtYZiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6OTtzOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czo0NzoiaHR0cDovL3Byb2play1ra3AudGVzdC9kYXNoYm9hcmQvYmFubmVycy9jcmVhdGUiO319', 1740404979);

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'S', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(2, 'M', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(3, 'L', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(4, 'XL', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(5, '-', '2025-02-24 12:15:43', '2025-02-24 12:15:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `phone`, `address`, `remember_token`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Melisa Waelchi', 'eduardo.conn', 'marquesdooley@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '+1 (641) 954-7983', '483 Johns Brooks Suite 849\nNorth Diana, MT 57921-4139', 'HEV1cGoWeR', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(2, 'Mr. Ephraim Haag Jr.', 'wsatterfield', 'eileenupton@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '+19548966855', '191 Kassandra Prairie Apt. 997\nNew Adrainmouth, DE 01126-0012', 'T9OOBMg6nS', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(3, 'Dion Goodwin', 'constance41', 'mariampowlowski@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '+1-870-361-8444', '751 Collier Dale\nNew Noble, KY 06946', 'yrrczqxhlC', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(4, 'Cruz Padberg I', 'alison.jast', 'filomenabarton@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '386-506-4821', '822 Nels Creek Suite 670\nNew Brandiberg, VA 88613-8636', 'oXeRb3Tw68', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(5, 'Theron Hahn', 'bmckenzie', 'ignatiusstehr@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '419.451.7248', '14793 Maia Extensions Apt. 512\nLake Adonischester, ID 70876', 'Roo4njEX2t', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(6, 'Dr. Michael Kuphal', 'blick.deondre', 'hayleymuller@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '725.896.7225', '4705 Herman Prairie Suite 299\nAdamhaven, KY 04206-6960', 'p99agQIRig', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(7, 'Liam Lakin', 'mekhi53', 'generalbednar@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '917-872-0755', '96848 Mable Valley\nNorth Estella, MA 52713', 'gRjhezFGfv', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(8, 'Camille Fahey II', 'donnelly.bennie', 'magdalenapfannerstill@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '+1 (505) 530-0358', '5270 Cyrus Shore Apt. 645\nNew Garry, ME 65811-1430', 'pztaqGpL60', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(9, 'ADMIN', 'admin', 'admin@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '630-217-8950', '32241 Sigmund Point Suite 553\nSchultztown, CA 69427', 'CqP60fquEWjuIejZUlB8e03r9zA6EJeAHKMlbQrbcnwHAGydfJLJHMV05c5K', 'admin', '2025-02-24 09:20:04', '2025-02-24 09:20:04'),
(10, 'Daffa', 'daffa', 'daffa@gmail.com', '2025-02-24 09:20:04', '$2y$12$pJlQxhGoqCfOiK6Z8oU1VOn7aGlb/igTs/PYhGnwhIQOY/RbMkR.G', '1-520-807-6347', '422 Buckridge Terrace Suite 085\nWest Josiah, WA 29202-1997', 'kFDM309ldRrzUJCaxixonJJGhhu5hA3sQsXECDKfRJijeQaVaoz13pDN6n9e', 'user', '2025-02-24 09:20:04', '2025-02-24 09:20:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart__items`
--
ALTER TABLE `cart__items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart__items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart__items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

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
  ADD KEY `orders_cart_id_foreign` (`cart_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_sizes_id_foreign` (`sizes_id`);

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
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_sizes_id_foreign` (`sizes_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ratings_user_id_foreign` (`user_id`),
  ADD KEY `ratings_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart__items`
--
ALTER TABLE `cart__items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart__items`
--
ALTER TABLE `cart__items`
  ADD CONSTRAINT `cart__items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart__items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_sizes_id_foreign` FOREIGN KEY (`sizes_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_sizes_id_foreign` FOREIGN KEY (`sizes_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
