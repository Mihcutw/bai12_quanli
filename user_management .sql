-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 17, 2025 lúc 07:15 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `user_management`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(50) DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`id`, `username`, `email`, `password`, `reset_password`, `created_at`, `role`) VALUES
(2, 'minhgs', 's@gmail.com', '$2y$10$.UE170BTrW/I5m0H8GouA.xu2lKeMCbnoq.Fu94585X82USZtcKN6', NULL, '2025-05-17 04:15:10', 'admin'),
(3, 'm', 'minhs@gmail.com', '$2y$10$MAziVnTd9iu1wH8LZiXGhONvIk8YFesDLmeLsMAiVwHr885A1PpVK', NULL, '2025-05-17 05:07:10', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reset_password` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `reset_password`, `avatar`, `created_at`, `role`) VALUES
(11, 'minhdeptrai', 'skbiditolet@gmail.com', '$2y$10$vd9l.Epv2jTkoMM1GaCp..JFN4l4J5LwxknMi3oY805WEUayGoFue', NULL, NULL, '2025-05-17 04:45:29', 0),
(12, 'minh', 'minhs@gmail.com', '$2y$10$Lp5qWxt.b/mrXyerZyBzLuA.i3e7TBQX76r94wJe2BSK2X6LzjxAC', NULL, NULL, '2025-05-17 04:46:29', 0),
(13, 'minhgs', 'minhgs@gmail.com', '$2y$10$nwSkn3PscyBRYdh8HbnlsOY1.Qx3C0RVlXZohHdkEOpaeiYMj6Z4O', NULL, NULL, '2025-05-17 04:48:37', 0),
(14, 'minh', 'wibu@gmail.com', '$2y$10$dyGHv4F5FhwzUL1X.Za4heEpZuWXdvgqLiDq7pjr/W9puZlv3LmwW', NULL, NULL, '2025-05-17 05:10:37', 0);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
