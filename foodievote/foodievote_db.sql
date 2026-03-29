-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 29, 2026 at 11:37 AM
-- Server version: 8.0.42
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `foodievote_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `foods`
--

CREATE TABLE `foods` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `restaurant_id` int NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `foods`
--

INSERT INTO `foods` (`id`, `name`, `description`, `price`, `restaurant_id`, `image_url`, `created_at`) VALUES
(1, 'Nasi Rendang', 'Nasi putih dengan rendang sapi khas Padang', 25000.00, 1, 'https://placehold.co/300x200/green/white?text=Nasi+Rendang', '2026-01-15 14:03:40'),
(2, 'Nasi Gurameh', 'Nasi dengan gurameh bakar pedas', 35000.00, 1, 'https://placehold.co/300x200/green/white?text=Nasi+Gurameh', '2026-01-15 14:03:40'),
(4, 'Cappuccinoii', 'Minuman kopi dengan busa susu', 22000.00, 3, 'https://placehold.co/300x200/brown/white?text=Cappuccino', '2026-01-15 14:03:40'),
(5, 'Sate Ayam', 'Sate ayam dengan bumbu kacang', 20000.00, 3, 'https://placehold.co/300x200/red/white?text=Sate+Ayam', '2026-01-15 14:03:40'),
(6, 'Sate Kambing', 'Sate kambing dengan bumbu kacang', 25000.00, 3, 'https://placehold.co/300x200/red/white?text=Sate+Kambing', '2026-01-15 14:03:40'),
(9, 'Cappuccinoii', 'sj', 22000.00, 1, '/uploads/foods/food_696b5b71ba7370.92115570.jpeg', '2026-01-17 09:50:41'),
(10, 'Wa', 'njnd', 10909.00, 5, 'uploads/foods/food_696b6475e17f55.93470792.jpg', '2026-01-17 10:29:09'),
(11, 'Ayam', 'Ayam', 100000.00, 4, 'uploads/foods/food_6971afd121b607.33917548.png', '2026-01-22 05:04:17'),
(12, 'Nasi goreng', 'disini', 20000.00, 7, 'uploads/foods/food_698d7dd9ced509.48532808.png', '2026-02-12 07:14:33'),
(13, 'sate madura', 'Top sales', 1000.00, 8, 'uploads/foods/food_698d90092625b1.36946247.png', '2026-02-12 08:32:09');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `restaurant_id` int DEFAULT NULL,
  `food_id` int DEFAULT NULL,
  `rating` int NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `user_id`, `restaurant_id`, `food_id`, `rating`, `review`, `created_at`) VALUES
(1, 2, 3, NULL, 5, 'wenaks', '2026-01-17 05:55:24'),
(2, 2, NULL, 2, 1, 'Kurang enak', '2026-01-17 07:00:10'),
(3, 2, 4, NULL, 3, 'Sangat enak', '2026-01-19 11:15:17'),
(4, 2, NULL, 4, 2, 'gak enak', '2026-01-19 11:15:49'),
(6, 6, 3, NULL, 1, 'Kureng', '2026-01-21 02:22:55'),
(7, 7, 3, NULL, 2, 'Kuang bagus', '2026-01-21 03:14:00'),
(8, 7, NULL, 2, 3, 'Mantap', '2026-01-21 03:14:24'),
(14, 10, 4, NULL, 5, 'oke', '2026-02-12 08:46:39');

-- --------------------------------------------------------

--
-- Table structure for table `restaurants`
--

CREATE TABLE `restaurants` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `operating_hours` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `restaurants`
--

INSERT INTO `restaurants` (`id`, `name`, `description`, `address`, `phone`, `operating_hours`, `image_url`, `created_at`) VALUES
(1, 'Warung Padang Mak Syakban', 'Restoran padang dengan masakan tradisional yang lezat', 'Jl. Diponegoro No. 123, Jakarta', '021-12345678', '08:00-22:00', 'https://placehold.co/600x400/orange/white?text=Warung+Padang', '2026-01-15 14:03:40'),
(2, 'Kopi Kenangan', 'Tempat nongkrong anak muda dengan kopi pilihan', 'Jl. Sudirman No. 45, Bandung', '022-87654321', '06:00-23:00', 'https://placehold.co/600x400/brown/white?text=Kopi+Kenangan', '2026-01-15 14:03:40'),
(3, 'Sate Khas Senayan', 'Sate dengan bumbu kacang khas yang gurih', 'Jl. Asia Afrika No. 78, Jakarta', '021-23456789', '10:00-21:00', 'https://placehold.co/600x400/red/white?text=Sate+Senayan', '2026-01-15 14:03:40'),
(4, 'ini contohw', 'mantap  c', 'jbdsjc', '08056656376', 'senin sampai sabtu 18.00', 'uploads/restaurants/restaurant_6970478f465441.30878595.png', '2026-01-17 09:57:20'),
(5, 'Warung Bu Marsi', 'Mantap polll', 'wonokarrto', '0867562768276', 'senin sampai sabtu 18.00', 'uploads/restaurants/restaurant_696b64529b7ea2.44393997.png', '2026-01-17 10:28:34'),
(6, 'warung Fauzan', 'Warung ter good', 'WONOKARTO', '089672671', '10:00-21:00', 'uploads/restaurants/restaurant_69704515407d15.44774723.png', '2026-01-21 03:16:37'),
(7, 'Warung Bu nyamik', 'Bagus warungnya', 'Giriwono', '0123455686', '10:00-21:00', 'uploads/restaurants/restaurant_698d7d93127e92.22341903.png', '2026-02-12 07:13:23'),
(8, 'Sate Bu is', 'Warung sate ter top', 'WONOKARTO, rt 09 rw 02', '0876542642', '10:00-21:00', 'uploads/restaurants/restaurant_698d8fbf964612.66029135.jpg', '2026-02-12 08:30:55');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('user','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@foodievote.com', 'admin123', 'admin', '2026-01-15 14:03:40'),
(2, 'roots', '6@g.com', '$2y$10$pL6m4sTcw/.kOiT0flsnbeH4LkuJq.eiOS7IqWSnURroUQOMMCcq.', 'user', '2026-01-17 03:33:17'),
(3, 'admin2', 'admin2@foodievote.com', '$2y$10$iMyyJlwPEslpCEQaNHL/6uqwwgnTlCcwSim1rxSsp1WL1f3GJGgq6', 'admin', '2026-01-17 04:11:21'),
(4, 'adm', 'admin@example.com', '$2y$10$0hBbwUyjU/g1tJr/FVPaQeEHOV5PUlc02hnKv8aJp7mE/tggR/wYW', 'admin', '2026-01-17 05:47:27'),
(6, 'Intan', 'intanpus@gmai.com', '$argon2id$v=19$m=65536,t=4,p=1$RllMbWpBWFlqR1M4Zmk5bA$19KtBSztpTzdalni8h7UBH4SNJDUQUhN5AifsCeUTcU', 'user', '2026-01-21 02:11:59'),
(7, 'fauzzan', 'fauzzan@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Rk50aXZjVFI4ZWlhTHUzUQ$lqP4sLNMlThfbCH0PCFy5wNRwL6X6VM5iHmtzp0asZA', 'user', '2026-01-21 03:13:02'),
(8, 'vano', 'stevano016@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$SllTTGZTRG9ZbkdrRW5xZg$Oi3WQNO7xAdpCcfpFwLmYOFOyBlHOuXoyokDgdyNoMo', 'user', '2026-02-12 07:12:12'),
(9, 'alfiyan', 'alfiyan@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$RHNJelNhMm05c1JRWE0wYQ$6u/tzIBysJBfTH7M/rxLv98Rbbwks1SpauRMC5IN7e8', 'user', '2026-02-12 07:18:46'),
(10, 'Damars', 'dam@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$WnVweHdrdnNYVm96L3BzdQ$bfhxl1S7+k5NQ1u7huYuFgfY+gjEsXu5Fnukc62kAFo', 'user', '2026-02-12 08:25:51'),
(12, '1234567', 'alfianwww@gmail.com', '$argon2id$v=19$m=65536,t=4,p=1$Snlad204UHBvbC5nTmRGSA$iX6Blkg+dmBYXnwj4gT/SXEG+kX9sCx7rbML32mQ9+0', 'user', '2026-02-12 09:02:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `foods`
--
ALTER TABLE `foods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `restaurant_id` (`restaurant_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_restaurant` (`user_id`,`restaurant_id`),
  ADD UNIQUE KEY `unique_user_food` (`user_id`,`food_id`),
  ADD KEY `restaurant_id` (`restaurant_id`),
  ADD KEY `food_id` (`food_id`);

--
-- Indexes for table `restaurants`
--
ALTER TABLE `restaurants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `foods`
--
ALTER TABLE `foods`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `restaurants`
--
ALTER TABLE `restaurants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `foods`
--
ALTER TABLE `foods`
  ADD CONSTRAINT `foods_ibfk_1` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`restaurant_id`) REFERENCES `restaurants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_3` FOREIGN KEY (`food_id`) REFERENCES `foods` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
