-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 19, 2022 at 01:26 PM
-- Server version: 8.0.21
-- PHP Version: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_emanuelemessina`
--

-- --------------------------------------------------------

--
-- Table structure for table `atmoeyes_data`
--

CREATE TABLE IF NOT EXISTS `atmoeyes_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `value` int NOT NULL,
  `lon` float NOT NULL,
  `lat` float NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_4` (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci AUTO_INCREMENT=85 ;

--
-- Dumping data for table `atmoeyes_data`
--

INSERT INTO `atmoeyes_data` (`id`, `value`, `lon`, `lat`) VALUES
(68, 81, 13.3462, 38.1017),
(69, 7, 13.3471, 38.1033),
(70, 46, 13.3483, 38.1047),
(71, 100, 13.3471, 38.1056),
(72, 40, 13.3447, 38.1008),
(73, 38, 13.346, 38.0998),
(74, 55, 13.3446, 38.099),
(75, 27, 13.3402, 38.0968),
(76, 57, 13.3407, 38.0993),
(77, 22, 13.3521, 38.1074),
(78, 48, 13.3523, 38.1102),
(79, 30, 13.3538, 38.1081),
(80, 64, 13.3513, 38.1062),
(81, 15, 13.3473, 38.1008),
(82, 52, 13.349, 38.1035),
(83, 76, 13.34, 38.094),
(84, 50, 13.3457, 38.0973);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
