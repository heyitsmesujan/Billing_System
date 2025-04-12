-- Set session options
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
START TRANSACTION;

-- Character set settings
/*!40101 SET NAMES utf8mb4 */;

-- Create database and switch to it
CREATE DATABASE IF NOT EXISTS `Invoice` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `Invoice`;

-- Drop tables if they exist
DROP TABLE IF EXISTS `invoice_particulars`;
DROP TABLE IF EXISTS `invoice_logs`;
DROP TABLE IF EXISTS `invoice_deletion_log`;
DROP TABLE IF EXISTS `invoices`;
DROP TABLE IF EXISTS `images`;
DROP TABLE IF EXISTS `admins`;

-- Create tables with keys directly defined

CREATE TABLE `admins` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `images` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `image_name` VARCHAR(255) NOT NULL,
  `image_data` MEDIUMTEXT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invoices` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reference_no` VARCHAR(50) NOT NULL,
  `client_name` VARCHAR(255) NOT NULL,
  `address` TEXT NOT NULL,
  `total_amount` DECIMAL(10,2) DEFAULT NULL,
  `discount` DECIMAL(10,2) NOT NULL,
  `net_amount` DECIMAL(10,2) NOT NULL,
  `issued_date` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `invoice_number` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reference_no` (`reference_no`),
  UNIQUE KEY `invoice_number` (`invoice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invoice_deletion_log` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` INT(11) NOT NULL,
  `remarks` TEXT NOT NULL,
  `deleted_at` DATETIME DEFAULT current_timestamp(),
  `invoice_number` VARCHAR(100) DEFAULT NULL,
  `reference_no` VARCHAR(100) DEFAULT NULL,
  `client_name` VARCHAR(255) DEFAULT NULL,
  `issued_date` DATE DEFAULT NULL,
  `total_amount` DECIMAL(10,2) DEFAULT NULL,
  `discount` DECIMAL(10,2) DEFAULT NULL,
  `net_amount` DECIMAL(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invoice_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` INT(11) NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `user_id` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_logs_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `invoice_particulars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` INT(11) NOT NULL,
  `description` TEXT NOT NULL,
  `amount` DECIMAL(10,2) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `invoice_id` (`invoice_id`),
  CONSTRAINT `invoice_particulars_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
