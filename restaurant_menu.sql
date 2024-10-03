-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2024 at 06:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_menu`
--

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `description`, `price`) VALUES
(1, 'ข้าวผัดหมู', NULL, 50.00),
(2, 'ข้าวผัดกุ้ง', NULL, 50.00),
(3, 'ผัดกระเพราไก่', NULL, 50.00),
(4, 'หมูทอดกระเทียมพริกไทย', NULL, 50.00),
(5, 'ต้มยำกุ้ง', NULL, 60.00),
(10, 'ไก่ผัด', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `stock_quantity` float DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `initial_stock` float DEFAULT NULL,
  `minimum_stock` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `stock_quantity`, `unit`, `initial_stock`, `minimum_stock`) VALUES
(1, 'ข้าวสวย', 0, 'กรัม', 1000, 2000),
(2, 'เนื้อหมู', 0, 'กรัม', 500, 2000),
(3, 'เนื้อไก่', 0, 'กรัม', 500, 2000),
(4, 'กุ้งสด', 0, 'กรัม', 300, 2000),
(5, 'น้ำมันพืช', 0, 'ลิตร', 20, 0.3),
(6, 'กระเทียมสับ', 0, 'กรัม', 200, 100),
(7, 'พริกขี้หนูสับ', 0, 'กรัม', 100, 50),
(8, 'ใบกระเพรา', 0, 'กรัม', 100, 100),
(9, 'ไข่ไก่', 0, 'ฟอง', 200, 10),
(10, 'ซีอิ๊วขาว', 0, 'ช้อนโต๊ะ', 50, 10),
(11, 'น้ำตาลทราย', 0, 'ช้อนชา', 100, 10),
(12, 'ซอสหอยนางรม', 0, 'ช้อนโต๊ะ', 50, 10),
(13, 'ต้นหอมซอย', 0, 'กรัม', 100, 100),
(14, 'พริกไทยดำ', 0, 'ช้อนชา', 50, 10),
(15, 'ข่าหั่นแว่น', 0, 'แว่น', 30, 50),
(16, 'ตะไคร้หั่นท่อน', 0, 'ต้น', 30, 30),
(17, 'ใบมะกรูดฉีก', 0, 'ใบ', 50, 30),
(18, 'น้ำปลา', 0, 'ช้อนโต๊ะ', 50, 10),
(19, 'น้ำมะนาว', 0, 'ช้อนโต๊ะ', 50, 20),
(20, 'ผักชี', 0, 'ช้อนโต๊ะ', 50, 10),
(22, 'ใบโหระพา', 0, 'ใบ', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity_per_unit` float NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `menu_id`, `product_id`, `quantity_per_unit`, `unit`) VALUES
(1, 1, 1, 200, 'กรัม'),
(2, 1, 2, 200, 'กรัม'),
(3, 1, 5, 0.03, 'ลิตร'),
(4, 1, 6, 10, 'กรัม'),
(5, 1, 9, 1, 'ฟอง'),
(6, 1, 10, 1, 'ช้อนโต๊ะ'),
(7, 1, 11, 1, 'ช้อนชา'),
(8, 1, 13, 10, 'กรัม'),
(9, 2, 1, 200, 'กรัม'),
(10, 2, 4, 200, 'กรัม'),
(11, 2, 5, 0.03, 'ลิตร'),
(12, 2, 6, 10, 'กรัม'),
(13, 2, 9, 1, 'ฟอง'),
(14, 2, 10, 1, 'ช้อนโต๊ะ'),
(15, 2, 11, 1, 'ช้อนชา'),
(16, 2, 13, 10, 'กรัม'),
(17, 3, 1, 200, 'กรัม'),
(18, 3, 3, 200, 'กรัม'),
(19, 3, 6, 10, 'กรัม'),
(20, 3, 7, 5, 'กรัม'),
(21, 3, 8, 10, 'กรัม'),
(22, 3, 18, 1, 'ช้อนโต๊ะ'),
(23, 3, 12, 1, 'ช้อนโต๊ะ'),
(24, 3, 11, 1, 'ช้อนชา'),
(33, 5, 1, 200, 'กรัม'),
(34, 5, 4, 300, 'กรัม'),
(35, 5, 15, 5, 'แว่น'),
(36, 5, 16, 3, 'ต้น'),
(37, 5, 17, 3, 'ใบ'),
(38, 5, 18, 2, 'ช้อนโต๊ะ'),
(39, 5, 19, 2, 'ช้อนโต๊ะ'),
(40, 5, 7, 5, 'กรัม'),
(41, 5, 20, 1, 'ช้อนโต๊ะ'),
(51, 4, 1, 200, ''),
(52, 4, 2, 200, ''),
(53, 4, 6, 20, ''),
(54, 4, 14, 1, ''),
(55, 4, 5, 0.05, ''),
(56, 4, 10, 1, ''),
(57, 4, 12, 1, ''),
(58, 4, 11, 1, ''),
(66, 10, 1, 200, ''),
(67, 10, 3, 100, '');

-- --------------------------------------------------------

--
-- Table structure for table `saleshistory`
--

