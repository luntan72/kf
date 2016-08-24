-- phpMyAdmin SQL Dump
-- version 4.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 03, 2016 at 11:56 AM
-- Server version: 5.5.25a-log
-- PHP Version: 5.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `qygl`
--

-- --------------------------------------------------------

--
-- Table structure for table `fp`
--

CREATE TABLE IF NOT EXISTS `fp` (
`id` int(4) NOT NULL,
  `summary` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `yw_id` int(4) NOT NULL DEFAULT '0' COMMENT '业务',
  `from_date` date NOT NULL COMMENT '起始日期',
  `to_date` date NOT NULL COMMENT '结束日期',
  `amount` decimal(11,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '发票编号',
  `cyr_id` int(4) NOT NULL DEFAULT '0' COMMENT '承运人',
  `yunfei` float NOT NULL DEFAULT '0' COMMENT '运费',
  `in_or_out` int(11) NOT NULL COMMENT '进项或出项',
  `fp_fl_id` int(11) NOT NULL COMMENT '发票类型',
  `remained_amount` decimal(11,2) NOT NULL COMMENT '还未完成支付的金额',
  `hb_id` int(11) NOT NULL COMMENT '伙伴'
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='开出发票' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fp`
--

INSERT INTO `fp` (`id`, `summary`, `yw_id`, `from_date`, `to_date`, `amount`, `code`, `cyr_id`, `yunfei`, `in_or_out`, `fp_fl_id`, `remained_amount`, `hb_id`) VALUES
(1, 'aaa', 20, '2016-05-01', '2016-06-02', '10000.00', '1234567890', 15, 0, 2, 1, '10000.00', 17),
(2, '', 21, '2016-05-01', '2016-06-02', '5000.00', '213', 15, 0, 2, 1, '100.00', 16),
(3, '', 22, '2016-06-02', '2016-06-02', '123.00', '222', 15, 0, 2, 1, '123.00', 16),
(4, '', 23, '2016-06-02', '2016-06-02', '300.00', '123', 15, 0, 2, 1, '300.00', 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fp`
--
ALTER TABLE `fp`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `code` (`code`), ADD KEY `yw_id` (`yw_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `fp`
--
ALTER TABLE `fp`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
