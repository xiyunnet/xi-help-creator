-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- 服务器版本： 8.0.24
-- PHP 版本： 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `help`
--

-- --------------------------------------------------------

--
-- 表的结构 `web_adm`
--

CREATE TABLE IF NOT EXISTS `web_adm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `phone` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reg_date` int DEFAULT '0',
  `last_login` int DEFAULT '0',
  `power` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'user',
  `state` int DEFAULT '1',
  `code` int DEFAULT '0',
  `code_get_date` int DEFAULT '0' COMMENT '验证码获取时间',
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `session` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 转存表中的数据 `web_adm`
--

INSERT INTO `web_adm` (`id`, `phone`, `password`, `reg_date`, `last_login`, `power`, `state`, `code`, `code_get_date`, `username`, `session`) VALUES
(1, 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 1683176260, 1686473575, 'adm', 1, 0, 0, 'demo', '283f4958a9d412ac61aa240e793e5a01');

-- --------------------------------------------------------

--
-- 表的结构 `web_data`
--

CREATE TABLE IF NOT EXISTS `web_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `c` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int DEFAULT '0',
  `date` int DEFAULT '0',
  `title` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `data` mediumtext COLLATE utf8mb4_general_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `web_help`
--

CREATE TABLE IF NOT EXISTS `web_help` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(200) COLLATE utf8mb4_general_ci DEFAULT '',
  `c` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `s` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` int DEFAULT '0',
  `last_update` int DEFAULT '0',
  `info` varchar(500) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lan` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'zh',
  `html` mediumtext COLLATE utf8mb4_general_ci,
  `view` int DEFAULT '0',
  `o` int DEFAULT '0',
  `user_id` int DEFAULT '0',
  `type` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'item',
  `data` mediumtext COLLATE utf8mb4_general_ci,
  `tag` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_publish` int DEFAULT '0' COMMENT '发布',
  `state` int DEFAULT '0',
  `bind_user_id` int DEFAULT NULL,
  `bind_date` int DEFAULT '0',
  `yes` int DEFAULT '0',
  `no` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `web_img`
--

CREATE TABLE IF NOT EXISTS `web_img` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `c` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_id` int DEFAULT '0',
  `ext` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` int DEFAULT '0',
  `path` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `width` int DEFAULT '0',
  `height` int DEFAULT '0',
  `adm_id` int DEFAULT '0',
  `s` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `state` int DEFAULT '1',
  `url` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `size` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- 表的结构 `web_sys_set`
--

CREATE TABLE IF NOT EXISTS `web_sys_set` (
  `id` int NOT NULL AUTO_INCREMENT,
  `webname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `debug` int DEFAULT '0',
  `logo` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_img` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_data` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `bind_edit` int DEFAULT '0' COMMENT '编辑绑定',
  `save_timeout` int DEFAULT '180',
  `bind_timeout` int DEFAULT '600',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='设置';

--
-- 转存表中的数据 `web_sys_set`
--

INSERT INTO `web_sys_set` (`id`, `webname`, `debug`, `logo`, `no_img`, `no_data`, `bind_edit`, `save_timeout`, `bind_timeout`) VALUES
(1, '羲云文档编辑工具', 1, 'https://www.zjhn.top/help/image/1/44_240.jpg', 'https://www.xicloud.top/image/1/35_480.jpg', 'https://www.zjhn.top/help/image/1/45_750.png', 0, 180, 600);

-- --------------------------------------------------------

--
-- 表的结构 `web_update`
--

CREATE TABLE IF NOT EXISTS `web_update` (
  `id` int NOT NULL AUTO_INCREMENT,
  `c` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `sign` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date` int DEFAULT '0',
  `from_id` int DEFAULT '0',
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `version` decimal(10,2) DEFAULT '0.00',
  `last_update` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='更新列表';
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
