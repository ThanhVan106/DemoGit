-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th2 20, 2025 lúc 06:44 PM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: sales_system
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng categories
--

CREATE TABLE categories (
  id int(11) UNSIGNED NOT NULL,
  name varchar(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng categories
--

INSERT INTO categories (id, name) VALUES
(1, 'Demo Category'),
(3, 'Finished Goods'),
(5, 'Machinery'),
(4, 'Packing Materials'),
(2, 'Raw Materials'),
(8, 'Stationery Items'),
(6, 'Work in Progress');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng media
--

CREATE TABLE media (
  id int(11) UNSIGNED NOT NULL,
  file_name varchar(255) NOT NULL,
  file_type varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng products
--

CREATE TABLE products (
  id int(11) UNSIGNED NOT NULL,
  name varchar(255) NOT NULL,
  quantity varchar(50) DEFAULT NULL,
  buy_price decimal(25,2) DEFAULT NULL,
  sale_price decimal(25,2) NOT NULL,
  categorie_id int(11) UNSIGNED NOT NULL,
  media_id int(11) DEFAULT 0,
  date datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng products
--

INSERT INTO products (id, name, quantity, buy_price, sale_price, categorie_id, media_id, date) VALUES
(1, 'Demo Product', '48', '100.00', '500.00', 1, 0, '2021-04-04 16:45:51'),
(2, 'Box Varieties', '12000', '55.00', '130.00', 4, 0, '2021-04-04 18:44:52'),
(3, 'Wheat', '69', '2.00', '5.00', 2, 0, '2021-04-04 18:48:53'),
(4, 'Timber', '1200', '780.00', '1069.00', 2, 0, '2021-04-04 19:03:23'),
(5, 'W1848 Oscillating Floor Drill Press', '26', '299.00', '494.00', 5, 0, '2021-04-04 19:11:30'),
(6, 'Portable Band Saw XBP02Z', '42', '280.00', '415.00', 5, 0, '2021-04-04 19:13:35'),
(7, 'Life Breakfast Cereal-3 Pk', '107', '3.00', '7.00', 3, 0, '2021-04-04 19:15:38'),
(8, 'Chicken of the Sea Sardines W', '110', '13.00', '20.00', 3, 0, '2021-04-04 19:17:11'),
(9, 'Disney Woody - Action Figure', '67', '29.00', '55.00', 3, 0, '2021-04-04 19:19:20'),
(10, 'Hasbro Marvel Legends Series Toys', '106', '219.00', '322.00', 3, 0, '2021-04-04 19:20:28'),
(11, 'Packing Chips', '78', '21.00', '31.00', 4, 0, '2021-04-04 19:25:22'),
(12, 'Classic Desktop Tape Dispenser 38', '160', '5.00', '10.00', 8, 0, '2021-04-04 19:48:01'),
(13, 'Small Bubble Cushioning Wrap', '199', '8.00', '19.00', 4, 0, '2021-04-04 19:49:00');

-- --------------------------------------------------------


CREATE TABLE users (
  id int(11) UNSIGNED NOT NULL,
  name varchar(60) NOT NULL,
  username varchar(50) NOT NULL,
  password varchar(255) NOT NULL,
  user_level int(11) NOT NULL,
  image varchar(255) DEFAULT 'no_image.jpg',
  status int(1) NOT NULL,
  last_login datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng users
--

INSERT INTO users (id, name, username, password, user_level, image, status, last_login) VALUES
(1, 'Harry Denn', 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 1, 'no_image.png', 1, '2024-05-25 18:35:40'),
(2, 'John Walker', 'special', 'ba36b97a41e7faf742ab09bf88405ac04f99599a', 2, 'no_image.png', 1, '2021-04-04 19:53:26'),
(3, 'Christopher', 'user', '12dea96fec20593566ab75692c9949596833adc9', 3, 'no_image.png', 1, '2021-04-04 19:54:46'),
(4, 'Natie Williams', 'natie', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'no_image.png', 1, NULL),
(5, 'Kevin', 'kevin', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 3, 'no_image.png', 1, '2021-04-04 19:54:29'),
(6, 'Demo', 'nhapkho', 'fac9788b0c3a2a0e46770bd7565677458b75db9a', 4, 'no_image.jpg', 1, '2024-05-25 18:36:15');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng user_groups
--

CREATE TABLE user_groups (
  id int(11) NOT NULL,
  group_name varchar(150) NOT NULL,
  group_level int(11) NOT NULL,
  group_status int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Đang đổ dữ liệu cho bảng user_groups
--

INSERT INTO user_groups (id, group_name, group_level, group_status) VALUES
(1, 'Admin', 1, 1),
(2, 'special', 2, 1),
(3, 'User', 3, 1),
(4, 'Wakehouse', 4, 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng categories
--
ALTER TABLE categories
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY name (name);

--
-- Chỉ mục cho bảng media
--
ALTER TABLE media
  ADD PRIMARY KEY (id),
  ADD KEY id (id);

--
-- Chỉ mục cho bảng products
--
ALTER TABLE products
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY name (name),
  ADD KEY categorie_id (categorie_id),
  ADD KEY media_id (media_id);

--
-- Chỉ mục cho bảng sales
--
ALTER TABLE sales
  ADD PRIMARY KEY (id),
  ADD KEY product_id (product_id);

--
-- Chỉ mục cho bảng users
--
ALTER TABLE users
  ADD PRIMARY KEY (id),
  ADD KEY user_level (user_level);

--
-- Chỉ mục cho bảng user_groups
--
ALTER TABLE user_groups
  ADD PRIMARY KEY (id),
  ADD UNIQUE KEY group_level (group_level);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng categories
--
ALTER TABLE categories
  MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng media
--
ALTER TABLE media
  MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng products
--
ALTER TABLE products
  MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


-- AUTO_INCREMENT cho bảng users
--
ALTER TABLE users
  MODIFY id int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng user_groups
--
ALTER TABLE user_groups
  MODIFY id int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng products
--
ALTER TABLE products
  ADD CONSTRAINT FK_products FOREIGN KEY (categorie_id) REFERENCES categories (id) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng users
--
ALTER TABLE users
  ADD CONSTRAINT FK_user FOREIGN KEY (user_level) REFERENCES user_groups (group_level) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
ALTER TABLE orders
ADD customer_name VARCHAR(255) AFTER id,
ADD total_price DOUBLE NOT NULL DEFAULT 0 AFTER customer_name;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;