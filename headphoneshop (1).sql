-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th3 20, 2026 lúc 10:52 AM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `headphoneshop`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `slug`, `photo`, `description`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Tai nghe chính hãng', 'tai-nghe-chinh-hang', '/storage/photos/33/Banner/airpods_banner11_043426204bcf45bbbc5d7a76db7ef5b5.jpg', '<h2>Âm thanh rõ, giá tốt mỗi ngày</h2>', 'active', '2020-08-14 01:50:23', '2026-03-15 05:44:21'),
(4, 'Miễn phí giao hàng đơn trên 1.000.000đ', 'mien-phi-giao-hang', '/storage/photos/33/Banner/Banner-free-shop.jpg', '<h2>Giao nhanh toàn quốc cho đơn đủ điều kiện</h2>', 'active', '2020-08-17 20:46:59', '2026-03-15 05:45:03'),
(5, 'Giảm đến 15% tai nghe Bluetooth', 'giam-gia-tai-nghe-bluetooth', '/storage/photos/33/Banner/tainghe_1280x486-800-resize.png', '<h2>Ưu đãi nổi bật cho dòng true wireless và over-ear</h2>', 'active', '2022-04-10 09:30:00', '2026-03-15 05:45:52');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Sony', 'sony', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(9, 'JBL', 'jbl', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(10, 'Sennheiser', 'sennheiser', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(11, 'SoundPEATS', 'soundpeats', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `status` enum('new','progress','delivered','cancel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `quantity` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `carts`
--

