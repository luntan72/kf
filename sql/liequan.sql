-- phpMyAdmin SQL Dump
-- version 4.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 31, 2016 at 10:31 AM
-- Server version: 5.5.25a-log
-- PHP Version: 5.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `liequan`
--

-- --------------------------------------------------------

--
-- Table structure for table `issue`
--

CREATE TABLE IF NOT EXISTS `issue` (
`id` int(11) NOT NULL,
  `isssu_type_id` int(11) NOT NULL COMMENT '问题类型',
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '问题描述',
  `severity_id` int(11) NOT NULL COMMENT '严重性',
  `issue_priority_id` int(11) NOT NULL COMMENT '优先级',
  `assigner_id` int(11) NOT NULL DEFAULT '0' COMMENT '分配该问题的人',
  `assignee_id` int(11) NOT NULL DEFAULT '0' COMMENT '被指定解决该问题的人',
  `root_cause` text COLLATE utf8_unicode_ci NOT NULL COMMENT '根源',
  `issue_status_id` int(11) NOT NULL COMMENT '当前状态',
  `creater_id` int(11) NOT NULL COMMENT '提交者',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '提交时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题定义' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_history`
--

CREATE TABLE IF NOT EXISTS `issue_history` (
  `id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `track_log` text COLLATE utf8_unicode_ci NOT NULL,
  `creater_id` int(11) NOT NULL COMMENT '提交者',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `issue_keyword`
--

CREATE TABLE IF NOT EXISTS `issue_keyword` (
`id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_priority`
--

CREATE TABLE IF NOT EXISTS `issue_priority` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题状态' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `issue_priority`
--

INSERT INTO `issue_priority` (`id`, `name`, `note`) VALUES
(1, 'P1', ''),
(2, 'P2', ''),
(3, 'P3', ''),
(4, 'P4', ''),
(5, 'P5', '');

-- --------------------------------------------------------

--
-- Table structure for table `issue_related`
--

CREATE TABLE IF NOT EXISTS `issue_related` (
`id` int(11) NOT NULL,
  `issue_id` int(11) NOT NULL,
  `related_issue_id` int(11) NOT NULL COMMENT '相关的问题',
  `note` int(11) NOT NULL COMMENT '相关性说明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='描述各issue间的相关性' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `issue_status`
--

CREATE TABLE IF NOT EXISTS `issue_status` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题状态' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `issue_status`
--

INSERT INTO `issue_status` (`id`, `name`, `note`) VALUES
(1, 'INIT', ''),
(2, 'OPEN', ''),
(3, 'ASSIGNED', ''),
(4, 'CLOSED', ''),
(5, 'REOPEN', '');

-- --------------------------------------------------------

--
-- Table structure for table `issue_type`
--

CREATE TABLE IF NOT EXISTS `issue_type` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题状态' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `issue_type`
--

INSERT INTO `issue_type` (`id`, `name`, `note`) VALUES
(1, 'BUG', ''),
(2, 'NEW REQUIREMENT', '');

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE IF NOT EXISTS `keyword` (
`id` int(11) NOT NULL,
  `name` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `severity`
--

CREATE TABLE IF NOT EXISTS `severity` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='问题状态' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `severity`
--

INSERT INTO `severity` (`id`, `name`, `note`) VALUES
(1, 'S1', ''),
(2, 'S2', ''),
(3, 'S3', ''),
(4, 'S4', ''),
(5, 'S5', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `issue`
--
ALTER TABLE `issue`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_keyword`
--
ALTER TABLE `issue_keyword`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_priority`
--
ALTER TABLE `issue_priority`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_related`
--
ALTER TABLE `issue_related`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_status`
--
ALTER TABLE `issue_status`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `issue_type`
--
ALTER TABLE `issue_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword`
--
ALTER TABLE `keyword`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `severity`
--
ALTER TABLE `severity`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `issue`
--
ALTER TABLE `issue`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issue_keyword`
--
ALTER TABLE `issue_keyword`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issue_priority`
--
ALTER TABLE `issue_priority`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `issue_related`
--
ALTER TABLE `issue_related`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `issue_status`
--
ALTER TABLE `issue_status`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `issue_type`
--
ALTER TABLE `issue_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `keyword`
--
ALTER TABLE `keyword`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `severity`
--
ALTER TABLE `severity`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
