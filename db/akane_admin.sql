-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2014 at 01:57 PM
-- Server version: 5.6.19
-- PHP Version: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `akane_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `nama` varchar(40) NOT NULL DEFAULT '',
  `level` int(1) NOT NULL DEFAULT '3',
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama`, `level`, `status`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Administrator', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `publish` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id`, `judul`, `isi`, `tags`, `publish`) VALUES
(1, 'lorem ipsum', 'lorem ipsum', 'lorem ipsum', '');

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `config_id` int(11) NOT NULL AUTO_INCREMENT,
  `config_edit` int(11) NOT NULL,
  `config_name` varchar(100) NOT NULL,
  `config_value` text NOT NULL,
  `config_desc` text NOT NULL,
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=80 ;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`config_id`, `config_edit`, `config_name`, `config_value`, `config_desc`) VALUES
(10, 0, 'member_photo_width', '148', 'Member Photo Width'),
(3, 0, 'language_set', 'indonesia', 'Language Set'),
(4, 0, 'max_pass_length', '4', 'Maximal Password Length'),
(5, 0, 'service_mail', 'noreply@wisnu-hafid.net', 'Service Mail'),
(6, 1, 'sitename', 'Wisnu-hafid.net', 'Website Name'),
(55, 0, 'banner_pic_width', '335', 'Banner Picture Width'),
(56, 0, 'banner_pic_height', '325', 'Banner Picture Height'),
(19, 0, 'max_upload_size_int', '2000000', 'Maximal Upload File Size'),
(20, 0, 'max_upload_size_str', '2 MB', 'Maximal Upload File Size (String)'),
(49, 1, 'meta_keyword', 'akane admin', 'Meta Keyword'),
(50, 1, 'meta_desc', 'akane admin', 'Meta Description'),
(51, 1, 'meta_title', 'Akane Admin | Wisnu-Hafid.net', 'Meta Title'),
(52, 1, 'google_analytics', '', 'Kode Google Analytics'),
(57, 1, 'contact_phone', '+6285294800898', 'Contact Phone'),
(58, 1, 'contact_mail', 'inuvalogic@gmail.com', 'Contact Email'),
(59, 1, 'contact_facebook', 'http://www.facebook.com/wisnuhafid', 'Facebook'),
(60, 1, 'contact_twitter', 'https://www.twitter.com/#/wisnuhafid', 'Twitter'),
(63, 1, 'company_name', 'Wisnu-Hafid.net', 'Company Name'),
(64, 1, 'company_address', 'Jl. Taman Merkuri Timur VI no. 15 RT 03/ RW 04\r\nkel. Manjahlega kec. Rancasari\r\nKomp. Margahayu Raya Blok U\r\nKota Bandung\r\nJawa Barat - Indonesia 40295', 'Company Address'),
(72, 0, 'product_width', '425', 'Product Pic Width'),
(76, 0, 'big_height', '600', 'Product Pic Big Height'),
(73, 0, 'product_height', '425', 'Product Pic Height'),
(74, 0, 'thumb_height', '160', 'Product Thumb Height'),
(75, 0, 'thumb_width', '160', 'Product Thumb Width'),
(77, 0, 'big_width', '600', 'Product Pic Big Width'),
(78, 0, 'small_height', '125', 'Small Height'),
(79, 0, 'small_width', '125', 'Small Width');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
