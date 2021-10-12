-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- 主機: 127.0.0.1
-- 產生時間： 2017-02-22 00:09:51
-- 伺服器版本: 10.1.19-MariaDB
-- PHP 版本： 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `nt_work_management`
--
CREATE DATABASE IF NOT EXISTS `nt_work_management` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `nt_work_management`;

-- --------------------------------------------------------

--
-- 資料表結構 `file`
--

DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
  `id` int(11) NOT NULL,
  `work` int(11) NOT NULL,
  `name` varchar(9999) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `src` varchar(9999) CHARACTER SET big5 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` smallint(6) NOT NULL,
  `user` varchar(99) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `pd` varchar(999) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` varchar(999) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(99) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `rank` tinyint(4) NOT NULL DEFAULT '1',
  `belong` varchar(999) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `user`
--

INSERT INTO `user` (`id`, `user`, `pd`, `name`, `email`, `rank`, `belong`) VALUES
(1, 'admin', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 'ADMIN_NAME', 'test@gmail.com', 1, '');

-- --------------------------------------------------------

--
-- 資料表結構 `board`
--

DROP TABLE IF EXISTS `board`;
CREATE TABLE `board` (
  `id` int(11) NOT NULL,
  `post` varchar(9999) COLLATE utf8_unicode_ci NOT NULL,
  `user` int(11) NOT NULL,
  `date` varchar(99) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `board`
--

INSERT INTO `board` (`id`, `post`, `user`, `date`) VALUES
(1, 'HIiiiihihihi', 1, '2017-02-21_17點45分'),
(2, 'HIiiiihihihi22222', 1, '2017-02-21_17點45分'),
(3, 'HIiiiihihihi33333333', 1, '2017-02-21_17點45分');

-- --------------------------------------------------------

--
-- 資料表結構 `work`
--

DROP TABLE IF EXISTS `work`;
CREATE TABLE `work` (
  `id` mediumint(6) NOT NULL,
  `content` varchar(9999) COLLATE utf8_unicode_ci NOT NULL,
  `overday` date DEFAULT NULL,
  `complete` tinyint(1) NOT NULL,
  `order_user` varchar(999) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `work_user` varchar(999) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `ps` varchar(999) CHARACTER SET utf32 COLLATE utf32_unicode_ci NOT NULL,
  `post_time` varchar(99) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 資料表的匯出資料 `work`
--

INSERT INTO `work` (`id`, `content`, `overday`, `complete`, `order_user`, `work_user`, `ps`, `post_time`) VALUES
(40, 'aaaaaa', '0000-00-00', 0, '1', '1', '', '2017-02-21');

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `board`
--
ALTER TABLE `board`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `file`
--
ALTER TABLE `file`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work` (`work`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD KEY `rank` (`rank`);

--
-- 資料表索引 `work`
--
ALTER TABLE `work`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_user`(255)),
  ADD KEY `work_id` (`work_user`(255));

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `board`
--
ALTER TABLE `board`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用資料表 AUTO_INCREMENT `file`
--
ALTER TABLE `file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用資料表 AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用資料表 AUTO_INCREMENT `work`
--
ALTER TABLE `work`
  MODIFY `id` mediumint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
