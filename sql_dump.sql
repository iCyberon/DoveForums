-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 29, 2012 at 05:09 PM
-- Server version: 5.5.16
-- PHP Version: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dove`
--

-- --------------------------------------------------------

--
-- Table structure for table `forums`
--

CREATE TABLE IF NOT EXISTS `forums` (
  `id` int(16) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_post_by` varchar(255) NOT NULL,
  `last_post_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `visibility` varchar(255) NOT NULL,
  `parent` varchar(50) NOT NULL,
  `order` varchar(50) NOT NULL,
  `history_last_post_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `history_last_post_by` varchar(255) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `sticky` enum('yes','no') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `forums`
--

INSERT INTO `forums` (`id`, `title`, `permalink`, `created_by`, `created_date`, `last_post_by`, `last_post_date`, `type`, `status`, `visibility`, `parent`, `order`, `history_last_post_date`, `history_last_post_by`, `tags`, `sticky`) VALUES
(1, 'First Test Forum', 'first-test-forum', 'admin', '2012-10-29 00:00:00', 'admin', '2012-10-29 00:00:00', 'forum', 'open', 'public', '', '', '2012-10-29 00:00:00', 'admin', '', 'no');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
