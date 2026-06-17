-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Време на генериране: 17 юни 2026 в 17:25
-- Версия на сървъра: 10.4.32-MariaDB
-- Версия на PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данни: `fashion_store`
--

-- --------------------------------------------------------

--
-- Структура на таблица `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`) VALUES
(1, 'Тениски', '2026-06-08 16:52:13'),
(2, 'Якета', '2026-06-08 16:52:41'),
(3, 'Обувки', '2026-06-08 16:53:06'),
(4, 'Аксесоари', '2026-06-08 16:53:14'),
(5, 'Панталони', '2026-06-08 16:53:59');

-- --------------------------------------------------------

--
-- Структура на таблица `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(30) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `delivery_method` varchar(100) DEFAULT NULL,
  `delivery_price` decimal(10,2) DEFAULT 0.00,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `phone`, `city`, `address`, `postal_code`, `payment_method`, `delivery_method`, `delivery_price`, `note`) VALUES
(1, 1, 219.98, 'completed', '2026-06-08 20:55:29', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(2, 1, 129.99, 'completed', '2026-06-08 23:29:54', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(3, 2, 219.98, 'completed', '2026-06-09 19:43:11', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(4, 1, 1039.92, 'completed', '2026-06-09 22:38:33', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(5, 2, 779.94, 'cancelled', '2026-06-09 23:09:30', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(6, 2, 259.98, 'cancelled', '2026-06-09 23:09:41', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(7, 2, 1169.91, 'cancelled', '2026-06-09 23:11:51', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(8, 2, 249.90, 'cancelled', '2026-06-09 23:50:01', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(9, 2, 129.99, 'cancelled', '2026-06-10 00:04:35', NULL, NULL, NULL, NULL, NULL, NULL, 0.00, NULL),
(10, 1, 39.99, 'completed', '2026-06-10 01:05:17', '0888123456', 'Sofia', 'Slivnica, nomer 15', '1100', 'Банкова карта', NULL, 0.00, 'da bude dostavena distancionno'),
(11, 2, 164.97, 'pending', '2026-06-10 18:39:38', '08884545454', 'софиа', 'бхбххббх', '1300', 'Банкова карта', NULL, 0.00, 'ок'),
(12, 2, 80.00, 'cancelled', '2026-06-10 21:29:11', '088655656', 'sofia', 'sohatna 10', '1200', 'Наложен платеж', NULL, 0.00, 'okay'),
(13, 2, 2450.00, 'cancelled', '2026-06-10 22:04:12', 'дсдсдс', 'дсдсдс', 'дсдсдс', 'дсдсдс', 'Наложен платеж', NULL, 0.00, 'дсддс'),
(14, 2, 159.97, 'pending', '2026-06-11 00:38:19', '08884d45sd4sd', 'dssd', 'dssdds', '1200', 'Наложен платеж', 'Еконт до адрес', 4.99, 'dsddsds'),
(15, 2, 99.98, 'pending', '2026-06-11 01:12:16', 'дсдсс', 'дссддс', 'дсдс', 'дссддс', 'Наложен платеж', 'Еконт до адрес', 4.99, 'дсдссд');

-- --------------------------------------------------------

--
-- Структура на таблица `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 4, 1, 129.99),
(2, 1, 3, 1, 89.99),
(3, 2, 4, 1, 129.99),
(4, 3, 3, 1, 89.99),
(5, 3, 4, 1, 129.99),
(6, 4, 4, 8, 129.99),
(7, 5, 4, 6, 129.99),
(8, 6, 4, 2, 129.99),
(9, 7, 4, 9, 129.99),
(10, 8, 5, 10, 24.99),
(11, 9, 4, 1, 129.99),
(12, 10, 1, 1, 39.99),
(13, 11, 3, 1, 89.99),
(14, 11, 1, 1, 39.99),
(15, 11, 2, 1, 34.99),
(16, 12, 9, 1, 35.00),
(17, 12, 5, 1, 45.00),
(18, 13, 8, 49, 50.00),
(19, 14, 10, 2, 35.00),
(20, 14, 9, 1, 35.00),
(21, 14, 11, 2, 24.99),
(22, 15, 10, 1, 35.00),
(23, 15, 9, 1, 35.00),
(24, 15, 11, 1, 24.99);