INSERT INTO `carts` (`id`, `product_id`, `order_id`, `user_id`, `price`, `status`, `quantity`, `amount`, `created_at`, `updated_at`) VALUES
(36, 26, 19, 41, 1200000.00, 'new', 1, 1200000.00, '2025-03-27 06:16:26', '2025-03-27 06:23:38'),
(37, 26, 20, 42, 1200000.00, 'new', 1, 1200000.00, '2025-03-27 07:24:57', '2025-03-27 07:25:48'),
(38, 26, 21, 42, 1200000.00, 'new', 1, 1200000.00, '2026-03-15 06:58:07', '2026-03-15 07:00:00'),
(39, 27, 22, 43, 1823600.00, 'new', 1, 1880000.00, '2026-03-16 07:41:34', '2026-03-16 07:42:43'),
(40, 24, 23, 43, 2511000.00, 'new', 1, 2790000.00, '2026-03-18 23:19:06', '2026-03-18 23:28:16'),
(41, 27, 24, 44, 1823600.00, 'new', 1, 1880000.00, '2026-03-20 02:14:19', '2026-03-20 02:16:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_parent` tinyint(1) NOT NULL DEFAULT 1,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `title`, `slug`, `summary`, `photo`, `is_parent`, `parent_id`, `added_by`, `status`, `created_at`, `updated_at`) VALUES
(26, 'Tai nghe chụp tai', 'tai-nghe-chup-tai', '<p><span style=\"color: rgb(15, 23, 42); font-family: CoinyRegular; font-size: 40px; text-align: center; text-transform: uppercase;\">Tai nghe chụp tai</span></p>', '/storage/photos/33/Product_Category/images.jpg', 1, NULL, NULL, 'active', '2026-03-15 09:00:00', '2026-03-15 05:37:14'),
(27, 'Tai nghe true wireless', 'tai-nghe-true-wireless', '<p><span style=\"color: rgb(15, 23, 42); font-family: CoinyRegular; font-size: 40px; text-align: center; text-transform: uppercase;\">Tai nghe true wireless</span></p>', '/storage/photos/33/Product_Category/jbl-tune-230nc-tws-tainghetot-2.jpg', 1, NULL, NULL, 'active', '2026-03-15 09:00:00', '2026-03-15 05:40:34'),
(28, 'Phụ kiện âm thanh', 'phu-kien-am-thanh', '<p><span style=\"color: rgb(15, 23, 42); font-family: CoinyRegular; font-size: 40px; text-align: center; text-transform: uppercase;\">Phụ kiện âm thanh</span></p>', '/storage/photos/33/Product_Category/images (1).jpg', 1, NULL, NULL, 'active', '2026-03-15 09:00:00', '2026-03-15 05:41:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chatbot_faqs`
--

CREATE TABLE `chatbot_faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_text` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chatbot_faqs`
--

INSERT INTO `chatbot_faqs` (`id`, `question`, `keywords`, `answer`, `link_text`, `link_url`, `priority`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Giao hàng', 'giao hàng,ship,vận chuyển,phí ship,freeship,thời gian giao hàng', 'Shop hỗ trợ giao hàng toàn quốc. Tùy khu vực và sản phẩm, thời gian giao có thể khác nhau. Nếu bạn đã chọn được mẫu phù hợp, mình có thể dẫn bạn đến trang sản phẩm để xem chi tiết trước khi đặt.', 'Xem sản phẩm', '/product-grids', 1, 'active', '2026-03-15 06:04:55', '2026-03-15 06:04:55'),
(2, 'Đổi trả', 'đổi trả,hoàn trả,bảo hành,chính sách đổi trả,sản phẩm lỗi', 'Shop có hỗ trợ đổi trả theo tình trạng đơn hàng và sản phẩm. Nếu bạn cần kiểm tra trường hợp cụ thể như lỗi kỹ thuật, đổi mẫu hoặc xác nhận điều kiện áp dụng, hãy liên hệ trực tiếp để được hỗ trợ nhanh nhất.', 'Trang liên hệ', '/contact', 2, 'active', '2026-03-15 06:04:55', '2026-03-15 06:04:55'),
(3, 'Liên hệ', 'liên hệ,số điện thoại,email,địa chỉ,hỗ trợ,tư vấn', 'Mình có thể gửi ngay số điện thoại, email và địa chỉ của shop trong khung chat này. Nếu bạn muốn trao đổi kỹ hơn về sản phẩm hoặc đơn hàng, bạn cũng có thể mở trang liên hệ.', 'Mở liên hệ', '/contact', 3, 'active', '2026-03-15 06:04:55', '2026-03-15 06:04:55'),
(4, 'Theo dõi đơn hàng', 'theo dõi đơn hàng,kiểm tra đơn,track order,mã đơn hàng,trạng thái đơn', 'Bạn có thể mở trang tra cứu đơn hàng để kiểm tra trạng thái xử lý và giao hàng. Nếu gặp khó khăn khi tra cứu, shop vẫn có thể hỗ trợ thêm qua trang liên hệ.', 'Tra cứu đơn hàng', '/product/track', 4, 'active', '2026-03-15 06:04:55', '2026-03-15 06:04:55'),
(5, 'Thanh toán', 'thanh toán,payment,trả tiền,phương thức thanh toán,cách thanh toán', 'Shop hỗ trợ đặt hàng trực tuyến theo quy trình có sẵn trên website. Nếu bạn đang phân vân trước bước thanh toán, mình có thể giúp bạn xem lại sản phẩm, giỏ hàng hoặc hướng dẫn sang trang liên hệ để được tư vấn thêm.', 'Xem giỏ hàng', '/cart', 5, 'active', '2026-03-15 06:04:55', '2026-03-15 06:04:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('fixed','percent') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `value` decimal(20,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `status`, `created_at`, `updated_at`) VALUES
(6, 'AUDIO10', 'percent', '10.00', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(7, 'FREESHIP', 'fixed', '30000.00', 'active', '2026-03-15 09:05:00', '2026-03-15 09:05:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2020_07_10_021010_create_brands_table', 1),
(5, '2020_07_10_025334_create_banners_table', 1),
(6, '2020_07_10_112147_create_categories_table', 1),
(7, '2020_07_11_063857_create_products_table', 1),
(8, '2020_07_12_073132_create_post_categories_table', 1),
(9, '2020_07_12_073701_create_post_tags_table', 1),
(10, '2020_07_12_083638_create_posts_table', 1),
(11, '2020_07_13_151329_create_messages_table', 1),
(12, '2020_07_14_023748_create_shippings_table', 1),
(13, '2020_07_15_054356_create_orders_table', 1),
(14, '2020_07_15_102626_create_carts_table', 1),
(15, '2020_07_16_041623_create_notifications_table', 1),
(16, '2020_07_16_053240_create_coupons_table', 1),
(17, '2020_07_23_143757_create_wishlists_table', 1),
(18, '2020_07_24_074930_create_product_reviews_table', 1),
(19, '2020_07_24_131727_create_post_comments_table', 1),
(20, '2020_08_01_143408_create_settings_table', 1),
(21, '2026_03_15_200000_create_chatbot_faqs_table', 2),
(22, '2026_03_15_203000_refresh_chatbot_faqs_content', 3),
(23, '2026_03_16_000000_add_momo_to_orders_payment_method_enum', 4),
(24, '2026_03_19_000100_mark_delivered_orders_as_paid', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0fb0a4f8-40f1-4295-a4af-6c9828c9200e', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"C\\u00f3 \\u0111\\u00e1nh gi\\u00e1 s\\u1ea3n ph\\u1ea9m m\\u1edbi!\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/san-pham\\/tai-nghe-bluetooth-soundpeats-air4-lite\",\"fas\":\"fa-star\"}', NULL, '2026-03-18 23:31:17', '2026-03-18 23:31:17'),
('14713b40-f2cf-4eba-8d6f-7c479ce258ed', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Có đánh giá sản phẩm mới!\",\"actionURL\":\"http://127.0.0.1:8000/product-detail/bo-dac-usb-c-va-hop-dung-tai-nghe-cao-cap\",\"fas\":\"fa-star\"}', NULL, '2026-03-15 09:20:00', '2026-03-15 09:20:00'),
('2f1b4112-9b01-4591-9d6f-939a3c0eb868', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Có đơn hàng mới\",\"actionURL\":\"http://127.0.0.1:8000/admin/order/20\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-15 09:25:00', '2026-03-15 09:25:00'),
('3d78f59e-babe-4b1e-b3c9-1b3bfa4cbf17', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Có đơn hàng mới\",\"actionURL\":\"http://127.0.0.1:8000/admin/order/19\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-15 09:26:00', '2026-03-15 09:26:00'),
('615f53cc-61ae-477b-83d5-59bf00562213', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Bình luận mới được tạo\",\"actionURL\":\"http://127.0.0.1:8000/blog-detail/meo-bao-quan-tai-nghe-ben-pin-ben-dem-tai\",\"fas\":\"fas fa-comment\"}', NULL, '2026-03-15 09:27:00', '2026-03-15 09:27:00'),
('729ecec3-aec0-4126-a7da-e35bf239b2c7', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"C\\u00f3 \\u0111\\u01a1n h\\u00e0ng m\\u1edbi\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/order\\/22\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-16 07:42:42', '2026-03-16 07:42:42'),
('7baeec7a-cf65-4b80-a494-dd7b0194203e', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"C\\u00f3 \\u0111\\u01a1n h\\u00e0ng m\\u1edbi\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/order\\/24\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-20 02:16:04', '2026-03-20 02:16:04'),
('97007b38-12d1-4c96-85ea-64bc41e9ec5a', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"C\\u00f3 \\u0111\\u01a1n h\\u00e0ng m\\u1edbi\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/order\\/23\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-18 23:28:15', '2026-03-18 23:28:15'),
('a6ec4c36-877c-43f1-bc86-049323812c0d', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Có đánh giá sản phẩm mới!\",\"actionURL\":\"http://127.0.0.1:8000/product-detail/tai-nghe-true-wireless-sennheiser-cx-plus-se\",\"fas\":\"fa-star\"}', NULL, '2026-03-15 09:28:00', '2026-03-15 09:28:00'),
('d2e8496b-c3ff-4ecc-a5ee-7a2e898df178', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"B\\u00ecnh lu\\u1eadn m\\u1edbi \\u0111\\u01b0\\u1ee3c t\\u1ea1o\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/bai-viet\\/tai-nghe-true-wireless-nao-hop-cho-nguoi-chay-bo\",\"fas\":\"fas fa-comment\"}', NULL, '2026-03-18 23:32:49', '2026-03-18 23:32:49'),
('e7c1d142-5f7f-4e77-bed9-fe416c04ec2a', 'AppNotificationsStatusNotification', 'AppUser', 33, '{\"title\":\"Bình luận mới được tạo\",\"actionURL\":\"http://127.0.0.1:8000/blog-detail/meo-bao-quan-tai-nghe-ben-pin-ben-dem-tai\",\"fas\":\"fas fa-comment\"}', NULL, '2026-03-15 09:29:00', '2026-03-15 09:29:00'),
('f388da47-0c68-4cf2-8dfa-e9b2fec72fea', 'App\\Notifications\\StatusNotification', 'App\\User', 33, '{\"title\":\"C\\u00f3 \\u0111\\u01a1n h\\u00e0ng m\\u1edbi\",\"actionURL\":\"http:\\/\\/127.0.0.1:8000\\/admin\\/order\\/21\",\"fas\":\"fa-file-alt\"}', NULL, '2026-03-15 07:00:00', '2026-03-15 07:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_number` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sub_total` double(10,2) NOT NULL,
  `shipping_id` bigint(20) UNSIGNED DEFAULT NULL,
  `coupon` double(10,2) DEFAULT NULL,
  `total_amount` double(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `payment_method` enum('cod','paypal','momo') NOT NULL DEFAULT 'cod',
  `payment_status` enum('paid','unpaid') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `status` enum('new','process','delivered','cancel') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `first_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `user_id`, `sub_total`, `shipping_id`, `coupon`, `total_amount`, `quantity`, `payment_method`, `payment_status`, `status`, `first_name`, `last_name`, `email`, `phone`, `country`, `post_code`, `address1`, `address2`, `created_at`, `updated_at`) VALUES
(19, 'ORD-1XZT3W6ZL6', 41, 1200000.00, 4, NULL, 1235000.00, 1, 'cod', 'paid', 'delivered', 'huyen', 'thu', 'huyen.lethi.05@gmail.com', '1247763378', 'VN', '100000', 'ha noi', NULL, '2025-03-27 06:23:38', '2025-03-27 06:33:23'),
(20, 'ORD-RP96RYVC7G', 42, 1200000.00, 2, NULL, 1230000.00, 1, 'cod', 'paid', 'delivered', 'Điều', 'Mạnh', 'manhdieu@gmail.com', '098765432', 'VN', '100000', '12 Ha noi', 'Hồ Hoàn Kiếm, Hàng Trống, Hoàn Kiếm, Hà Nội', '2025-03-27 07:25:48', '2025-03-27 07:31:16'),
(21, 'ORD-NDHQJLPXCN', 42, 1200000.00, 1, NULL, 1240000.00, 1, 'cod', 'paid', 'delivered', 'Mạnh Điều', 'Trần', 'manhdieu@gmail.com', '0522359440', 'VN', '10000', '33 Hà Nội', '33 Hà Nội', '2026-03-15 06:59:58', '2026-03-15 07:05:32'),
(22, 'ORD-C6WQG2LWPE', 43, 1880000.00, 1, NULL, 1920000.00, 1, 'cod', 'paid', 'delivered', 'Cảnh', 'Trần', 'canh@gmail.com', '098765432', 'VN', '1000000', '31 Hà Nội', '31 Hà Nội', '2026-03-16 07:42:39', '2026-03-19 00:01:00'),
(23, 'ORD-L15UZGXXR1', 43, 2790000.00, 1, NULL, 2830000.00, 1, 'momo', 'unpaid', 'new', 'Cảnh', 'Trần', 'canh@gmail.com', '098765432', 'VN', '1000000', '31 Hà Nội', '31 Hà Nội', '2026-03-18 23:28:12', '2026-03-18 23:28:12'),
(24, 'ORD-9KYLUIQHSO', 44, 1880000.00, NULL, NULL, 1880000.00, 1, 'momo', 'paid', 'delivered', 'Thu Hồng', 'Trần', 'hong@gmail.com', '098765432', 'VN', '100000', '78 Hà Nội', '78 Hà Nội', '2026-03-20 02:16:01', '2026-03-20 02:29:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('thinhtest222@gmail.com', '$2y$10$UpffYw4dpalbkPbK4U.G0eESzAPNL51Z4mMMKJa7Isx5h5Z/owehy', '2022-04-21 07:10:03'),
('thinhtest111@gmail.com', '$2y$10$Bs7ZIpjeJxaXkcZJVQsA2OdoPec6PNN6Co7CWD4OwEGeKnldJqgg2', '2022-04-21 07:15:47'),
('thinhphuongxa1@gmail.com', '$2y$10$zrt0Y16waKaRz4oX1sYgteMUFa2PGL9.ubqeQwA1f9nObdcg4MvXO', '2022-05-19 09:44:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quote` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `post_cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `post_tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `added_by` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `summary`, `description`, `quote`, `photo`, `tags`, `post_cat_id`, `post_tag_id`, `added_by`, `status`, `created_at`, `updated_at`) VALUES
(11, 'Chọn tai nghe chống ồn hay xuyên âm cho dân văn phòng?', 'chon-tai-nghe-chong-on-hay-xuyen-am-cho-dan-van-phong', '<p>Tai nghe chống ồn phù hợp khi bạn cần tập trung sâu, còn xuyên âm phù hợp nếu phải nghe môi trường xung quanh thường xuyên.</p>', '<h2>1. Khi nào nên chọn chống ồn chủ động?</h2><p>Nếu bạn làm việc ở văn phòng mở hoặc quán cà phê, chống ồn chủ động sẽ giúp giảm tiếng động nền và giữ sự tập trung tốt hơn.</p><h2>2. Khi nào nên ưu tiên xuyên âm?</h2><p>Xuyên âm phù hợp khi bạn cần nghe đồng nghiệp gọi, nghe thông báo hoặc quan sát giao thông trong lúc di chuyển.</p><h2>3. Gợi ý nhanh</h2><p>Nếu cần tập trung sâu, hãy ưu tiên chống ồn. Nếu cần tương tác linh hoạt với môi trường, xuyên âm sẽ thực tế hơn.</p>', '<p>Tai nghe phù hợp không chỉ hay mà còn phải đúng bối cảnh sử dụng mỗi ngày.</p>', '/storage/photos/33/Blog/images.jpg', 'Tai nghe chống ồn', 10, NULL, 33, 'active', '2026-03-15 09:00:00', '2026-03-15 06:27:36'),
(12, 'Tai nghe true wireless nào hợp cho người chạy bộ?', 'tai-nghe-true-wireless-nao-hop-cho-nguoi-chay-bo', '<p>Khi chạy bộ, ưu tiên quan trọng là độ bám tai, chống mồ hôi và kết nối ổn định.</p>', '<h2>1. Độ bám tai là ưu tiên số một</h2><p>Một mẫu tai nghe nhẹ, đeo chắc và ít xê dịch sẽ thoải mái hơn rất nhiều khi vận động.</p><h2>2. Chống mồ hôi và pin đủ dùng</h2><p>Hãy chọn chuẩn kháng nước từ IPX4 trở lên và pin đủ cho nhiều buổi tập liên tiếp.</p><h2>3. Đừng bỏ qua xuyên âm</h2><p>Nếu chạy ngoài trời, chế độ xuyên âm sẽ giúp bạn an toàn hơn khi nghe được xe cộ xung quanh.</p>', '<p>Tai nghe cho vận động nên ưu tiên sự ổn định và an toàn trước hiệu ứng âm thanh.</p>', '/storage/photos/33/Blog/20210704_hesZl45FtbVBmRPETnFvOKUs.jpg', 'True wireless', 10, NULL, 33, 'active', '2026-03-15 09:05:00', '2026-03-15 06:32:55'),
(13, 'Mẹo bảo quản tai nghe bền pin, bền đệm tai', 'meo-bao-quan-tai-nghe-ben-pin-ben-dem-tai', '<p>Chỉ cần vài thói quen nhỏ như lau sạch sau khi dùng và tránh cắm sạc quá lâu, tuổi thọ tai nghe sẽ khác rõ rệt.</p>', '<h2>1. Lau sạch sau khi dùng</h2><p>Mồ hôi và bụi bám lâu ngày dễ làm xuống cấp đệm tai và tip silicon.</p><h2>2. Không để pin cạn kiệt liên tục</h2><p>Hãy sạc khi pin còn khoảng 20 đến 30 phần trăm để giúp pin ổn định hơn về lâu dài.</p><h2>3. Bảo quản trong hộp</h2><p>Việc để tai nghe lẫn với chìa khóa hoặc đồ sắc nhọn trong balo rất dễ gây trầy xước và hỏng cổng kết nối.</p>', '<p>Tai nghe bền hơn nhiều khi bạn chăm nó như một thiết bị dùng mỗi ngày.</p>', '/storage/photos/33/Blog/images (1).jpg', 'Tai nghe chống ồn,True wireless,Phụ kiện âm thanh', 10, NULL, 33, 'active', '2026-03-15 09:10:00', '2026-03-15 06:33:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_categories`
--

CREATE TABLE `post_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_categories`
--

INSERT INTO `post_categories` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(10, 'Cẩm nang âm thanh', 'cam-nang-am-thanh', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_comments`
--

CREATE TABLE `post_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `replied_comment` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_comments`
--

INSERT INTO `post_comments` (`id`, `user_id`, `post_id`, `comment`, `status`, `replied_comment`, `parent_id`, `created_at`, `updated_at`) VALUES
(10, 33, 13, 'bài viết hay ,ứng dụng tốt', 'active', NULL, NULL, '2025-03-27 06:40:10', '2025-03-27 06:40:10'),
(11, 42, 13, 'bài viết rất hay', 'active', NULL, NULL, '2025-03-27 07:26:23', '2025-03-27 07:26:23'),
(12, 43, 12, 'Bài viết bổ ích', 'active', NULL, NULL, '2026-03-18 23:32:49', '2026-03-18 23:32:49');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_tags`
--

CREATE TABLE `post_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_tags`
--

INSERT INTO `post_tags` (`id`, `title`, `slug`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Tai nghe chống ồn', 'tai-nghe-chong-on', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(9, 'True wireless', 'true-wireless', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00'),
(10, 'Phụ kiện âm thanh', 'phu-kien-am-thanh-tag', 'active', '2026-03-15 09:00:00', '2026-03-15 09:00:00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `summary` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 1,
  `size` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'M',
  `condition` enum('default','new','hot') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inactive',
  `price` double(20,2) NOT NULL,
  `discount` double(10,2) NOT NULL,
  `is_featured` tinyint(1) NOT NULL,
  `cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `child_cat_id` bigint(20) UNSIGNED DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `title`, `slug`, `summary`, `description`, `photo`, `stock`, `size`, `condition`, `status`, `price`, `discount`, `is_featured`, `cat_id`, `child_cat_id`, `brand_id`, `created_at`, `updated_at`) VALUES
(21, 'Tai nghe chụp tai Sony WH-CH720N', 'tai-nghe-chup-tai-sony-wh-ch720n', '<p>Chống ồn chủ động, pin tới 35 giờ, kết nối Bluetooth 5.2 và đệm tai êm cho học tập, làm việc.</p>', '<h3>Sony WH-CH720N</h3><p>Mẫu over-ear nhẹ, đeo lâu không bí và phù hợp cho người cần tập trung khi làm việc.</p><ul><li>Chống ồn chủ động và chế độ xuyên âm tiện chuyển đổi</li><li>Pin dài cho nhu cầu văn phòng và di chuyển</li><li>Mic rõ cho họp online và gọi điện</li></ul><p>Phù hợp cho sinh viên, dân văn phòng và người hay di chuyển.</p>', '/storage/photos/33/Product/tải xuống.jpg', 60, '1 & 2: 40.5 x 16.5 x 18 mm,3: 39.79 x 18.26 x 19.21 mm,Pro 2: 30.9 x 21.8 x 24 mm', 'new', 'active', 3290000.00, 7.00, 1, 26, NULL, 8, '2026-03-15 09:00:00', '2026-03-15 06:18:05'),
(22, 'Tai nghe gaming JBL Quantum 100M2', 'tai-nghe-gaming-jbl-quantum-100m2', '<p>Âm thanh rõ tiếng bước chân, mic cần gập gọn và đệm tai mềm cho game thủ phổ thông.</p>', '<h3>JBL Quantum 100M2</h3><p>Mẫu tai nghe chụp tai có dây tối ưu cho game, học online và giải trí tại nhà.</p><ul><li>Micro tháo lắp nhanh, bắt giọng khá rõ</li><li>Đệm tai mềm, ôm vừa đầu</li><li>Chất âm thiên sáng, dễ nghe thoại và tiếng động trong game</li></ul><p>Thích hợp cho người mới bắt đầu hoặc cần một mẫu gaming headset dễ dùng.</p>', '/storage/photos/33/Product/tai_nghe_choang_dau_co_mic_gaming_jbl_quantum_100m2_1_64138a9ab8.jpg', 120, '', 'hot', 'active', 890000.00, 5.00, 1, 26, NULL, 9, '2026-03-15 09:05:00', '2026-03-15 05:52:28'),
(24, 'Tai nghe true wireless Sennheiser CX Plus SE', 'tai-nghe-true-wireless-sennheiser-cx-plus-se', '<p>Chống ồn lai, âm chi tiết và cảm ứng mượt cho nhu cầu nghe nhạc hằng ngày.</p>', '<h3>Sennheiser CX Plus SE</h3><p>Mẫu true wireless cân bằng giữa chất âm, độ êm và khả năng sử dụng hằng ngày.</p><ul><li>Âm thanh chi tiết, vocal rõ</li><li>Chế độ chống ồn và xuyên âm linh hoạt</li><li>Form in-ear ôm tai, thao tác cảm ứng nhanh</li></ul><p>Rất hợp cho người nghe nhạc lâu, làm việc linh hoạt và cần chất âm chỉn chu.</p>', '/storage/photos/33/Product/tai-nghe-khong-day-sennheiser-cx-plus-min-mobile-quan-10-tphcm__1__1190dd2518f948609769017e18ee4e92_master.jpg', 75, '', 'new', 'active', 2790000.00, 10.00, 1, 27, NULL, 10, '2026-03-15 09:10:00', '2026-03-15 05:53:25'),
(26, 'Tai nghe Bluetooth SoundPEATS Air4 Lite', 'tai-nghe-bluetooth-soundpeats-air4-lite', '<p>Thiết kế nửa in-ear thoáng tai, pin ổn định và độ trễ thấp khi xem video, chơi game nhẹ.</p>', '<h3>SoundPEATS Air4 Lite</h3><p>Mẫu true wireless dễ đeo, dễ ghép nối và phù hợp nhu cầu dùng hàng ngày trong tầm giá dễ tiếp cận.</p><ul><li>Kết nối nhanh, giữ tín hiệu ổn định</li><li>Đeo thoáng tai, phù hợp nghe lâu</li><li>Mic ổn cho gọi điện và học online</li></ul><p>Đây là lựa chọn hợp lý cho học sinh, sinh viên và người cần tai nghe Bluetooth gọn nhẹ.</p>', '/storage/photos/33/Product/tai-nghe-khong-day-soundpeats-air-4-lite_4_.png', 97, '', 'hot', 'active', 1200000.00, 0.00, 1, 27, NULL, 11, '2026-03-15 09:15:00', '2026-03-15 07:05:32'),
(27, 'Bộ DAC USB-C và hộp đựng tai nghe cao cấp', 'bo-dac-usb-c-va-hop-dung-tai-nghe-cao-cap', '<p>Bộ phụ kiện gồm DAC USB-C, hộp chống sốc và móc treo giúp bảo quản tai nghe gọn gàng khi di chuyển.</p>', '<h3>Combo phụ kiện âm thanh</h3><p>Bộ phụ kiện dành cho người dùng tai nghe có dây hoặc tai nghe cao cấp cần bảo quản gọn và ổn định khi kết nối với điện thoại.</p><ul><li>DAC USB-C chuyển tín hiệu sạch và ổn định</li><li>Hộp cứng chống va đập khi mang theo</li><li>Móc treo tiện bố trí góc làm việc</li></ul><p>Phù hợp cho người muốn set up gọn gàng và bảo vệ thiết bị tốt hơn mỗi ngày.</p>', '/storage/photos/33/Product/images.jpg', 98, '1 & 2: 40.5 x 16.5 x 18 mm,3: 39.79 x 18.26 x 19.21 mm', 'default', 'active', 1880000.00, 6.00, 1, 28, NULL, 8, '2026-03-15 09:20:00', '2026-03-20 02:29:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rate` tinyint(4) NOT NULL DEFAULT 0,
  `review` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `user_id`, `product_id`, `rate`, `review`, `status`, `created_at`, `updated_at`) VALUES
(4, 33, 24, 5, 'Âm thanh rõ, đeo chắc tai và chống ồn khá ổn trong tầm giá.', 'active', '2026-03-15 09:30:00', '2026-03-15 09:30:00'),
(5, 42, 27, 5, 'Bộ phụ kiện hoàn thiện tốt, cắm DAC ổn định và hộp đựng khá chắc chắn.', 'active', '2026-03-15 09:31:00', '2026-03-15 09:31:00'),
(6, 43, 26, 5, 'Tai nghe đẹp', 'active', '2026-03-18 23:31:17', '2026-03-18 23:31:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_des` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `settings`
--

INSERT INTO `settings` (`id`, `description`, `short_des`, `logo`, `photo`, `address`, `phone`, `email`, `created_at`, `updated_at`) VALUES
(1, 'Audio Hub chuyên tai nghe chính hãng, tập trung vào các dòng chống ồn, true wireless và phụ kiện âm thanh dễ chọn, dễ dùng.', 'Tai nghe chính hãng, giá rõ ràng, hỗ trợ chọn nhanh theo nhu cầu.', '/storage/photos/33/—Pngtree—hand-painted black and white music_4343119.png', '/storage/photos/33/—Pngtree—hand-painted black and white music_4343119.png', '123 Trần Duy Hưng - Cầu Giấy - Hà Nội', '+(84) 398 314 279', 'audiohub@gmail.com', '2026-03-15 09:00:00', '2026-03-15 06:15:57');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shippings`
--

CREATE TABLE `shippings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shippings`
--

INSERT INTO `shippings` (`id`, `type`, `price`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Hà Nội', '40000.00', 'active', '2020-08-14 04:22:17', '2022-04-25 11:14:54'),
(2, 'Phú Thọ', '30000.00', 'active', '2020-08-14 04:22:41', '2022-04-25 11:15:11'),
(3, 'Vĩnh Phúc', '40000.00', 'active', '2020-08-15 06:54:04', '2022-04-25 11:15:27'),
(4, 'Ninh Bình', '35000.00', 'active', '2020-08-17 20:50:48', '2022-04-25 11:15:47'),
(5, 'Hồ Chí Minh', '80000.00', 'active', '2025-03-27 07:30:33', '2025-03-27 07:30:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) CHARACTER SET utf8 NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `photo`, `role`, `provider`, `provider_id`, `status`, `remember_token`, `created_at`, `updated_at`) VALUES
(33, 'Admin', 'admin@gmail.com', NULL, '$2y$10$7U6jQWXQz2Tcvl.t3FZiUu9xVZ7w13qliKRzXBD65akufAfzrnwmq', '/storage/photos/33/User/logo-comics-food-bar-hd-png.png', 'admin', NULL, NULL, 'active', 'U6nx5DCk8jyHQpAwFt3KjFhbbGqC5nuobZgi7WKJtvPjlTeAjZg9lJdVKAQH', NULL, '2025-03-27 04:28:08'),
(34, 'Linh', 'user@gmail.com', NULL, '$2y$10$An0KdWoqcI1JK8Fj7Db2burUfxtf2xbNSnLDr.4mbsUqvlR7E954.', '/storage/photos/33/User/4-ceo-viet-tuoi-suu-tai-gioi-dang-dieu-hanh-doanh-nghiep-nao.jpg', 'user', NULL, NULL, 'active', NULL, '2022-04-25 09:04:15', '2022-04-25 09:05:00'),
(35, 'Thịnh', 'thinhphuongxa1@gmail.com', NULL, '$2y$10$W8jNnJgUSG5zfXjWPW6KTeaM9uI2U0jOxozG/N0Ody6rf1.X/bssO', '/storage/photos/33/User/logo-comics-food-bar-hd-png.png', 'user', NULL, NULL, 'active', NULL, '2022-04-25 09:04:15', '2022-04-25 09:04:15'),
(36, 'Minh', 'minh@gmail.com', NULL, '$2y$10$AtPfHsxPzg7P8vooZEQBPeIc1mEpWh.sIOG1LNoAWBf9MytBDp5Dm', '/storage/photos/33/User/4-ceo-viet-tuoi-suu-tai-gioi-dang-dieu-hanh-doanh-nghiep-nao.jpg', 'user', NULL, NULL, 'active', NULL, '2022-04-27 02:05:54', '2022-04-28 11:11:36'),
(37, 'user1', 'user1@gmail.com', NULL, '$2y$10$kxkc4NscWvCxh0t/7nYIqOCOSJPwfrMLmawfwmxnJlCAFcbLvZWfC', NULL, 'user', NULL, NULL, 'active', NULL, '2022-05-11 10:04:16', '2022-05-11 10:04:16'),
(38, 'user2', 'user2@gmail.com', NULL, '$2y$10$RwduGWnyr96mK.DqvYi5Cex7ZujywKNJQ9lTfR9tuds36mUvc7Jie', NULL, 'user', NULL, NULL, 'active', NULL, '2022-05-19 08:11:39', '2022-05-19 08:11:39'),
(39, 'user3', 'user3@gmail.com', NULL, '$2y$10$DCyzfOKCmgt2njr8D7Z.ZeZ2pFVly/chBrJeqJFwYBjXipliyPs26', NULL, 'user', NULL, NULL, 'active', NULL, '2022-05-20 08:12:05', '2022-05-20 08:12:05'),
(40, 'user4', 'user4@gmail.com', NULL, '$2y$10$IbppthaqLsoS9QOEH3gWtu7cPiEzhY.lUeEFEz4EGVXBivJL8kN8y', NULL, 'user', NULL, NULL, 'active', NULL, '2022-05-21 08:12:28', '2022-05-21 08:12:28'),
(41, 'huyen', 'huyen@gmail.com', NULL, '$2y$10$7U6jQWXQz2Tcvl.t3FZiUu9xVZ7w13qliKRzXBD65akufAfzrnwmq', NULL, 'user', NULL, NULL, 'active', NULL, '2025-03-27 03:44:50', '2025-03-27 03:44:50'),
(42, 'manhdieu', 'manhdieu@gmail.com', NULL, '$2y$10$k8bzC0WFjVIQSlPIBx3h0OLtRLVNQmq173vAIsWTP8gIZX9DLbHSC', NULL, 'user', NULL, NULL, 'active', NULL, '2025-03-27 07:23:18', '2025-03-27 07:23:18'),
(43, 'canh', 'canh@gmail.com', NULL, '$2y$10$FTM9COFVREVn4Wgfbh3B6.BGmD5q/MkMMDh5nSNw29Ev8InxP/azi', NULL, 'user', NULL, NULL, 'active', NULL, '2026-03-16 07:40:30', '2026-03-16 07:40:30'),
(44, 'hong', 'hong@gmail.com', NULL, '$2y$10$8ZJJvQuxbtEF/Tu9jO8ACe9voQj1vpNsBWA0Jzckthtai5y2LWKJ6', NULL, 'user', NULL, NULL, 'active', NULL, '2026-03-20 02:13:08', '2026-03-20 02:13:08');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` double(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `banners_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_product_id_foreign` (`product_id`),
  ADD KEY `carts_user_id_foreign` (`user_id`),
  ADD KEY `carts_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`),
  ADD KEY `categories_added_by_foreign` (`added_by`);

--
-- Chỉ mục cho bảng `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_shipping_id_foreign` (`shipping_id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_post_cat_id_foreign` (`post_cat_id`),
  ADD KEY `posts_post_tag_id_foreign` (`post_tag_id`),
  ADD KEY `posts_added_by_foreign` (`added_by`);

--
-- Chỉ mục cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_comments_user_id_foreign` (`user_id`),
  ADD KEY `post_comments_post_id_foreign` (`post_id`);

--
-- Chỉ mục cho bảng `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_tags_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_cat_id_foreign` (`cat_id`),
  ADD KEY `products_child_cat_id_foreign` (`child_cat_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_reviews_user_id_foreign` (`user_id`),
  ADD KEY `product_reviews_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `shippings`
--
ALTER TABLE `shippings`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`),
  ADD KEY `wishlists_user_id_foreign` (`user_id`),
  ADD KEY `wishlists_cart_id_foreign` (`cart_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `chatbot_faqs`
--
ALTER TABLE `chatbot_faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `post_tags`
--
ALTER TABLE `post_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `shippings`
--
ALTER TABLE `shippings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `carts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_shipping_id_foreign` FOREIGN KEY (`shipping_id`) REFERENCES `shippings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_added_by_foreign` FOREIGN KEY (`added_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_post_cat_id_foreign` FOREIGN KEY (`post_cat_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `posts_post_tag_id_foreign` FOREIGN KEY (`post_tag_id`) REFERENCES `post_tags` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `post_comments`
--
ALTER TABLE `post_comments`
  ADD CONSTRAINT `post_comments_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `post_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_cat_id_foreign` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `products_child_cat_id_foreign` FOREIGN KEY (`child_cat_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
