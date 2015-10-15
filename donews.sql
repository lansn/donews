-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2015 年 10 月 15 日 08:54
-- 服务器版本: 5.6.12-log
-- PHP 版本: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `donews`
--
CREATE DATABASE IF NOT EXISTS `donews` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `donews`;

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `group` int(1) NOT NULL DEFAULT '1' COMMENT '0超级管理, 1普通管理',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `admin`
--

INSERT INTO `admin` (`id`, `user_name`, `name`, `password`, `email`, `group`) VALUES
(1, 'admin', 'admin7700992222', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1', '173654757@qq.com', 0),
(2, 'xadmin', 'xadmin', '63982e54a7aeb0d89910475ba6dbd3ca6dd4e5a1', 'xadmin@122.com', 1),
(3, 'blues', 'lansn-lan', '1234', 'lansn-lan@uecsh.com', 0);

-- --------------------------------------------------------

--
-- 表的结构 `app`
--

CREATE TABLE IF NOT EXISTS `app` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `http` varchar(50) DEFAULT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `icp` varchar(30) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `tel` varchar(30) DEFAULT NULL,
  `fax` varchar(30) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `app`
--

INSERT INTO `app` (`id`, `name`, `http`, `keywords`, `description`, `icp`, `email`, `tel`, `fax`, `address`) VALUES
(1, '新闻发布系统', '广告歌.com', '淡淡的', '淡淡的', '淡淡的', '123@23.com', '广告00', '广告歌00', '广告歌999000');

-- --------------------------------------------------------

--
-- 表的结构 `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(2) NOT NULL,
  `cid` int(5) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `origin` varchar(200) DEFAULT NULL,
  `keywords` varchar(200) DEFAULT NULL,
  `content` text,
  `datetime` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `article`
--

INSERT INTO `article` (`id`, `uid`, `cid`, `title`, `author`, `origin`, `keywords`, `content`, `datetime`) VALUES
(1, 2, 2, 'ddd方芳芳0011ddd方芳芳0011ddd方芳芳0011', '的订单', '顶顶顶顶', '的顶顶顶顶顶', '嘎嘎嘎个大概', '2015-10-15 05:40:41'),
(2, 2, 1, '戴尔XPS 13极致轻薄本：看外观就知道是高级货', 'ddd', 'ggg', '', 'gdgdg', '2015-10-15 07:43:18');

-- --------------------------------------------------------

--
-- 表的结构 `classis`
--

CREATE TABLE IF NOT EXISTS `classis` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `keywords` varchar(100) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `sort` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `classis`
--

INSERT INTO `classis` (`id`, `name`, `keywords`, `description`, `sort`) VALUES
(1, '科教', '科技教育', '科教 教育', 1),
(2, '娱乐', '娱乐圈', '娱乐新闻', 0);

-- --------------------------------------------------------

--
-- 表的结构 `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `aid` int(10) NOT NULL,
  `author` varchar(20) NOT NULL,
  `content` text NOT NULL,
  `datetime` datetime NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0已审核 1未审核',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `comment`
--

INSERT INTO `comment` (`id`, `aid`, `author`, `content`, `datetime`, `status`) VALUES
(1, 1, '烦烦烦', '', '2015-10-15 05:11:12', 1);

-- --------------------------------------------------------

--
-- 表的结构 `friendly_link`
--

CREATE TABLE IF NOT EXISTS `friendly_link` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `url` varchar(100) NOT NULL,
  `description` varchar(200) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '0已审核 1未审核',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `friendly_link`
--

INSERT INTO `friendly_link` (`id`, `name`, `url`, `description`, `status`) VALUES
(1, '淘测网', 'http://t107.temp.ly200.net/download', '淡淡的', 0),
(2, '新浪网', 'sina.com', 'dddd', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