CREATE TABLE `saleshistory` (
  `id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity_used` float NOT NULL,
  `unit` varchar(50) NOT NULL,
  `quantity_sold` int(11) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `table_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saleshistory`
--

INSERT INTO `saleshistory` (`id`, `menu_name`, `product_name`, `quantity_used`, `unit`, `quantity_sold`, `sale_date`, `table_number`) VALUES
(249, 'ข้าวผัดหมู', 'ข้าวสวย', 200, '0', 1, '2024-10-01 16:55:30', '1'),
(250, 'ข้าวผัดหมู', 'เนื้อหมู', 200, '0', 1, '2024-10-01 16:55:30', '1'),
(251, 'ข้าวผัดหมู', 'น้ำมันพืช', 0.03, '0', 1, '2024-10-01 16:55:30', '1'),
(252, 'ข้าวผัดหมู', 'กระเทียมสับ', 10, '0', 1, '2024-10-01 16:55:30', '1'),
(253, 'ข้าวผัดหมู', 'ไข่ไก่', 1, '0', 1, '2024-10-01 16:55:30', '1'),
(254, 'ข้าวผัดหมู', 'ซีอิ๊วขาว', 1, '0', 1, '2024-10-01 16:55:30', '1'),
(255, 'ข้าวผัดหมู', 'น้ำตาลทราย', 1, '0', 1, '2024-10-01 16:55:30', '1'),
(256, 'ข้าวผัดหมู', 'ต้นหอมซอย', 10, '0', 1, '2024-10-01 16:55:30', '1'),
(257, 'ข้าวผัดหมู', 'ข้าวสวย', 400, '0', 2, '2024-10-01 17:02:32', '2'),
(258, 'ข้าวผัดหมู', 'เนื้อหมู', 400, '0', 2, '2024-10-01 17:02:32', '2'),
(259, 'ข้าวผัดหมู', 'น้ำมันพืช', 0.06, '0', 2, '2024-10-01 17:02:32', '2'),
(260, 'ข้าวผัดหมู', 'กระเทียมสับ', 20, '0', 2, '2024-10-01 17:02:32', '2'),
(261, 'ข้าวผัดหมู', 'ไข่ไก่', 2, '0', 2, '2024-10-01 17:02:32', '2'),
(262, 'ข้าวผัดหมู', 'ซีอิ๊วขาว', 2, '0', 2, '2024-10-01 17:02:32', '2'),
(263, 'ข้าวผัดหมู', 'น้ำตาลทราย', 2, '0', 2, '2024-10-01 17:02:32', '2'),
(264, 'ข้าวผัดหมู', 'ต้นหอมซอย', 20, '0', 2, '2024-10-01 17:02:32', '2'),
(265, 'ข้าวผัดหมู', 'ข้าวสวย', 2000, '0', 10, '2024-10-01 17:15:52', '5'),
(266, 'ข้าวผัดหมู', 'เนื้อหมู', 2000, '0', 10, '2024-10-01 17:15:52', '5'),
(267, 'ข้าวผัดหมู', 'น้ำมันพืช', 0.3, '0', 10, '2024-10-01 17:15:52', '5'),
(268, 'ข้าวผัดหมู', 'กระเทียมสับ', 100, '0', 10, '2024-10-01 17:15:52', '5'),
(269, 'ข้าวผัดหมู', 'ไข่ไก่', 10, '0', 10, '2024-10-01 17:15:52', '5'),
(270, 'ข้าวผัดหมู', 'ซีอิ๊วขาว', 10, '0', 10, '2024-10-01 17:15:52', '5'),
(271, 'ข้าวผัดหมู', 'น้ำตาลทราย', 10, '0', 10, '2024-10-01 17:15:52', '5'),
(272, 'ข้าวผัดหมู', 'ต้นหอมซอย', 100, '0', 10, '2024-10-01 17:15:52', '5'),
(273, 'ข้าวผัดหมู', 'ข้าวสวย', 200, '0', 1, '2024-10-03 03:31:49', '5'),
(274, 'ข้าวผัดหมู', 'เนื้อหมู', 200, '0', 1, '2024-10-03 03:31:49', '5'),
(275, 'ข้าวผัดหมู', 'น้ำมันพืช', 0.03, '0', 1, '2024-10-03 03:31:49', '5'),
(276, 'ข้าวผัดหมู', 'กระเทียมสับ', 10, '0', 1, '2024-10-03 03:31:49', '5'),
(277, 'ข้าวผัดหมู', 'ไข่ไก่', 1, '0', 1, '2024-10-03 03:31:49', '5'),
(278, 'ข้าวผัดหมู', 'ซีอิ๊วขาว', 1, '0', 1, '2024-10-03 03:31:49', '5'),
(279, 'ข้าวผัดหมู', 'น้ำตาลทราย', 1, '0', 1, '2024-10-03 03:31:49', '5'),
(280, 'ข้าวผัดหมู', 'ต้นหอมซอย', 10, '0', 1, '2024-10-03 03:31:49', '5'),
(281, 'ข้าวผัดหมู', 'ข้าวสวย', 2000, '0', 10, '2024-10-03 03:52:45', '7'),
(282, 'ข้าวผัดหมู', 'เนื้อหมู', 2000, '0', 10, '2024-10-03 03:52:45', '7'),
(283, 'ข้าวผัดหมู', 'น้ำมันพืช', 0.3, '0', 10, '2024-10-03 03:52:45', '7'),
(284, 'ข้าวผัดหมู', 'กระเทียมสับ', 100, '0', 10, '2024-10-03 03:52:45', '7'),
(285, 'ข้าวผัดหมู', 'ไข่ไก่', 10, '0', 10, '2024-10-03 03:52:45', '7'),
(286, 'ข้าวผัดหมู', 'ซีอิ๊วขาว', 10, '0', 10, '2024-10-03 03:52:45', '7'),
(287, 'ข้าวผัดหมู', 'น้ำตาลทราย', 10, '0', 10, '2024-10-03 03:52:45', '7'),
(288, 'ข้าวผัดหมู', 'ต้นหอมซอย', 100, '0', 10, '2024-10-03 03:52:45', '7');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_id` (`menu_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `saleshistory`
--
ALTER TABLE `saleshistory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `saleshistory`
--
ALTER TABLE `saleshistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`),
  ADD CONSTRAINT `recipes_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