-- --------------------------------------------------------

--
-- Структура на таблица `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `products`
--

INSERT INTO `products` (`id`, `category_id`, `subcategory_id`, `name`, `description`, `price`, `image`, `stock`, `created_at`) VALUES
(1, 1, 1, 'Тениска Black Edition', 'Стилна тениска с ефектен надпис, която добавя характер към всяка визия.', 20.00, 'black_tshirt.jpg', 13, '2026-06-08 16:59:56'),
(2, 1, 1, 'Тениска Classic', 'Мъжка бяла тениска с изчистен дизайн, създадена за удобство и лесно съчетаване с всякакъв ежедневен стил.', 15.00, 'white_tshirt.jpg', 19, '2026-06-08 17:02:30'),
(3, 2, 4, 'Яке Blaze', 'Мъжко зимно яке с модерна цветова комбинация и топла подплата.', 70.00, 'jacket.jpg', 9, '2026-06-08 17:03:13'),
(4, 3, 7, 'Маратонки Air Motion', 'Спортни маратонки с модерен дизайн, осигуряващи комфорт, лекота и стабилност', 130.00, 'shoes.jpg', 1, '2026-06-08 17:04:11'),
(5, 1, 2, 'Тениска Not Today', 'Дамска тениска с отличителен принт и модерно излъчване.', 15.00, 'woman_tshirt2.jpg', 15, '2026-06-08 17:05:29'),
(7, 5, 12, 'Панталон Executive', 'Класически мъжки панталони с изчистен дизайн и удобна кройка.', 30.00, 'man_pants.jpg', 20, '2026-06-10 19:17:08'),
(8, 3, 7, 'Маратонки Runner', 'Спортни маратонки Adidas за тренировки и ежедневно носене.', 50.00, 'adidas_shoes.jpg', 75, '2026-06-10 19:25:57'),
(9, 1, 2, 'Тениска Natural', 'Изчистена дамска тениска в топъл земен нюанс.', 10.00, 'woman_tshirt.jpg', 4, '2026-06-10 19:31:47'),
(10, 5, 13, 'Спортен панталон Move', 'Дамски спортен панталон, създаден за максимална свобода на движение в ежедневието.', 20.00, 'yellow.jpg', 12, '2026-06-10 19:32:02'),
(11, 4, 10, 'Колан Classic', 'Елегантен мъжки колан от естествена кожа в класически кафяв цвят.', 25.00, 'belt.jpg', 3, '2026-06-10 19:32:29'),
(12, 4, 10, 'Раница Explorer', 'Функционална и издръжлива раница, подходяща за пътувания, преходи и ежедневна употреба.', 37.00, 'man_backpack.jpg', 10, '2026-06-12 02:39:52'),
(13, 4, 10, 'Часовник Престиж', 'Елегантен ръчен часовник с минималистичен дизайн, кожена каишка и класическа визия.', 25.00, 'man_watch.jpg', 8, '2026-06-12 02:49:51'),
(14, 4, 11, 'Колие Цветен Блясък', 'Елегантно дамско колие с перли и кристали.', 80.00, 'woman_necklace.jpg', 2, '2026-06-12 02:54:59'),
(15, 4, 11, 'Чанта Белла', 'Стилна дамска чанта с удобна дръжка и дълга презрамка', 35.00, 'woman_bag.jpg', 5, '2026-06-12 02:59:51'),
(16, 3, 9, 'Чехли Весели Крачета', 'Удобни и леки детски чехли с цветен и забавен дизайн.', 15.00, 'kid_shoes.jpg', 3, '2026-06-12 03:36:10'),
(17, 3, 8, 'Обувки Елеганс', 'Дамски обувки на висок ток с изчистен дизайн', 40.00, 'woman_shoes.jpg', 7, '2026-06-12 03:55:27'),
(18, 3, 8, 'Кецове Червен Импулс', 'Спортно-ежедневни кецове с класически дизайн.', 25.00, 'woman_shoes2.jpg', 2, '2026-06-12 03:59:52'),
(19, 3, 8, 'Чехли Комфорт', 'Удобни дамски чехли с анатомична стелка и регулируеми каишки.', 30.00, 'woman_sandals.jpg', 10, '2026-06-12 04:04:13'),
(20, 5, 12, 'Дънки Urban', 'Мъжки дънки с класическа права кройка и изчистен дизайн.', 30.00, 'man_jeans2.jpg', 5, '2026-06-12 16:43:31'),
(21, 5, 13, 'Панталон Classic', 'Дамски панталон с елегантна линия и наситен цвят, който съчетава женственост, удобство и съвременен стил.', 35.00, 'woman_trousers.jpg', 2, '2026-06-12 16:47:11'),
(22, 2, 4, 'Яке Classic Denim', 'Класическо мъжко дънково яке с автентичен дизайн и модерно излъчване.', 35.00, 'jacket2.jpg', 15, '2026-06-12 18:42:08'),
(23, 2, 4, 'Яке Urban', 'Кожено яке с модерна кройка и отличителни детайли.', 70.00, 'leatherjacket.jpg', 5, '2026-06-12 18:44:56'),
(24, 2, 4, 'Яке Explorer', 'Леко мъжко яке с модерна визия и функционални детайли', 45.00, 'autumn_jacket.jpg', 10, '2026-06-12 18:48:50'),
(25, 2, 5, 'Яке Noir', 'Стилно дамско яке с елегантна кройка и класически дизайн', 40.00, 'woman_jacket3.jpg', 8, '2026-06-12 19:02:07'),
(26, 2, 5, 'Яке Amber', 'Стилно дамско яке в топъл кафяв нюанс с мека вътрешна подплата.', 40.00, 'woman_jacket2.jpg', 5, '2026-06-12 19:27:49'),
(27, 2, 5, 'Худи Shadow', 'Дамско худи с изчистен дизайн и удобна свободна кройка.', 25.00, 'woman_hoodie.jpg', 5, '2026-06-12 19:31:02'),
(29, 2, 6, 'Яке Junior Denim', 'Детско дънково яке с практичен дизайн.', 15.00, 'kid_jacket.jpg', 7, '2026-06-12 19:53:44');

-- --------------------------------------------------------

--
-- Структура на таблица `product_ratings`
--

CREATE TABLE `product_ratings` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `product_ratings`
--

INSERT INTO `product_ratings` (`id`, `product_id`, `user_id`, `rating`, `created_at`) VALUES
(1, 10, 1, 5, '2026-06-11 22:21:53'),
(2, 9, 1, 1, '2026-06-11 22:37:48'),
(3, 26, 1, 5, '2026-06-14 01:02:42');

-- --------------------------------------------------------

--
-- Структура на таблица `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Мъжки'),
(2, 1, 'Дамски'),
(3, 1, 'Детски'),
(4, 2, 'Мъжки'),
(5, 2, 'Дамски'),
(6, 2, 'Детски'),
(7, 3, 'Мъжки'),
(8, 3, 'Дамски'),
(9, 3, 'Детски'),
(10, 4, 'Мъжки'),
(11, 4, 'Дамски'),
(12, 5, 'Мъжки'),
(13, 5, 'Дамски');

