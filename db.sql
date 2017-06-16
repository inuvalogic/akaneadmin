/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50505
Source Host           : 127.0.0.1:3306
Source Database       : akane_admin

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-06-16 11:15:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `name` varchar(40) NOT NULL DEFAULT '',
  `level` int(1) NOT NULL DEFAULT '3',
  `status` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', '015eb9562238dcd13eb08c072c6a4fa3', 'Administrator', '1', 'active');

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `publish` enum('Yes','No') NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of article
-- ----------------------------
INSERT INTO `article` VALUES ('1', 'lorem ipsum', 'lorem ipsum', 'lorem ipsum', '');

-- ----------------------------
-- Table structure for config
-- ----------------------------
DROP TABLE IF EXISTS `config`;
CREATE TABLE `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `config_category` varchar(255) NOT NULL,
  `config_name` varchar(100) NOT NULL,
  `config_value` text NOT NULL,
  `config_desc` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of config
-- ----------------------------
INSERT INTO `config` VALUES ('1', 'framework', 'max_pass_length', '4', 'Maximal Password Length');
INSERT INTO `config` VALUES ('2', 'framework', 'service_mail', 'noreply@pit-womenimaging.com', 'Service Mail');
INSERT INTO `config` VALUES ('3', 'framework', 'max_upload_size_int', '2000000', 'Maximal Upload File Size');
INSERT INTO `config` VALUES ('4', 'framework', 'max_upload_size_str', '2 MB', 'Maximal Upload File Size (String)');
INSERT INTO `config` VALUES ('5', 'images', 'thumb_height', '100', 'Product Thumb Height');
INSERT INTO `config` VALUES ('6', 'images', 'thumb_width', '150', 'Product Thumb Width');
INSERT INTO `config` VALUES ('7', 'images', 'big_height', '300', 'Product Pic Big Height');
INSERT INTO `config` VALUES ('8', 'images', 'big_width', '450', 'Product Pic Big Width');
INSERT INTO `config` VALUES ('9', 'images', 'small_height', '73', 'Small Height');
INSERT INTO `config` VALUES ('10', 'images', 'small_width', '102', 'Small Width');
INSERT INTO `config` VALUES ('11', 'images', 'medium_width', '400', 'Medium Width');
INSERT INTO `config` VALUES ('12', 'images', 'medium_height', '300', 'Medium Height');
INSERT INTO `config` VALUES ('13', 'images', 'logo_width', '100', 'Logo Width');
INSERT INTO `config` VALUES ('14', 'images', 'logo_height', '100', 'Logo Height');
INSERT INTO `config` VALUES ('25', 'profile', 'contact_phone', '081321425825', 'Contact Phone');
INSERT INTO `config` VALUES ('26', 'profile', 'contact_mail', 'info@webhade.id', 'Contact Email');
INSERT INTO `config` VALUES ('27', 'profile', 'contact_us_email', 'info@webhade.id', 'Contact Us Email Notification');
INSERT INTO `config` VALUES ('28', 'profile', 'company_name', 'WebHade Creative', 'Company Name');
INSERT INTO `config` VALUES ('29', 'profile', 'company_address', 'Jl. Taman Merkuri Timur VI no. 15 kel. Manjahlega kec. Rancasari Kota Bandung Jawa Barat - Indonesia 40295', 'Company Address Office');
INSERT INTO `config` VALUES ('30', 'profile', 'company_logo', 'logo.png', 'Company Logo');
INSERT INTO `config` VALUES ('31', 'profile', 'company_favicon', 'favicon.ico', 'Company Favicon');
INSERT INTO `config` VALUES ('32', 'profile', 'contact_facebook', '', 'Facebook');
INSERT INTO `config` VALUES ('33', 'profile', 'contact_twitter', '', 'Twitter');
INSERT INTO `config` VALUES ('34', 'profile', 'contact_gplus', '', 'Google+');
INSERT INTO `config` VALUES ('35', 'profile', 'contact_instagram', '', 'Instagram');
INSERT INTO `config` VALUES ('36', 'profile', 'contact_linkedin', '', 'Linkedin');
INSERT INTO `config` VALUES ('37', 'profile', 'contact_youtube', '', 'Youtube Channel');
INSERT INTO `config` VALUES ('38', 'web', 'sitename', 'WebHade Creative', 'Website Name');
INSERT INTO `config` VALUES ('39', 'web', 'meta_keyword', '', 'SEO Meta Keyword');
INSERT INTO `config` VALUES ('40', 'web', 'meta_desc', '', 'SEO Meta Description');
INSERT INTO `config` VALUES ('41', 'web', 'meta_title', '', 'SEO Meta Title');
INSERT INTO `config` VALUES ('42', 'web', 'google_analytics', '', 'Kode Google Analytics');
INSERT INTO `config` VALUES ('43', 'web', 'siteurl', 'www.webhade.id', 'Website URL');
INSERT INTO `config` VALUES ('44', 'web', 'maintenance_mode', 'No', 'Maintenance Mode');

-- ----------------------------
-- Table structure for kategori
-- ----------------------------
DROP TABLE IF EXISTS `kategori`;
CREATE TABLE `kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of kategori
-- ----------------------------
INSERT INTO `kategori` VALUES ('1', 'test kategori');

-- ----------------------------
-- Table structure for sub_kategori
-- ----------------------------
DROP TABLE IF EXISTS `sub_kategori`;
CREATE TABLE `sub_kategori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kategori` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_kategori` (`id_kategori`),
  CONSTRAINT `fk_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of sub_kategori
-- ----------------------------
INSERT INTO `sub_kategori` VALUES ('1', '1', 'test sub');
