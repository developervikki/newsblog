-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 29, 2025 at 02:27 PM
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
-- Database: `newsblog`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `email`, `password`, `created_at`) VALUES
(1, 'admin@newsblog.com', '$2y$10$XY/4rlkwKLVq6NP9LbzJ7eXSJz0GMoiLpDNUNzRsvCX91vjwxPkLW', '2025-06-29 14:54:13');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Sportss', NULL, '2025-06-29 10:27:41'),
(2, 'Entertanments', NULL, '2025-06-29 10:27:59');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `author_name` varchar(100) DEFAULT NULL,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author_name`, `comment`, `is_approved`, `created_at`, `is_deleted`) VALUES
(1, 3, 'aaa', 'jdsjfjjds', 1, '2025-06-29 16:26:22', 0),
(2, 3, 'aaa', 'jdsjfjjds', 1, '2025-06-29 16:26:58', 0),
(3, 3, 'aaa', 'jdsjfjjds', 1, '2025-06-29 16:27:48', 0),
(4, 4, 'jdsjlfj', 'dsjfjfsd', 1, '2025-06-29 16:43:59', 1),
(5, 4, 'jdsjlfj', 'dsjfjfsd', 0, '2025-06-29 16:49:04', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `slug`, `content`, `created_at`) VALUES
(1, 'About', 'about', '<p>dhjfdjdfvjdf</p>', '2025-06-29 10:38:53');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `content` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_deleted` tinyint(1) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `short_description`, `content`, `image_path`, `created_at`, `is_deleted`, `category_id`) VALUES
(3, 'Fatal error: Uncaught mysqli_sql_exception: Unknown column &#039;is_deleted&#039; in &#039;where clause&#039;', 'Fatal error: Uncaught mysqli_sql_exception: Unknown column &#039;is_deleted&#039; in &#039;where clause&#039;', '<h3>How to Fix It</h3>\r\n\r\n<p>You have two main options:</p>\r\n\r\n<hr />\r\n<h3><strong>Option 1: Add the <code>is_deleted</code> column to your table</strong></h3>\r\n\r\n<p>If you <strong>intend</strong> to use soft deletes (i.e., hide records instead of deleting them), then you need to add the <code>is_deleted</code> column.</p>\r\n\r\n<p>Run this SQL in <strong>phpMyAdmin</strong> or a MySQL client:</p>\r\n\r\n<pre>\r\n\r\n&nbsp;</pre>\r\n\r\n<p>sql</p>\r\n\r\n<p>कॉपी करेंबदलें</p>\r\n\r\n<p><code>ALTER TABLE posts ADD is_deleted TINYINT(1) DEFAULT 0; </code></p>\r\n\r\n<p>This adds a column named <code>is_deleted</code> that you can use to mark posts as &quot;deleted&quot; without actually removing them from the database.</p>\r\n\r\n<p>Then, your query like this will work:</p>\r\n\r\n<pre>\r\n\r\n&nbsp;</pre>\r\n\r\n<p>php</p>\r\n\r\n<p>कॉपी करेंबदलें</p>\r\n\r\n<p><code>$statement = $conn-&gt;prepare(&quot;SELECT id, title FROM posts WHERE is_deleted = 0&quot;); </code></p>\r\n\r\n<hr />\r\n<h3><strong>Option 2: Remove the <code>is_deleted</code> condition from the query</strong></h3>\r\n\r\n<p>If you <strong>don&rsquo;t use</strong> soft deletes and don&rsquo;t need the <code>is_deleted</code> column, then just remove that part from your SQL query in <code>dashboard.php</code> at line 35:</p>\r\n\r\n<p><strong>Before (causing error):</strong></p>\r\n\r\n<pre>\r\n\r\n&nbsp;</pre>\r\n\r\n<p>php</p>\r\n\r\n<p>कॉपी करेंबदलें</p>\r\n\r\n<p><code>$statement = $conn-&gt;prepare(&quot;SELECT id, title FROM posts WHERE is_deleted = 0&quot;); </code></p>\r\n\r\n<p><strong>After (fixed if you don&rsquo;t need <code>is_deleted</code>):</strong></p>\r\n\r\n<pre>\r\n\r\n&nbsp;</pre>\r\n\r\n<p>php</p>\r\n\r\n<p>कॉपी करेंबदलें</p>\r\n\r\n<p><code>$statement = $conn-&gt;prepare(&quot;SELECT id, title FROM posts&quot;); </code></p>\r\n\r\n<hr />\r\n<h3>???? Best Practice (Recommended)</h3>\r\n\r\n<p>If you&#39;re building an admin panel where you want to &quot;hide&quot; posts instead of permanently deleting them, using <code>is_deleted</code> is smart. Just make sure it&#39;s part of your table.</p>\r\n\r\n<p>You can also add timestamps like <code>deleted_at</code> to track when the post was deleted, which is useful in advanced systems.</p>\r\n', 'uploads/1751193692-Room Desktop Wallpaper 3d Render.jpg', '2025-06-29 16:11:32', 0, 2),
(4, 'hjfdjhfdjj', 'dshfhsd', '<p>jsdjlsjlf</p>\r\n', 'uploads/1751193769-Untitled_design-removebg-preview.png', '2025-06-29 16:12:49', 0, 1),
(5, 'jfjdls', 'jjdsjjd', '<p>dshjhhds</p>\r\n', 'uploads/1751193796-WhatsApp Image 2025-06-23 at 11.26.31 AM (1).jpeg', '2025-06-29 16:13:16', 0, 1),
(6, 'jdsjdjjdsf', 'jdsjlfjjsdjjsdjlsdl;', '<p>jjhdsfhjhjvfhjhj</p>\r\n', 'uploads/1751193846-book.jpeg', '2025-06-29 16:14:06', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','editor','viewer') DEFAULT 'editor',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'dd', 'a@gmal.com', '$2y$10$Pbnte4YSaaHgJ1ii/FCgYux3aJKMazCyEb2uVXSjQ6M1XqtB9ycRi', 'editor', '2025-06-29 12:13:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
