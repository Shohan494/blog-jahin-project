-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 28, 2025 at 02:09 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `author_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `bio` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `social_links` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`author_id`, `name`, `email`, `bio`, `profile_image`, `social_links`, `created_at`) VALUES
(1, 'Alice Johnson', 'alice@example.com', 'Passionate food blogger and recipe developer.', 'alice.jpg', 'https://instagram.com/alicefoodie', '2025-06-28 13:24:51'),
(2, 'Bob Smith', 'bob@example.com', 'Lover of all things culinary, sharing recipes and tips.', 'bob.jpg', 'https://facebook.com/bobchef', '2025-06-28 13:30:00'),
(3, 'Carla Green', 'carla@example.com', 'Vegetarian and healthy meal enthusiast.', 'carla.jpg', 'https://twitter.com/carlacooks', '2025-06-28 13:35:00'),
(4, 'David Lee', 'david@example.com', 'Exploring global cuisine one dish at a time.', 'david.jpg', 'https://linkedin.com/in/davidfoodie', '2025-06-28 13:40:00'),
(5, 'Ella Kim', 'ella@example.com', 'Baking addict and dessert recipe creator.', 'ella.jpg', 'https://pinterest.com/ellabakes', '2025-06-28 13:45:00'),
(6, 'Fiona Torres', 'fiona@example.com', 'Home cook sharing easy everyday meals.', 'fiona.jpg', 'https://instagram.com/fionacooks', '2025-06-28 13:50:00'),
(7, 'George Patel', 'george@example.com', 'Street food reviewer and travel eater.', 'george.jpg', 'https://twitter.com/georgeeats', '2025-06-28 13:55:00'),
(8, 'Hannah Liu', 'hannah@example.com', 'Fusion cuisine chef blending East & West.', 'hannah.jpg', 'https://instagram.com/hannahfusion', '2025-06-28 14:00:00'),
(9, 'Ian Miller', 'ian@example.com', 'BBQ master and grilling enthusiast.', 'ian.jpg', 'https://facebook.com/ianbbq', '2025-06-28 14:05:00'),
(10, 'Rivera', 'jasmine@example.com', 'Vegan chef sharing plant-based joy.', 'jasmine.jpg', 'https://pinterest.com/jasminevegan', '2025-06-28 14:10:00');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `content` text NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `author_id` int(11) NOT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `title`, `content`, `author_id`, `status`, `created_at`) VALUES
(1, 'Shorshe Ilish – Mustard Hilsa Delight', '<p>This classic Bengali dish features Hilsa fish cooked in a pungent mustard gravy served with steamed rice.</p>', 1, 'published', '2025-06-28 15:00:00'),
(2, 'Chingri Malai Curry – Prawn Coconut Curry', '<p>Succulent prawns simmered in a creamy coconut milk gravy infused with Bengali spices.</p>', 2, 'published', '2025-06-28 15:05:00'),
(4, 'Luchi & Aloor Dum – Festive Bengali Breakfast', '<p>Soft, deep-fried puffed bread served with spicy potato curry. A Sunday favorite in every Bengali home.</p>', 4, 'draft', '2025-06-28 15:15:00'),
(5, 'Panta Bhat – Fermented Rice with Mustard & Onion', '<p>A traditional poor man’s meal turned delicacy, served with mustard paste, onions, and green chilies.</p>', 5, 'published', '2025-06-28 15:18:00'),
(6, 'Mochar Ghonto – Banana Blossom Curry', '<p>A dry curry made from banana blossoms, coconut, and subtle spices, perfect with steamed rice.</p>', 6, 'published', '2025-06-28 15:22:00'),
(7, 'Shutki Bhuna – Dry Fish Fry', '<p>A powerful punch of flavor from fermented dried fish fried with onions and chili. Not for the faint-hearted!</p>', 7, 'published', '2025-06-28 15:26:00'),
(8, 'Mishti Doi – Sweetened Yogurt Dessert', '<p>This creamy and caramelized sweet yogurt is an iconic Bengali dessert. Best served chilled.</p>', 8, 'draft', '2025-06-28 15:30:00'),
(9, 'Patishapta – Rice Crepes Stuffed with Coconut juice', '<p>Traditional Poush Parbon dessert made of rice flour pancakes filled with jaggery and coconut.</p>', 9, 'published', '2025-06-28 15:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `role` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `firstname`, `lastname`, `email`, `password`, `role`) VALUES
(102, 'jahin', 'Jahin ', 'Sultana', 'jahinsultana21@gmail.com', '$2y$10$G9LU/s5woxnhliDrd38Lp.BeAn2NsrzN4HigplrnSlxqoxTctMmmG', 'admin'),
(103, 'nirob', 'Farid', 'Nirob', 'nirob20@gmail.com', '$2y$10$dycEARtM2fkn.MkqrChIfesqpuutNekBla/fH8hhNc79vo2q3Grxq', 'user'),
(104, 'pr', 'Pritom', 'Dev', 'hassdg@gmail.com', '$2y$10$FDEUlHMOxcAWYoo6xUlcvO0EkfzlynEj1fqKTV1f5AHFhUbZk8voe', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `pro_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `status` enum('Active','Inactive') DEFAULT 'Active',
  `profile_pic` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`pro_id`, `username`, `email`, `status`, `profile_pic`, `updated_at`) VALUES
(1, 'pr', 'hassdg@gmail.com', 'Active', 'profile_685f78e0b96235.34751414.png', '2025-06-28 05:15:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`author_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `Test` (`post_id`),
  ADD KEY `fk_comments_user_id` (`user_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `Test` (`author_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `user_profile_ibfk_1` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=403;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `Test` FOREIGN KEY (`author_id`) REFERENCES `posts` (`post_id`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `post_categories` (
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`post_id`, `category_id`),
  FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`) ON DELETE CASCADE,
  FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
