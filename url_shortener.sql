-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 13, 2011 at 10:49 PM
-- Server version: 5.1.36
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `url_shortener`
--

-- --------------------------------------------------------

--
-- Table structure for table `redirect_stats`
--

CREATE TABLE IF NOT EXISTS `redirect_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shortener_id` int(11) NOT NULL,
  `referer` varchar(256) NOT NULL,
  `redirect_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `redirect_stats`
--


-- --------------------------------------------------------

--
-- Table structure for table `shortener`
--

CREATE TABLE IF NOT EXISTS `shortener` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `short_url` varchar(64) NOT NULL,
  `redirect_url` varchar(256) NOT NULL,
  `date_created` datetime NOT NULL,
  `is_unique` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `shortener`
--

