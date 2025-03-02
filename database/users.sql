/*
 Navicat Premium Data Transfer

 Source Server         : Localhost 3336
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3336
 Source Schema         : chat

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 02/03/2025 02:54:37
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(0) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_seen` timestamp(0) NULL DEFAULT NULL,
  `is_online` tinyint(0) NULL DEFAULT 0,
  `is_active` tinyint(0) NULL DEFAULT 0,
  `about` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `photo_url` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `activation_code` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `is_system` tinyint(0) NULL DEFAULT 0,
  `player_id` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'One signal user id',
  `is_subscribed` tinyint(1) NULL DEFAULT NULL,
  `privacy` int(0) NOT NULL DEFAULT 1,
  `gender` int(0) NULL DEFAULT NULL,
  `deleted_at` timestamp(0) NULL DEFAULT NULL,
  `language` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'en',
  `is_super_admin` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE,
  UNIQUE INDEX `users_player_id_unique`(`player_id`) USING BTREE,
  INDEX `users_name_index`(`name`) USING BTREE,
  INDEX `users_email_index`(`email`) USING BTREE,
  INDEX `users_phone_index`(`phone`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Super Admin', 'admin@gmail.com', '2023-03-23 14:45:02', '$2y$10$wNaEZGRMjjPJbYm28X9Jzuk1BD7ec9kRWcLRYFDqdFe27JeWyHDmi', NULL, '2025-03-01 13:38:10', 0, 1, NULL, NULL, NULL, NULL, '2023-03-23 14:45:02', '2025-03-01 13:38:10', 1, NULL, NULL, 1, NULL, NULL, 'en', 1);
INSERT INTO `users` VALUES (3, 'Thai Jing', 'srosthai00@gmail.com', '2025-03-01 09:00:37', '$2y$10$Xq/3bGbKIqBIJZn/Izoa9uo1fEsjS/A1c1EmhYJREKBIOqyPWlNLu', '876543555', '2025-03-01 11:49:34', 0, 1, '', NULL, '67c2ccb62bcdc', 'Hc7fpbHnQBHR4Gko1IRqE4hoUvqmxzROlIZo0Zd3UrnWzJ6mGbyAU5p32uWM', '2025-03-01 09:00:37', '2025-03-01 11:49:34', 0, NULL, 0, 1, 1, NULL, 'en', 0);

SET FOREIGN_KEY_CHECKS = 1;
