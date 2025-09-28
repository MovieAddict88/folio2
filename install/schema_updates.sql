-- This script updates the database schema to support the new features.
-- It should be run after the initial schema has been set up.

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `download_tokens`
--
CREATE TABLE IF NOT EXISTS `download_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(255) NOT NULL,
  `file_id` int(11) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Schema Updates for existing tables
--

-- Add video_url and social media links to about_me table
ALTER TABLE `about_me`
  ADD COLUMN `video_url` VARCHAR(255) DEFAULT NULL AFTER `phone`,
  ADD COLUMN `facebook_url` VARCHAR(255) DEFAULT NULL AFTER `video_url`,
  ADD COLUMN `tiktok_url` VARCHAR(255) DEFAULT NULL AFTER `facebook_url`,
  ADD COLUMN `youtube_url` VARCHAR(255) DEFAULT NULL AFTER `tiktok_url`,
  ADD COLUMN `instagram_url` VARCHAR(255) DEFAULT NULL AFTER `youtube_url`;

-- Add media_thumbnails to experience table
ALTER TABLE `experience`
  ADD COLUMN `media_thumbnails` TEXT DEFAULT NULL AFTER `description`;

-- Add external_links and category_tags to projects table
ALTER TABLE `projects`
  ADD COLUMN `external_links` TEXT DEFAULT NULL AFTER `media_url`,
  ADD COLUMN `category_tags` TEXT DEFAULT NULL AFTER `external_links`;

-- Add video_url to testimonials table
ALTER TABLE `testimonials`
  ADD COLUMN `video_url` VARCHAR(255) DEFAULT NULL AFTER `author_image_url`;

-- Add security and tracking fields to downloads table
ALTER TABLE `downloads`
  ADD COLUMN `is_password_protected` TINYINT(1) NOT NULL DEFAULT 0 AFTER `file_url`,
  ADD COLUMN `password_hash` VARCHAR(255) DEFAULT NULL AFTER `is_password_protected`,
  ADD COLUMN `download_count` INT(11) NOT NULL DEFAULT 0 AFTER `password_hash`,
  CHANGE `file_url` `file_path` VARCHAR(255) NOT NULL; -- Renaming for clarity

-- Add category to skills table
ALTER TABLE `skills`
  ADD COLUMN `category` VARCHAR(50) NOT NULL DEFAULT 'hard' AFTER `level`;

-- Note: You may need to manually populate the new columns with data.
-- Example for a new admin user (password: 'adminpassword')
-- INSERT INTO `admins` (`username`, `password_hash`) VALUES ('admin', '$2y$10$your_password_hash_here');