-- --------------------------------------------------------

--
-- Структура на таблица `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('customer','admin') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Схема на данните от таблица `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Test', 'User', 'test@test.com', '$2y$10$SMPxn9glgLP222PgpLmqz.wQKKf5x9b.C8WWwL1UntRFCvxvGtQPa', 'customer', '2026-06-08 19:16:26'),
(2, 'Admin', 'Admin', 'admin@admin.com', '$2y$10$VBnBRZE8jraQXUBqII.Xy.qPwYy3PwA/Rc8y/sgbH.0m1FXO4b3Si', 'admin', '2026-06-08 19:56:12');

--
-- Indexes for dumped tables
--

--
-- Индекси за таблица `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индекси за таблица `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индекси за таблица `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индекси за таблица `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_products_subcategory_id` (`subcategory_id`);

--
-- Индекси за таблица `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индекси за таблица `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_subcategories_category_id` (`category_id`);

--
-- Индекси за таблица `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `product_ratings`
--
ALTER TABLE `product_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ограничения за дъмпнати таблици
--

--
-- Ограничения за таблица `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ограничения за таблица `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ограничения за таблица `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ограничения за таблица `product_ratings`
--
ALTER TABLE `product_ratings`
  ADD CONSTRAINT `product_ratings_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ограничения за таблица `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `fk_subcategories_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
