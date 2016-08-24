-- phpMyAdmin SQL Dump
-- version 4.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 16, 2016 at 09:41 AM
-- Server version: 5.5.25a-log
-- PHP Version: 5.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `property`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_type`
--

CREATE TABLE IF NOT EXISTS `data_type` (
`id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `data_type`
--

INSERT INTO `data_type` (`id`, `name`) VALUES
(1, 'String'),
(2, 'Number');

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE IF NOT EXISTS `device` (
`id` int(11) NOT NULL,
  `device_type_id` int(11) NOT NULL,
  `fix_code` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `manager_id` int(11) NOT NULL COMMENT '谁管理',
  `owner_id` int(11) NOT NULL COMMENT '当前在谁手里',
  `stock_date` date NOT NULL COMMENT '入库日期',
  `expire_date` date NOT NULL COMMENT '预计报废日期',
  `isactive` int(11) NOT NULL DEFAULT '1' COMMENT '是否有效',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creater_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Dumping data for table `device`
--

INSERT INTO `device` (`id`, `device_type_id`, `fix_code`, `name`, `manager_id`, `owner_id`, `stock_date`, `expire_date`, `isactive`, `created`, `creater_id`) VALUES
(1, 1, 'AF256207', 'B19268-02', 2, 48, '0000-00-00', '2016-06-27', 0, '2016-06-27 08:49:16', 2),
(2, 2, 'asdff', 'asdfadsf', 2, 2, '0000-00-00', '2016-06-28', 1, '2016-06-28 06:26:48', 2),
(14, 1, 'pc-001', 'pc-001', 48, 48, '2016-06-29', '2016-06-29', 1, '2016-06-29 10:28:00', 48);

-- --------------------------------------------------------

--
-- Table structure for table `device_property`
--

CREATE TABLE IF NOT EXISTS `device_property` (
`id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `content` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `device_property`
--

INSERT INTO `device_property` (`id`, `device_id`, `property_id`, `content`) VALUES
(1, 1, 2, 'aaa'),
(2, 1, 1, '4G'),
(3, 2, 2, '123'),
(5, 14, 2, '123'),
(6, 14, 1, '123'),
(7, 14, 3, '123');

-- --------------------------------------------------------

--
-- Table structure for table `device_trace`
--

CREATE TABLE IF NOT EXISTS `device_trace` (
`id` int(11) NOT NULL,
  `device_id` int(11) NOT NULL,
  `borrower_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `device_trace`
--

INSERT INTO `device_trace` (`id`, `device_id`, `borrower_id`, `borrow_date`, `created`) VALUES
(1, 1, 35, '2016-06-27', '2016-06-27 09:07:18'),
(2, 1, 48, '2016-06-28', '2016-06-28 06:56:52');

-- --------------------------------------------------------

--
-- Table structure for table `device_type`
--

CREATE TABLE IF NOT EXISTS `device_type` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `device_type`
--

INSERT INTO `device_type` (`id`, `name`, `note`) VALUES
(1, 'PC', ''),
(2, 'TV', ''),
(3, 'USB Camera', '');

-- --------------------------------------------------------

--
-- Table structure for table `device_type_property`
--

CREATE TABLE IF NOT EXISTS `device_type_property` (
`id` int(11) NOT NULL,
  `device_type_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `device_type_property`
--

INSERT INTO `device_type_property` (`id`, `device_type_id`, `property_id`) VALUES
(3, 2, 2),
(5, 1, 3),
(6, 3, 3),
(7, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `property`
--

CREATE TABLE IF NOT EXISTS `property` (
`id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `data_type_id` int(11) NOT NULL DEFAULT '1' COMMENT '值类型',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `property`
--

INSERT INTO `property` (`id`, `name`, `data_type_id`, `note`) VALUES
(1, 'Memory', 2, ''),
(2, 'CPU', 1, ''),
(3, 'Speed', 1, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_type`
--
ALTER TABLE `data_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_property`
--
ALTER TABLE `device_property`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_trace`
--
ALTER TABLE `device_trace`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_type`
--
ALTER TABLE `device_type`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `device_type_property`
--
ALTER TABLE `device_type_property`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `property`
--
ALTER TABLE `property`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_type`
--
ALTER TABLE `data_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `device_property`
--
ALTER TABLE `device_property`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `device_trace`
--
ALTER TABLE `device_trace`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `device_type`
--
ALTER TABLE `device_type`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `device_type_property`
--
ALTER TABLE `device_type_property`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `property`
--
ALTER TABLE `property`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
