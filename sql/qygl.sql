-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-08-17 11:02:33
-- 服务器版本： 10.1.13-MariaDB
-- PHP Version: 5.6.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qygl`
--

-- --------------------------------------------------------

--
-- 表的结构 `baoxian_type`
--

CREATE TABLE `baoxian_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='保险类型';

--
-- 转存表中的数据 `baoxian_type`
--

INSERT INTO `baoxian_type` (`id`, `name`, `note`) VALUES
(1, '国家社保', ''),
(2, '商业保险', '');

-- --------------------------------------------------------

--
-- 表的结构 `bizhong`
--

CREATE TABLE `bizhong` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '人民币'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='币种';

--
-- 转存表中的数据 `bizhong`
--

INSERT INTO `bizhong` (`id`, `name`) VALUES
(1, '人民币');

-- --------------------------------------------------------

--
-- 表的结构 `calc_method`
--

CREATE TABLE `calc_method` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='计算方法';

--
-- 转存表中的数据 `calc_method`
--

INSERT INTO `calc_method` (`id`, `name`, `note`) VALUES
(1, '01. 绝对值', ''),
(2, '02. 体积比例', '根据主输入的体积按照给定的比例进行计算'),
(3, '03. 面积比例', '根据主输入的表面积按照给定的比例进行计算');

-- --------------------------------------------------------

--
-- 表的结构 `chuku`
--

CREATE TABLE `chuku` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL DEFAULT '0',
  `pici_id` int(11) NOT NULL DEFAULT '0' COMMENT '批次明细',
  `amount` float NOT NULL COMMENT '数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='出库';

--
-- 转存表中的数据 `chuku`
--

INSERT INTO `chuku` (`id`, `yw_id`, `pici_id`, `amount`) VALUES
(1, 4, 1, 2),
(2, 6, 2, 3),
(3, 7, 2, 1),
(4, 24, 5, 4),
(5, 24, 6, 2),
(6, 24, 7, 3),
(7, 26, 14, 1000),
(8, 26, 17, 500),
(9, 27, 19, 1500),
(10, 28, 17, 500),
(11, 28, 18, 1000),
(12, 29, 21, 480),
(13, 29, 22, 80),
(14, 31, 20, 30),
(15, 33, 24, 40),
(16, 33, 25, 20),
(17, 34, 26, 60),
(18, 34, 27, 40),
(19, 35, 28, 100),
(21, 42, 29, 100),
(22, 44, 31, 1000),
(23, 45, 31, 200),
(24, 45, 41, 1200),
(25, 46, 43, 2000),
(26, 46, 44, 1000),
(27, 46, 45, 1400),
(32, 57, 46, 2000),
(33, 58, 46, 2000),
(34, 59, 32, 200),
(35, 59, 42, 200),
(36, 60, 46, 200),
(37, 61, 46, 200),
(38, 63, 57, 2000),
(39, 63, 58, 2000),
(40, 63, 60, 200),
(41, 63, 61, 200),
(42, 64, 62, 4400),
(43, 64, 63, 600),
(44, 65, 59, 400),
(45, 44, 67, 1),
(46, 45, 67, 1),
(47, 56, 73, 2),
(48, 56, 74, 2),
(49, 56, 75, 2),
(50, 56, 76, 3),
(51, 56, 77, 2),
(52, 56, 78, 1),
(53, 58, 30, 100),
(54, 3, 1, 500),
(55, 7, 2, 100),
(56, 7, 1, 500),
(57, 8, 4, 0.303),
(58, 18, 6, 25),
(59, 18, 3, 500),
(60, 18, 7, 500),
(61, 19, 10, 25);

-- --------------------------------------------------------

--
-- 表的结构 `ck`
--

CREATE TABLE `ck` (
  `id` int(4) NOT NULL,
  `ck_fl_id` int(11) NOT NULL DEFAULT '1',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `volumn` int(11) NOT NULL COMMENT '库容',
  `kuguan_id` int(11) NOT NULL DEFAULT '0' COMMENT '库管'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='仓库';

--
-- 转存表中的数据 `ck`
--

INSERT INTO `ck` (`id`, `ck_fl_id`, `name`, `address`, `volumn`, `kuguan_id`) VALUES
(1, 1, '废钢仓库', '第二车间', 0, 0),
(2, 1, '石蜡仓库', '第一车间', 0, 0),
(3, 2, '模具仓库', '', 0, 0),
(4, 16, '成品仓库', '', 0, 0),
(5, 3, '劳保用品仓库', '', 0, 0),
(6, 4, '维修备件仓库', '', 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `ck_fl`
--

CREATE TABLE `ck_fl` (
  `id` int(11) NOT NULL,
  `wz_fl_id` int(11) NOT NULL COMMENT '用来存放哪一类物资',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='仓库类型是非常重要的信息，涉及到工序之间的衔接。每个工序从某一类仓库里获得输入，同时产出输出到某类一仓库';

--
-- 转存表中的数据 `ck_fl`
--

INSERT INTO `ck_fl` (`id`, `wz_fl_id`, `name`, `note`) VALUES
(1, 1, '原料仓库', ''),
(2, 2, '设备仓库', ''),
(3, 6, '办公用品仓库', ''),
(4, 0, '维修备件仓库', ''),
(5, 0, '劳保用品仓库', ''),
(6, 0, '其他类型仓库', ''),
(7, 0, '蜡型毛坯仓库', ''),
(8, 0, '蜡型成品仓库', ''),
(9, 0, '蜡型树仓库', ''),
(10, 0, '失蜡前模壳仓库', ''),
(11, 0, '失蜡后模壳仓库', ''),
(12, 0, '浇筑后模壳仓库', ''),
(13, 0, '清砂后产品串仓库', ''),
(14, 0, '产品毛坯仓库', ''),
(15, 0, '次品仓库', ''),
(16, 0, '成品仓库', '');

-- --------------------------------------------------------

--
-- 表的结构 `ck_pd`
--

CREATE TABLE `ck_pd` (
  `id` int(11) NOT NULL,
  `pici_id` int(11) NOT NULL COMMENT '批次',
  `happen_date` date NOT NULL COMMENT '盘点日期',
  `amount` float NOT NULL COMMENT '实际值',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注，实际值和期望值不符的原因和可能',
  `jbr_id` int(11) NOT NULL COMMENT '盘点人',
  `expected_amount` float NOT NULL COMMENT '期望值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='仓库盘点';

-- --------------------------------------------------------

--
-- 表的结构 `ck_weizhi`
--

CREATE TABLE `ck_weizhi` (
  `id` int(11) NOT NULL,
  `ck_id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='仓库位置';

--
-- 转存表中的数据 `ck_weizhi`
--

INSERT INTO `ck_weizhi` (`id`, `ck_id`, `name`, `note`) VALUES
(1, 1, '第一仓位', '测试'),
(2, 1, '第二仓位', '');

-- --------------------------------------------------------

--
-- 表的结构 `contact_method`
--

CREATE TABLE `contact_method` (
  `id` int(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='联系方式';

--
-- 转存表中的数据 `contact_method`
--

INSERT INTO `contact_method` (`id`, `name`) VALUES
(1, '电话'),
(2, '手机'),
(3, '传真'),
(4, '微信'),
(5, 'QQ'),
(6, '电子邮件');

-- --------------------------------------------------------

--
-- 表的结构 `credit_level`
--

CREATE TABLE `credit_level` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `total` int(4) NOT NULL DEFAULT '0' COMMENT '额度',
  `duration` int(2) NOT NULL DEFAULT '0' COMMENT '账期',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='信用度定义';

--
-- 转存表中的数据 `credit_level`
--

INSERT INTO `credit_level` (`id`, `name`, `total`, `duration`, `note`) VALUES
(1, 'VIP', 10000, 30, NULL),
(2, '普通', 5000, 30, '');

-- --------------------------------------------------------

--
-- 表的结构 `data_type`
--

CREATE TABLE `data_type` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='数据类型';

--
-- 转存表中的数据 `data_type`
--

INSERT INTO `data_type` (`id`, `name`) VALUES
(1, '数值'),
(2, '字符串');

-- --------------------------------------------------------

--
-- 表的结构 `defect`
--

CREATE TABLE `defect` (
  `id` int(11) NOT NULL,
  `zl_id` int(11) NOT NULL DEFAULT '1' COMMENT '质量等级',
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `pic` text COLLATE utf8_unicode_ci COMMENT '图片',
  `root_cause` text COLLATE utf8_unicode_ci COMMENT '原因',
  `method` text COLLATE utf8_unicode_ci COMMENT '解决办法'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='缺陷';

--
-- 转存表中的数据 `defect`
--

INSERT INTO `defect` (`id`, `zl_id`, `name`, `description`, `pic`, `root_cause`, `method`) VALUES
(1, 1, '正品，无缺陷', '正品，没有瑕疵', NULL, NULL, NULL),
(2, 1, '盘点损耗', '这个指盘点时得到的数量和应有的计算量之间的差值，作为损耗，保存在这里', NULL, NULL, NULL),
(3, 2, '砂眼', '需要电焊修补', NULL, NULL, NULL),
(4, 2, '毛刺', '需要砂轮打磨', NULL, NULL, NULL),
(5, 1, '测试', '的', NULL, NULL, NULL),
(6, 3, '漏串', '浇注过程中因为模壳破裂等原因导致的钢水外漏', NULL, NULL, NULL),
(7, 2, '蜡泪', '在蜡型焊接组树的过程中，熔化的蜡滴在蜡型上，造成缺陷', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `defect_gx`
--

CREATE TABLE `defect_gx` (
  `id` int(11) NOT NULL,
  `defect_id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资的缺陷情况管理';

--
-- 转存表中的数据 `defect_gx`
--

INSERT INTO `defect_gx` (`id`, `defect_id`, `gx_id`) VALUES
(29, 1, 6),
(40, 5, 9),
(52, 3, 6),
(59, 6, 7),
(60, 3, 7),
(63, 4, 12),
(64, 3, 12),
(67, 1, 2),
(68, 4, 2),
(73, 1, 3),
(74, 7, 4),
(75, 1, 5),
(76, 4, 5),
(77, 5, 5),
(78, 3, 5);

-- --------------------------------------------------------

--
-- 表的结构 `defect_gx_wz`
--

CREATE TABLE `defect_gx_wz` (
  `id` int(11) NOT NULL,
  `gx_wz_id` int(11) NOT NULL DEFAULT '0',
  `defect_id` int(11) NOT NULL,
  `price` float NOT NULL DEFAULT '0' COMMENT '单价',
  `ck_weizhi_id` int(11) NOT NULL COMMENT '仓库位置',
  `remained` float NOT NULL COMMENT '当前剩余量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资的缺陷情况管理';

--
-- 转存表中的数据 `defect_gx_wz`
--

INSERT INTO `defect_gx_wz` (`id`, `gx_wz_id`, `defect_id`, `price`, `ck_weizhi_id`, `remained`) VALUES
(42, 108, 1, 10000, 0, 0),
(60, 125, 1, 1000, 0, 301.697),
(61, 126, 1, 2000, 0, 0),
(62, 127, 1, 0.1, 1, 0),
(65, 130, 1, 0, 0, 75),
(66, 131, 1, 0.05, 1, 1000),
(67, 132, 1, 0, 0, 0),
(68, 133, 1, 200, 0, 0),
(69, 134, 1, 20, 0, 0),
(70, 135, 1, 0.6, 0, 0),
(75, 138, 1, 0.1, 1, 200),
(76, 138, 4, 0, 1, 0),
(77, 139, 1, 0.1, 1, 0),
(78, 140, 1, 0.1, 1, 25),
(79, 141, 1, 0.1, 1, 0),
(80, 142, 1, 0.1, 1, 0),
(81, 143, 1, 0.1, 1, 0),
(82, 144, 1, 0.1, 1, 0),
(83, 145, 1, 1000, 1, 3),
(84, 146, 1, 1000, 1, 3),
(85, 147, 1, 1000, 1, 3),
(86, 148, 1, 1000, 1, 3),
(87, 149, 1, 1000, 1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `dept`
--

CREATE TABLE `dept` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='部门';

--
-- 转存表中的数据 `dept`
--

INSERT INTO `dept` (`id`, `name`, `note`) VALUES
(1, '生产部门', ''),
(2, '财务部门', ''),
(3, '后勤部门', ''),
(4, '总经理办公室', ''),
(5, '销售部门', '');

-- --------------------------------------------------------

--
-- 表的结构 `dingdan`
--

CREATE TABLE `dingdan` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL DEFAULT '0',
  `wz_id` int(11) NOT NULL,
  `defect_id` int(11) NOT NULL DEFAULT '1' COMMENT '质量要求',
  `price` float NOT NULL DEFAULT '0' COMMENT '单价',
  `amount` float NOT NULL COMMENT '数量',
  `plan_date` date NOT NULL COMMENT '交付日期',
  `expire_date` date NOT NULL COMMENT '失效期，也就是最后的有效期',
  `completed_amount` float NOT NULL DEFAULT '0' COMMENT '已经完成多少数量',
  `dingdan_status_id` int(11) NOT NULL DEFAULT '1' COMMENT '订单状态'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='描述采购或销售订单情况';

--
-- 转存表中的数据 `dingdan`
--

INSERT INTO `dingdan` (`id`, `yw_id`, `wz_id`, `defect_id`, `price`, `amount`, `plan_date`, `expire_date`, `completed_amount`, `dingdan_status_id`) VALUES
(1, 4, 3, 1, 1000, 2, '0000-00-00', '0000-00-00', 2, 2),
(2, 9, 3, 1, 1000, 500, '2016-05-22', '0000-00-00', 300, 1),
(6, 13, 7, 1, 1.2, 2000, '2016-05-27', '0000-00-00', 0, 1),
(8, 15, 7, 1, 1.2, 200, '2016-05-26', '0000-00-00', 0, 1),
(9, 15, 8, 1, 2.1, 200, '2016-05-26', '0000-00-00', 0, 1),
(10, 16, 4, 1, 2000, 10, '2016-05-13', '0000-00-00', 0, 1),
(11, 29, 4, 1, 2000, 12, '2016-06-16', '0000-00-00', 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `dingdan_jfjh`
--

CREATE TABLE `dingdan_jfjh` (
  `id` int(11) NOT NULL,
  `dingdan_id` int(11) NOT NULL,
  `plan_date` date NOT NULL COMMENT '计划交付日期',
  `plan_amount` float NOT NULL COMMENT '计划交付数量',
  `happen_date` date NOT NULL COMMENT '实际交付日期',
  `happen_amount` float NOT NULL COMMENT '实际交付数量',
  `jf_yw_id` int(11) DEFAULT '0' COMMENT '交付业务',
  `pici_id` int(11) DEFAULT '0',
  `note` text COLLATE utf8_unicode_ci,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单交付计划';

--
-- 转存表中的数据 `dingdan_jfjh`
--

INSERT INTO `dingdan_jfjh` (`id`, `dingdan_id`, `plan_date`, `plan_amount`, `happen_date`, `happen_amount`, `jf_yw_id`, `pici_id`, `note`, `created`) VALUES
(1, 1, '0000-00-00', 0, '2015-08-12', 5, 3, 1, NULL, '2015-08-12 08:02:59'),
(2, 1, '0000-00-00', 0, '2015-08-12', 2, 4, 1, NULL, '2015-08-12 08:11:19'),
(3, 1, '0000-00-00', 0, '2015-08-13', 5, 5, 2, NULL, '2015-08-13 05:52:21'),
(4, 1, '0000-00-00', 0, '2015-08-13', 3, 6, 2, NULL, '2015-08-13 05:55:36'),
(5, 1, '0000-00-00', 0, '2015-08-13', 1, 7, 2, NULL, '2015-08-13 06:05:12'),
(6, 1, '0000-00-00', 0, '2015-08-13', 5, 8, 3, NULL, '2015-08-13 06:18:02'),
(7, 1, '0000-00-00', 0, '2015-08-13', 1, 9, 4, NULL, '2015-08-13 06:18:47'),
(26, 1, '0000-00-00', 0, '2016-05-09', 2, 5, 4, NULL, '2016-05-09 09:20:12'),
(27, 2, '2016-05-12', 222, '2016-05-13', 300, 17, 9, NULL, '2016-05-12 09:32:44'),
(28, 2, '2016-05-22', 278, '0000-00-00', 0, 0, 0, NULL, '2016-05-12 09:32:44'),
(32, 6, '2016-05-13', 1002, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 05:44:23'),
(33, 6, '2016-05-27', 998, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 05:44:23'),
(34, 8, '2016-05-13', 100, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 06:10:37'),
(35, 8, '2016-05-26', 100, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 06:10:37'),
(36, 9, '2016-05-13', 100, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 06:10:37'),
(37, 9, '2016-05-26', 100, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 06:10:37'),
(38, 10, '2016-05-13', 10, '0000-00-00', 0, 0, 0, NULL, '2016-05-13 07:26:59'),
(39, 11, '2016-06-06', 6, '0000-00-00', 0, 0, 0, NULL, '2016-06-06 07:49:53'),
(40, 11, '2016-06-16', 6, '0000-00-00', 0, 0, 0, NULL, '2016-06-06 07:49:53');

-- --------------------------------------------------------

--
-- 表的结构 `dingdan_status`
--

CREATE TABLE `dingdan_status` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='订单状态';

--
-- 转存表中的数据 `dingdan_status`
--

INSERT INTO `dingdan_status` (`id`, `name`) VALUES
(1, '执行中'),
(2, '已结束'),
(3, '已取消');

-- --------------------------------------------------------

--
-- 表的结构 `dj`
--

CREATE TABLE `dj` (
  `id` int(4) NOT NULL,
  `dj_fl_id` int(4) NOT NULL COMMENT '单据分类',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '内部编号',
  `pic` blob COMMENT '照片',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '相应的电子文档',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='单据';

-- --------------------------------------------------------

--
-- 表的结构 `dj_fl`
--

CREATE TABLE `dj_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `in_out` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '-1：采购；1：销售'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='单据分类，主要有合同、送货单、图纸等';

-- --------------------------------------------------------

--
-- 表的结构 `doc`
--

CREATE TABLE `doc` (
  `id` int(4) NOT NULL,
  `doc_fl_id` int(4) NOT NULL COMMENT '文档分类',
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `code` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '内部编号',
  `pic` blob COMMENT '照片',
  `filename` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '相应的电子文档',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='单据';

-- --------------------------------------------------------

--
-- 表的结构 `doc_fl`
--

CREATE TABLE `doc_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='单据分类，主要有合同、送货单、图纸等';

-- --------------------------------------------------------

--
-- 表的结构 `doc_keyword`
--

CREATE TABLE `doc_keyword` (
  `id` int(11) NOT NULL,
  `doc_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `fp`
--

CREATE TABLE `fp` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发票';

--
-- 转存表中的数据 `fp`
--

INSERT INTO `fp` (`id`, `summary`, `yw_id`, `from_date`, `to_date`, `amount`, `code`, `cyr_id`, `yunfei`, `in_or_out`, `fp_fl_id`, `remained_amount`, `hb_id`) VALUES
(1, 'aaa', 20, '2016-05-01', '2016-06-02', '10000.00', '1234567890', 15, 0, 2, 1, '10000.00', 17),
(2, '', 21, '2016-05-01', '2016-06-02', '5000.00', '213', 15, 0, 2, 1, '100.00', 16),
(3, '', 22, '2016-06-02', '2016-06-02', '123.00', '222', 15, 0, 2, 1, '123.00', 16),
(4, '', 23, '2016-06-02', '2016-06-02', '300.00', '123', 15, 0, 2, 1, '300.00', 16);

-- --------------------------------------------------------

--
-- 表的结构 `fp_fl`
--

CREATE TABLE `fp_fl` (
  `id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发票类型';

--
-- 转存表中的数据 `fp_fl`
--

INSERT INTO `fp_fl` (`id`, `name`) VALUES
(1, '普通发票'),
(2, '增值税发票');

-- --------------------------------------------------------

--
-- 表的结构 `fp_yw`
--

CREATE TABLE `fp_yw` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL,
  `fp_id` int(11) NOT NULL,
  `amount` decimal(11,2) NOT NULL COMMENT '支付/回款了多少金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='业务支付了哪些发票';

--
-- 转存表中的数据 `fp_yw`
--

INSERT INTO `fp_yw` (`id`, `yw_id`, `fp_id`, `amount`) VALUES
(1, 28, 3, '100.00'),
(2, 28, 4, '300.00'),
(3, 28, 2, '100.00');

-- --------------------------------------------------------

--
-- 表的结构 `gender`
--

CREATE TABLE `gender` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `gender`
--

INSERT INTO `gender` (`id`, `name`) VALUES
(1, '男'),
(2, '女'),
(3, '法人');

-- --------------------------------------------------------

--
-- 表的结构 `gx`
--

CREATE TABLE `gx` (
  `id` int(4) NOT NULL,
  `gx_fl_id` int(4) NOT NULL COMMENT '工序类型',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `replaced_wz_id` int(11) DEFAULT '0' COMMENT '被置换掉的材料',
  `wz_id` int(11) DEFAULT '0' COMMENT '该工序生成的产品的材质，如石蜡，废钢等',
  `has_shell` int(11) DEFAULT '2' COMMENT '是否有外壳，1表示有外壳，2表示没有外壳',
  `need_mj` int(11) DEFAULT '2' COMMENT '是否需要模具，1表示需要，2表示不需要',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序，主要定义主输入和主输出';

--
-- 转存表中的数据 `gx`
--

INSERT INTO `gx` (`id`, `gx_fl_id`, `name`, `replaced_wz_id`, `wz_id`, `has_shell`, `need_mj`, `note`) VALUES
(1, 6, '0. 采购', 0, 0, 2, 2, '采购作为一道工序存在，这样可以将整个工厂的活动组织起来''<|\\>/{}?&^%$#@!()'),
(2, 1, '01. 造型', 0, 3, 2, 1, '用蜡料置换模具里的空气成型'),
(3, 5, '02. 蜡型清理', 0, 5, 2, 2, '检查蜡型质量并进行合适的修补以及清理。这道工序有一个特殊点就是一般情况下，造型工不会登记生产数据，而由清理工序来确定造型工序的数据。因此，在登记这道工序的生产数据时，应同时生成造型工序的生产数据。'),
(4, 2, '03. 蜡型焊接', 0, 5, 2, 2, '将多个相同或不同种类的蜡型焊接到同一个蜡型棒上，形成蜡型树'),
(5, 4, '04. 涂料制模', 0, 11, 1, 2, '将蜡型树外部涂裹上制壳材料'),
(6, 1, '05. 失蜡', 0, 5, 1, 2, '将实心的模壳通过加温方式将失蜡融化，从而形成空心模壳'),
(7, 1, '06. 浇注', 0, 4, 1, 2, '将废钢融化成钢水，注入空心的模壳里，形成实心的模壳'),
(8, 5, '07. 清砂', 0, 0, 2, 2, '将已经浇注好的实心模壳外部的砂子清楚'),
(9, 3, '08. 产品分解', 0, 0, 2, 2, '将清砂后的产品数用特定的设备将产品从产品棒上分离出来'),
(10, 6, '09. 一级分拣', 0, 0, 2, 2, '将产品放入抛丸机内进行进一步的清砂'),
(11, 5, '10. 抛丸清砂', 0, 0, 2, 2, '将抛丸清砂后的产品进行初步分拣，按产品质量进行分类'),
(12, 5, '11. 焊接修补', 0, 4, 2, 2, '将表面有沙眼类缺陷的产品进行电焊修理'),
(13, 5, '12. 打磨', 0, 4, 2, 2, '对浇口和表面用砂轮进行打磨'),
(14, 6, '13. 二级分拣', 0, 4, 2, 2, '对打磨好的产品进行二级分拣，根据质量进行分类'),
(15, 6, '14. 包装', 0, 0, 2, 2, '对正品进行包装，准备发晕'),
(16, 6, '15. 检验入库', 0, 0, 2, 2, '将包装好的产品入库'),
(20, 6, '16. 发运', 0, 0, 2, 2, '将包装好的产品运送到客户处');

-- --------------------------------------------------------

--
-- 表的结构 `gx_de`
--

CREATE TABLE `gx_de` (
  `id` int(11) NOT NULL,
  `wz_id` int(11) DEFAULT NULL COMMENT '产品',
  `gx_id` int(11) DEFAULT NULL COMMENT '工序',
  `rengong` float DEFAULT '0' COMMENT '需要的人工',
  `changdi_area` float DEFAULT '0' COMMENT '需要的场地面积'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序定额';

--
-- 转存表中的数据 `gx_de`
--

INSERT INTO `gx_de` (`id`, `wz_id`, `gx_id`, `rengong`, `changdi_area`) VALUES
(3, 8, 2, 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `gx_de_input`
--

CREATE TABLE `gx_de_input` (
  `id` int(4) NOT NULL,
  `gx_de_id` int(4) NOT NULL COMMENT '具体工序',
  `wz_id` int(4) NOT NULL COMMENT '输入物资',
  `yl_id` int(11) NOT NULL COMMENT '产品使用什么原料，比如是石蜡还是钢铁',
  `has_shell` int(11) NOT NULL DEFAULT '2' COMMENT '是否有外壳，1表示有外壳，2表示没有外壳',
  `defect_id` int(11) NOT NULL DEFAULT '1' COMMENT '缺陷',
  `amount` float NOT NULL DEFAULT '1' COMMENT '数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='某些类性工序的输入';

-- --------------------------------------------------------

--
-- 表的结构 `gx_de_output`
--

CREATE TABLE `gx_de_output` (
  `id` int(4) NOT NULL,
  `gx_de_id` int(4) NOT NULL COMMENT '具体工序',
  `wz_id` int(4) NOT NULL COMMENT '输入物资',
  `yl_id` int(11) NOT NULL COMMENT '产品使用什么原料，比如是石蜡还是钢铁',
  `has_shell` int(11) NOT NULL DEFAULT '2' COMMENT '是否有外壳，1表示有外壳，2表示没有外壳',
  `amount` float NOT NULL DEFAULT '1' COMMENT '数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='某些类性工序的输出，根据产出不同的质量，输出到不同的仓库';

--
-- 转存表中的数据 `gx_de_output`
--

INSERT INTO `gx_de_output` (`id`, `gx_de_id`, `wz_id`, `yl_id`, `has_shell`, `amount`) VALUES
(1, 3, 8, 5, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `gx_fl`
--

CREATE TABLE `gx_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序分类，如转换，组合，分解等';

--
-- 转存表中的数据 `gx_fl`
--

INSERT INTO `gx_fl` (`id`, `name`, `note`) VALUES
(1, '置换', '用一种材料置换另一种材料，所用材料的数量由产品的体积决定。\r\n如：造型、失蜡和浇铸工序，都属于置换类型。\r\n造型：用石蜡置换模具里的空气，石蜡是置入物资，用量比例是1.01。置出物为空（空气）\r\n\r\n失蜡：用空（空气）置换模壳里的石蜡。空（空气）是置入物资，用量是0.置出物为石蜡，置出量为0.95.\r\n\r\n浇铸：用钢铁置换模壳里的空气。钢铁是置入物资，用量为1.01，置出物为空（空气）\r\n\r\n'),
(2, '组合', '组合就是将多个产品组合成另一个产品。组合后得到的产品的体积是组合物资的总和。\r\n如蜡型焊接工序，属于组合类型。\r\n\r\n和组合相对应的是分解类型。'),
(3, '分解', '和组合相对应'),
(4, '涂裹', '主要就是制壳工序，所用的材料是固定的，但量和产品的表面积相关。'),
(5, '加工', '如后处理等，不会造成数量上的减少和形态上的变化'),
(6, '非生产性工序', '如分拣等，不会进行实质性的变化，不需要进行定额管理');

-- --------------------------------------------------------

--
-- 表的结构 `gx_hjcs`
--

CREATE TABLE `gx_hjcs` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL,
  `hjcs_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL COMMENT '参数值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序--环境参数';

--
-- 转存表中的数据 `gx_hjcs`
--

INSERT INTO `gx_hjcs` (`id`, `gx_id`, `hjcs_id`, `content`) VALUES
(1, 7, 1, ''),
(2, 7, 2, ''),
(3, 5, 3, '');

-- --------------------------------------------------------

--
-- 表的结构 `gx_input`
--

CREATE TABLE `gx_input` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL COMMENT '工序',
  `from_gx_id` int(11) DEFAULT '1',
  `wz_id` int(11) NOT NULL COMMENT '需要的物资',
  `defect_id` int(11) DEFAULT '1' COMMENT '缺陷，默认为正品',
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `calc_method_id` int(11) NOT NULL DEFAULT '1' COMMENT '计算方法'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序需要的输入';

--
-- 转存表中的数据 `gx_input`
--

INSERT INTO `gx_input` (`id`, `gx_id`, `from_gx_id`, `wz_id`, `defect_id`, `amount`, `calc_method_id`) VALUES
(52, 7, 1, 4, 1, 1.01, 2),
(55, 2, 1, 3, 1, 1.01, 2),
(57, 5, 1, 10, 1, 0.1, 3),
(58, 5, 1, 11, 1, 0.1, 3),
(59, 5, 1, 12, 1, 0.1, 3),
(60, 5, 1, 13, 1, 0.1, 3),
(61, 5, 1, 14, 1, 0.1, 3);

-- --------------------------------------------------------

--
-- 表的结构 `gx_input_defect`
--

CREATE TABLE `gx_input_defect` (
  `id` int(11) NOT NULL,
  `defect_id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资的缺陷情况管理';

--
-- 转存表中的数据 `gx_input_defect`
--

INSERT INTO `gx_input_defect` (`id`, `defect_id`, `gx_id`) VALUES
(1, 4, 12),
(2, 3, 12),
(3, 1, 3),
(4, 4, 3);

-- --------------------------------------------------------

--
-- 表的结构 `gx_output`
--

CREATE TABLE `gx_output` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL COMMENT '工序',
  `wz_id` int(11) NOT NULL COMMENT '需要的物资',
  `defect_id` int(11) NOT NULL DEFAULT '1',
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `calc_method_id` int(11) NOT NULL DEFAULT '1' COMMENT '计算方法'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序的输出';

--
-- 转存表中的数据 `gx_output`
--

INSERT INTO `gx_output` (`id`, `gx_id`, `wz_id`, `defect_id`, `amount`, `calc_method_id`) VALUES
(2, 6, 5, 1, 0.9, 2);

-- --------------------------------------------------------

--
-- 表的结构 `gx_pre_gx`
--

CREATE TABLE `gx_pre_gx` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL,
  `pre_gx_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='前置工序';

--
-- 转存表中的数据 `gx_pre_gx`
--

INSERT INTO `gx_pre_gx` (`id`, `gx_id`, `pre_gx_id`) VALUES
(5, 6, 5),
(10, 8, 7),
(11, 9, 8),
(12, 10, 9),
(13, 11, 10),
(19, 14, 13),
(20, 15, 10),
(21, 15, 14),
(22, 13, 10),
(23, 13, 12),
(24, 13, 14),
(26, 16, 15),
(27, 20, 16),
(29, 7, 6),
(30, 12, 11),
(31, 12, 14),
(34, 3, 2),
(35, 4, 3),
(36, 5, 4);

-- --------------------------------------------------------

--
-- 表的结构 `gx_work_type`
--

CREATE TABLE `gx_work_type` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL,
  `work_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工序涉及到的工种';

--
-- 转存表中的数据 `gx_work_type`
--

INSERT INTO `gx_work_type` (`id`, `gx_id`, `work_type_id`) VALUES
(2, 12, 17),
(3, 12, 16),
(5, 2, 6),
(6, 3, 9),
(7, 4, 7),
(8, 5, 10);

-- --------------------------------------------------------

--
-- 表的结构 `gx_wz`
--

CREATE TABLE `gx_wz` (
  `id` int(4) NOT NULL,
  `gx_id` int(11) NOT NULL DEFAULT '0',
  `wz_id` int(11) NOT NULL DEFAULT '0',
  `min_kc` float DEFAULT '0' COMMENT '最小库存',
  `max_kc` float DEFAULT '0' COMMENT '最大库存',
  `pd_days` int(11) DEFAULT NULL COMMENT '盘点周期',
  `pd_last` date DEFAULT NULL COMMENT '最后一次盘点日期',
  `chengpinlv` int(11) NOT NULL DEFAULT '100' COMMENT '成品率，100为最高，0为最低'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='某个工序中某个物资某种质量等级的详细情况，包括默认单价，默认存放位置';

--
-- 转存表中的数据 `gx_wz`
--

INSERT INTO `gx_wz` (`id`, `gx_id`, `wz_id`, `min_kc`, `max_kc`, `pd_days`, `pd_last`, `chengpinlv`) VALUES
(49, 0, 15, 0, 0, NULL, NULL, 100),
(50, 0, 16, 0, 0, NULL, NULL, 100),
(51, 0, 17, 0, 0, NULL, NULL, 100),
(52, 0, 18, 0, 0, NULL, NULL, 100),
(53, 2, 21, 0, 0, 0, NULL, 100),
(57, 1, 22, 1, 23, 12, NULL, 100),
(64, 1, 23, 2, 10, 12, NULL, 100),
(65, 1, 24, 1, 10, 30, NULL, 100),
(108, 1, 26, 1, 3, 180, NULL, 100),
(125, 1, 3, 5, 15, 30, NULL, 100),
(126, 1, 4, 10, 30, 30, NULL, 100),
(127, 2, 6, 100, 300, 0, NULL, 100),
(130, 3, 6, 0, 0, NULL, NULL, 100),
(131, 2, 7, 0, 1000, 20, NULL, 100),
(132, 3, 7, 0, 0, 0, NULL, 100),
(133, 1, 1, 0, 0, 0, NULL, 100),
(134, 1, 2, 0, 0, 0, NULL, 100),
(135, 1, 5, 0, 0, 0, NULL, 100),
(138, 2, 8, 0, 0, 0, NULL, 100),
(139, 4, 9, 0, 0, 0, NULL, 100),
(140, 5, 9, 0, 0, 0, NULL, 100),
(141, 6, 9, 0, 0, 0, NULL, 100),
(142, 7, 9, 0, 0, 0, NULL, 100),
(143, 8, 9, 0, 0, 0, NULL, 100),
(144, 9, 9, 0, 0, 0, NULL, 100),
(145, 1, 10, 2, 10, 30, NULL, 100),
(146, 1, 11, 2, 10, 30, NULL, 100),
(147, 1, 12, 2, 10, 30, NULL, 100),
(148, 1, 13, 2, 10, 30, NULL, 100),
(149, 1, 14, 2, 10, 30, NULL, 100);

-- --------------------------------------------------------

--
-- 表的结构 `gy`
--

CREATE TABLE `gy` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工艺，是一系列工序的有机组合';

-- --------------------------------------------------------

--
-- 表的结构 `gy_detail`
--

CREATE TABLE `gy_detail` (
  `id` int(4) NOT NULL,
  `gy_id` int(4) NOT NULL COMMENT '工艺',
  `pre_gx_id` int(4) NOT NULL DEFAULT '0' COMMENT '上一工序',
  `gx_id` int(4) NOT NULL DEFAULT '0' COMMENT '工序'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='具体工艺描述，指定不同工序的先后关系';

-- --------------------------------------------------------

--
-- 表的结构 `hb`
--

CREATE TABLE `hb` (
  `id` int(11) NOT NULL,
  `hb_fl_id` int(11) NOT NULL DEFAULT '1' COMMENT '分类',
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称，',
  `gender_id` int(4) NOT NULL DEFAULT '1' COMMENT '性别：1.男；2：女；3：法人',
  `zhengjian_fl_id` int(11) NOT NULL DEFAULT '1',
  `identity_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '身份编号，如企业注册号，自然人的身份证号码',
  `bank` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '开户行',
  `bank_account_no` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '使用的银行账号',
  `tax_no` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '税号',
  `city` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '地址',
  `lxr` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系人',
  `cell_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '联系电话',
  `init_date` date NOT NULL,
  `init_account_receivable` float NOT NULL DEFAULT '0',
  `account_receivable` float NOT NULL DEFAULT '0' COMMENT '该伙伴目前和企业的应收款，正数表明该伙伴欠本企业，负数表示本企业欠该伙伴',
  `credit_level_id` int(11) NOT NULL DEFAULT '0' COMMENT '信用度，包括账期和额度',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `isactive` int(4) NOT NULL DEFAULT '1' COMMENT '是否活跃有效伙伴，1：有效，2：无效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴，包括所有和企业发生联系的自然人或法人或政府部门，员工可以认为是中间产品的供应商';

--
-- 转存表中的数据 `hb`
--

INSERT INTO `hb` (`id`, `hb_fl_id`, `name`, `gender_id`, `zhengjian_fl_id`, `identity_no`, `bank`, `bank_account_no`, `tax_no`, `city`, `address`, `lxr`, `cell_no`, `init_date`, `init_account_receivable`, `account_receivable`, `credit_level_id`, `note`, `isactive`) VALUES
(1, 1, '叶霖', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(2, 1, '蒋剑佩', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(3, 1, '造型工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -110, 1, '', 1),
(4, 1, '蜡型清理工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -17, 1, '', 1),
(5, 1, '蜡型焊接工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -7.5, 1, '', 1),
(6, 1, '制壳工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -2.5, 1, '', 1),
(7, 1, '失蜡工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(8, 1, '焙烧工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(9, 1, '浇注炉头', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(10, 1, '浇铸工', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(11, 1, '后处理工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(12, 1, '打磨工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(13, 1, '电焊工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(14, 1, '分拣工1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(15, 3, '运输人1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -60400, 1, '', 1),
(16, 3, '石蜡供应商1', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(17, 3, '原料供应商', 1, 1, '', '', '', '', '', '', '', '', '0000-00-00', 0, -271431, 1, '', 1),
(18, 2, '申达', 3, 2, '1111', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(19, 2, '申宏', 3, 2, '222', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1),
(20, 2, '无锡', 3, 2, '333', '', '', '', '', '', '', '', '0000-00-00', 0, 0, 1, '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hb_account_receivable`
--

CREATE TABLE `hb_account_receivable` (
  `id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL,
  `happen_date` date NOT NULL,
  `account_receivable` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cause` text COLLATE utf8_unicode_ci NOT NULL,
  `yw_id` int(11) NOT NULL DEFAULT '0' COMMENT '引发变化的业务'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='应收款历史记录';

-- --------------------------------------------------------

--
-- 表的结构 `hb_contact_method`
--

CREATE TABLE `hb_contact_method` (
  `id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL,
  `contact_method_id` int(11) NOT NULL,
  `content` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='联系方式';

--
-- 转存表中的数据 `hb_contact_method`
--

INSERT INTO `hb_contact_method` (`id`, `hb_id`, `contact_method_id`, `content`) VALUES
(5, 1, 5, '111'),
(6, 1, 4, 'yelin@gmail.com');

-- --------------------------------------------------------

--
-- 表的结构 `hb_fl`
--

CREATE TABLE `hb_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴分类，包括员工，供应商，客户，工商，税务，水电部门等';

--
-- 转存表中的数据 `hb_fl`
--

INSERT INTO `hb_fl` (`id`, `name`) VALUES
(1, '员工'),
(2, '客户'),
(3, '供应商'),
(4, '金融相关者');

-- --------------------------------------------------------

--
-- 表的结构 `hb_hobby`
--

CREATE TABLE `hb_hobby` (
  `id` int(4) NOT NULL,
  `hb_id` int(4) NOT NULL COMMENT '员工',
  `hobby_id` int(4) NOT NULL COMMENT '爱好'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴爱好';

--
-- 转存表中的数据 `hb_hobby`
--

INSERT INTO `hb_hobby` (`id`, `hb_id`, `hobby_id`) VALUES
(9, 2, 3),
(10, 2, 5),
(11, 1, 1),
(12, 1, 3),
(13, 1, 2);

-- --------------------------------------------------------

--
-- 表的结构 `hb_skill`
--

CREATE TABLE `hb_skill` (
  `id` int(4) NOT NULL,
  `hb_id` int(4) NOT NULL COMMENT '员工',
  `skill_id` int(4) NOT NULL COMMENT '技能',
  `skill_grade_id` int(4) NOT NULL COMMENT '技能水平',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴技能';

--
-- 转存表中的数据 `hb_skill`
--

INSERT INTO `hb_skill` (`id`, `hb_id`, `skill_id`, `skill_grade_id`, `note`) VALUES
(4, 2, 2, 5, ''),
(5, 1, 4, 3, '');

-- --------------------------------------------------------

--
-- 表的结构 `hb_wz`
--

CREATE TABLE `hb_wz` (
  `id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL,
  `wz_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='伙伴可以提供或需求的物资';

--
-- 转存表中的数据 `hb_wz`
--

INSERT INTO `hb_wz` (`id`, `hb_id`, `wz_id`) VALUES
(5, 17, 3),
(6, 16, 3),
(7, 17, 4),
(11, 20, 7),
(12, 19, 7),
(13, 18, 7),
(14, 15, 1),
(19, 20, 8),
(20, 19, 8),
(21, 17, 10),
(22, 17, 11),
(23, 17, 12),
(24, 17, 13),
(25, 17, 14);

-- --------------------------------------------------------

--
-- 表的结构 `hb_yf`
--

CREATE TABLE `hb_yf` (
  `id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL COMMENT '合作伙伴',
  `from_date` date NOT NULL COMMENT '起始日期',
  `to_date` date NOT NULL COMMENT '结束日期',
  `total_money` float NOT NULL COMMENT '工资总额',
  `paied_money` float NOT NULL COMMENT '已付工资总额',
  `creater_id` int(4) NOT NULL COMMENT '录入人',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='应付统计';

-- --------------------------------------------------------

--
-- 表的结构 `hb_yg`
--

CREATE TABLE `hb_yg` (
  `id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL COMMENT '合作伙伴',
  `enter_date` date DEFAULT NULL COMMENT '进厂日期',
  `baoxian_type_id` int(11) DEFAULT '0' COMMENT '保险类型',
  `baoxian_start_date` date DEFAULT NULL COMMENT '保险起付日期',
  `baoxian_feiyong` float DEFAULT '0' COMMENT '保险费用',
  `salary_fl_id` int(11) NOT NULL DEFAULT '1' COMMENT '工资类型，如固定工资，计件工资等',
  `base_salary` int(11) NOT NULL DEFAULT '800' COMMENT '基本工资',
  `ticheng_ratio` int(11) NOT NULL DEFAULT '0' COMMENT '提成比例, 千分之几',
  `work_type_id` int(11) NOT NULL DEFAULT '1' COMMENT '当前主要工种',
  `dept_id` int(11) NOT NULL COMMENT '当前所属部门',
  `position_id` int(11) NOT NULL COMMENT '职位'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='员工的信息';

--
-- 转存表中的数据 `hb_yg`
--

INSERT INTO `hb_yg` (`id`, `hb_id`, `enter_date`, `baoxian_type_id`, `baoxian_start_date`, `baoxian_feiyong`, `salary_fl_id`, `base_salary`, `ticheng_ratio`, `work_type_id`, `dept_id`, `position_id`) VALUES
(1, 1, '2011-05-07', 1, '2016-05-07', 1000, 1, 3000, 0, 8, 1, 2),
(2, 2, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 6, 1, 2),
(3, 3, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 6, 0, 0),
(4, 4, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 9, 0, 0),
(5, 5, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 7, 0, 0),
(6, 6, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 10, 0, 0),
(7, 7, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 11, 0, 0),
(8, 8, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 13, 0, 0),
(9, 9, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 12, 0, 0),
(10, 10, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 14, 0, 0),
(11, 11, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 19, 0, 0),
(12, 12, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 17, 0, 0),
(13, 13, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 16, 0, 0),
(14, 14, '2016-05-07', 0, '2016-05-07', 0, 1, 800, 0, 15, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `hjcs`
--

CREATE TABLE `hjcs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `data_type_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工作环境参数';

--
-- 转存表中的数据 `hjcs`
--

INSERT INTO `hjcs` (`id`, `name`, `data_type_id`) VALUES
(1, '钢号', 1),
(2, '锰含量', 1),
(3, '流速', 1);

-- --------------------------------------------------------

--
-- 表的结构 `hobby`
--

CREATE TABLE `hobby` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='爱好';

--
-- 转存表中的数据 `hobby`
--

INSERT INTO `hobby` (`id`, `name`, `note`) VALUES
(1, '旅游', ''),
(2, '阅读', ''),
(3, '购物', ''),
(4, '理财', ''),
(5, '运动', '');

-- --------------------------------------------------------

--
-- 表的结构 `jl`
--

CREATE TABLE `jl` (
  `id` int(4) NOT NULL,
  `jl_fl_id` int(4) NOT NULL DEFAULT '1' COMMENT '交流方式，如电话，Email，IM，出差等',
  `hzhb_id` int(4) NOT NULL COMMENT '合作伙伴',
  `jlr_id` int(4) NOT NULL COMMENT '己方交流者',
  `happened_date` date NOT NULL COMMENT '日期',
  `cause` text COLLATE utf8_unicode_ci NOT NULL COMMENT '原因',
  `result` text COLLATE utf8_unicode_ci NOT NULL COMMENT '结果',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `attachment` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '附件文件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='交流沟通';

-- --------------------------------------------------------

--
-- 表的结构 `jl_fl`
--

CREATE TABLE `jl_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `add_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '附加信息说明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='交流方式分类，包括电话，Email，IM，出差等';

-- --------------------------------------------------------

--
-- 表的结构 `jl_fy_bx`
--

CREATE TABLE `jl_fy_bx` (
  `id` int(4) NOT NULL,
  `jl_id` int(4) NOT NULL COMMENT '交流编号',
  `jl_fy_fl_id` int(4) NOT NULL COMMENT '交流费用类型',
  `amount` float NOT NULL COMMENT '费用总额',
  `add_info` text COLLATE utf8_unicode_ci NOT NULL COMMENT '附加信息',
  `happened_date` date NOT NULL COMMENT '日期',
  `ticket_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '票据编号',
  `ticket_img` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '票据扫描文件'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='交流费用报销';

-- --------------------------------------------------------

--
-- 表的结构 `jl_fy_fl`
--

CREATE TABLE `jl_fy_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `add_info` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '附加信息说明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='交流费用分类，主要是出差的费用';

-- --------------------------------------------------------

--
-- 表的结构 `jszb`
--

CREATE TABLE `jszb` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='技术指标';

--
-- 转存表中的数据 `jszb`
--

INSERT INTO `jszb` (`id`, `name`, `description`) VALUES
(1, '含碳量', ''),
(2, '含锰量', ''),
(3, '含硅量', ''),
(4, '抗拉强度', ''),
(5, '表面光滑度', ''),
(6, '钢号', '');

-- --------------------------------------------------------

--
-- 表的结构 `jszb_wz`
--

CREATE TABLE `jszb_wz` (
  `id` int(11) NOT NULL,
  `wz_id` int(11) NOT NULL COMMENT '物资',
  `jszb_id` int(11) NOT NULL COMMENT '技术指标',
  `min_value` float NOT NULL DEFAULT '0',
  `max_value` float NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='某个物资需要检测的技术指标，具体的值在批次管理里标注';

--
-- 转存表中的数据 `jszb_wz`
--

INSERT INTO `jszb_wz` (`id`, `wz_id`, `jszb_id`, `min_value`, `max_value`) VALUES
(3, 7, 6, 45, 45);

-- --------------------------------------------------------

--
-- 表的结构 `jszb_wz_pici`
--

CREATE TABLE `jszb_wz_pici` (
  `id` int(11) NOT NULL,
  `jszb_wz_id` int(11) NOT NULL COMMENT '物资的一个技术指标',
  `pici_id` int(11) NOT NULL COMMENT '某批次的物资',
  `min_value` float NOT NULL COMMENT '该技术指标在这批次物资中的最小值',
  `max_value` float NOT NULL COMMENT '该技术指标在这批次物资中的最大值'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='一个批次物资的详细技术指标值';

-- --------------------------------------------------------

--
-- 表的结构 `keyword`
--

CREATE TABLE `keyword` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='关键字';

-- --------------------------------------------------------

--
-- 表的结构 `lxr`
--

CREATE TABLE `lxr` (
  `id` int(11) NOT NULL,
  `hzhb_id` int(4) NOT NULL COMMENT '合作伙伴',
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '名称，',
  `gender` tinyint(1) NOT NULL DEFAULT '1' COMMENT '性别，1：男，2：女',
  `birth_date` date DEFAULT NULL COMMENT '生日',
  `identity_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '身份编号，如企业注册号，自然人的身份证号码',
  `mobile_no` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '手机号码',
  `tele_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '电话号码',
  `other_contact_method` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '其他联系方式，如MSN，传真等，格式为MSN:aa@aa.com,Fax:021-2333432432,qq:123455',
  `position` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '职位',
  `zgyw` text COLLATE utf8_unicode_ci COMMENT '主管业务',
  `isactive` int(4) NOT NULL DEFAULT '1' COMMENT '是否活跃有效伙伴，1：有效，2：无效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='联系人';

-- --------------------------------------------------------

--
-- 表的结构 `muju`
--

CREATE TABLE `muju` (
  `id` int(11) NOT NULL,
  `wz_id` int(11) NOT NULL COMMENT '哪个产品',
  `muju_type_id` int(11) NOT NULL COMMENT '模具类型：手工模具还是自动模具',
  `chupinlv` int(11) NOT NULL DEFAULT '1' COMMENT '每模出几个产品',
  `hemu_seconds` int(11) NOT NULL COMMENT '合模秒数',
  `lengque_seconds` int(11) NOT NULL COMMENT '冷却秒数',
  `chaimu_seconds` int(11) NOT NULL COMMENT '拆模秒数',
  `caozuo_seconds` int(11) NOT NULL COMMENT '操作秒数，比如注蜡',
  `in_used` int(11) NOT NULL COMMENT '目前可用数量',
  `muju_from_id` int(11) NOT NULL DEFAULT '0' COMMENT '模具来源，自己的或客户的'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='模具';

--
-- 转存表中的数据 `muju`
--

INSERT INTO `muju` (`id`, `wz_id`, `muju_type_id`, `chupinlv`, `hemu_seconds`, `lengque_seconds`, `chaimu_seconds`, `caozuo_seconds`, `in_used`, `muju_from_id`) VALUES
(1, 1, 0, 1, 0, 0, 0, 0, 0, 0),
(2, 2, 0, 1, 0, 0, 0, 0, 0, 0),
(3, 3, 0, 1, 0, 0, 0, 0, 0, 0),
(4, 4, 0, 1, 0, 0, 0, 0, 0, 0),
(5, 5, 0, 1, 0, 0, 0, 0, 0, 0),
(6, 6, 0, 1, 0, 0, 0, 0, 0, 0),
(7, 7, 0, 1, 0, 0, 0, 0, 0, 0),
(9, 8, 3, 2, 3, 3, 3, 3, 3, 0),
(10, 8, 2, 1, 3, 3, 3, 3, 3, 0);

-- --------------------------------------------------------

--
-- 表的结构 `muju_from`
--

CREATE TABLE `muju_from` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='模具类型';

--
-- 转存表中的数据 `muju_from`
--

INSERT INTO `muju_from` (`id`, `name`) VALUES
(4, '自有'),
(5, '客户提供');

-- --------------------------------------------------------

--
-- 表的结构 `muju_type`
--

CREATE TABLE `muju_type` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='模具类型';

--
-- 转存表中的数据 `muju_type`
--

INSERT INTO `muju_type` (`id`, `name`) VALUES
(1, '自动机械模'),
(2, '手工模具'),
(3, '半自动机器模具');

-- --------------------------------------------------------

--
-- 表的结构 `pici`
--

CREATE TABLE `pici` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '批号名称，一般为日期-姓名-物资-工序组合',
  `yw_id` int(11) NOT NULL COMMENT '由哪个入库动作生成，yw_ruku里包含了hb, gx。',
  `gx_id` int(11) NOT NULL COMMENT '工序',
  `wz_id` int(11) NOT NULL COMMENT '物资',
  `dingdan_id` int(11) NOT NULL DEFAULT '0' COMMENT '订单',
  `defect_id` int(11) NOT NULL DEFAULT '1' COMMENT '缺陷',
  `amount` float NOT NULL COMMENT '该批次的总量',
  `price` decimal(10,4) NOT NULL DEFAULT '0.0000' COMMENT '单价',
  `remained` float NOT NULL COMMENT '剩余量',
  `ck_weizhi_id` int(11) NOT NULL DEFAULT '0' COMMENT '存放位置',
  `note` text COLLATE utf8_unicode_ci,
  `happen_date` date NOT NULL COMMENT '批号实际生成日期',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间',
  `item_id` int(11) NOT NULL DEFAULT '0',
  `hb_id` int(11) NOT NULL DEFAULT '0',
  `yw_fl_id` int(11) NOT NULL,
  `need_pd` int(11) NOT NULL DEFAULT '1' COMMENT '需要盘点，1：需要盘点，2：不需要盘点'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='批次管理';

--
-- 转存表中的数据 `pici`
--

INSERT INTO `pici` (`id`, `name`, `yw_id`, `gx_id`, `wz_id`, `dingdan_id`, `defect_id`, `amount`, `price`, `remained`, `ck_weizhi_id`, `note`, `happen_date`, `created`, `item_id`, `hb_id`, `yw_fl_id`, `need_pd`) VALUES
(1, '造型工1在2016-05-09入库, 工序:01. 造型, 物资:拨档-1, 数量:1000个', 2, 2, 7, 0, 1, 1000, '0.0500', 0, 1, NULL, '2016-05-09', '2016-05-09 02:42:46', 0, 3, 3, 1),
(2, '造型工1在2016-05-09入库, 工序:01. 造型, 物资:浇口棒-1, 数量:100个', 2, 2, 6, 0, 1, 100, '0.1000', 0, 1, NULL, '2016-05-09', '2016-05-09 02:42:46', 0, 3, 3, 1),
(3, '蜡型焊接工1在2016-05-09入库, 工序:02. 蜡型清理, 物资:拨档-1, 数量:500个', 3, 3, 7, 0, 1, 500, '0.0100', 0, 0, NULL, '2016-05-09', '2016-05-09 02:44:21', 0, 5, 3, 1),
(4, '原料供应商在2016-05-09入库, 工序:0. 采购, 物资:石蜡, 数量:2吨', 5, 1, 3, 1, 1, 2, '0.0000', 1.697, 0, NULL, '2016-05-09', '2016-05-09 09:20:12', 0, 17, 0, 1),
(6, '蜡型清理工1在2016-05-10入库, 工序:02. 蜡型清理, 物资:浇口棒-1, 数量:100个', 7, 3, 6, 0, 1, 100, '0.0200', 75, 0, NULL, '2016-05-10', '2016-05-10 02:43:45', 0, 4, 3, 1),
(7, '蜡型清理工1在2016-05-10入库, 工序:02. 蜡型清理, 物资:拨档-1, 数量:500个', 7, 3, 7, 0, 1, 500, '0.0300', 0, 0, NULL, '2016-05-10', '2016-05-10 02:43:45', 0, 4, 3, 1),
(8, '造型工1在2016-05-10入库, 工序:01. 造型, 物资:拨档-1, 数量:1000个', 8, 2, 7, 0, 1, 1000, '0.0500', 1000, 1, NULL, '2016-05-10', '2016-05-10 02:53:16', 0, 3, 3, 1),
(9, '原料供应商在2016-05-13入库, 工序:0. 采购, 物资:石蜡, 数量:300吨', 17, 1, 3, 2, 1, 300, '0.0000', 300, 0, NULL, '2016-05-13', '2016-05-13 07:33:42', 0, 17, 0, 1),
(10, '蜡型焊接工1在2016-05-13入库, 工序:03. 蜡型焊接, 物资:拨档组I, 数量:25串', 18, 4, 9, 0, 1, 25, '0.1000', 0, 1, NULL, '2016-05-13', '2016-05-13 08:59:22', 0, 5, 3, 1),
(11, '制壳工1在2016-05-13入库, 工序:04. 涂料制模, 物资:拨档组I, 数量:25串', 19, 5, 9, 0, 1, 25, '0.1000', 25, 1, NULL, '2016-05-13', '2016-05-13 09:11:31', 0, 6, 3, 1);

-- --------------------------------------------------------

--
-- 表的结构 `position`
--

CREATE TABLE `position` (
  `id` int(11) NOT NULL,
  `name` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '职位名',
  `zhize` text COLLATE utf8_unicode_ci NOT NULL COMMENT '职责',
  `isactive` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='职位';

--
-- 转存表中的数据 `position`
--

INSERT INTO `position` (`id`, `name`, `zhize`, `isactive`) VALUES
(1, '基础员工', '', 1),
(2, '管理人员', '', 1);

-- --------------------------------------------------------

--
-- 表的结构 `price_model`
--

CREATE TABLE `price_model` (
  `id` int(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='价格模型，主要分为固定单价和复合单价';

--
-- 转存表中的数据 `price_model`
--

INSERT INTO `price_model` (`id`, `name`, `note`) VALUES
(1, '固定工资', ''),
(2, '计件工资', '');

-- --------------------------------------------------------

--
-- 表的结构 `ruku`
--

CREATE TABLE `ruku` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL DEFAULT '0',
  `pici_id` int(11) NOT NULL DEFAULT '0' COMMENT '批次'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='入库';

-- --------------------------------------------------------

--
-- 表的结构 `salary_fl`
--

CREATE TABLE `salary_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工资类型';

--
-- 转存表中的数据 `salary_fl`
--

INSERT INTO `salary_fl` (`id`, `name`, `note`) VALUES
(1, '固定工资', ''),
(2, '计件工资', '');

-- --------------------------------------------------------

--
-- 表的结构 `scdj`
--

CREATE TABLE `scdj` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '业务',
  `gx_id` int(11) DEFAULT '0' COMMENT '工序',
  `wz_id` int(11) DEFAULT '0' COMMENT '产品',
  `pici_id` int(11) NOT NULL COMMENT '生成的物资批次'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='生产数据的详细信息';

-- --------------------------------------------------------

--
-- 表的结构 `scdj_input`
--

CREATE TABLE `scdj_input` (
  `id` int(11) NOT NULL,
  `scdj_id` int(11) NOT NULL,
  `pici_detail_id` int(11) NOT NULL COMMENT '所使用的物资批次',
  `amount` float NOT NULL COMMENT '使用的数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='生产当中用掉的物资情况';

-- --------------------------------------------------------

--
-- 表的结构 `scdj_output`
--

CREATE TABLE `scdj_output` (
  `id` int(11) NOT NULL,
  `scdj_id` int(11) NOT NULL,
  `pici_id` int(11) NOT NULL COMMENT '生成的物资批次',
  `amount` float NOT NULL COMMENT '使用的数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='生产当中用掉的物资情况';

-- --------------------------------------------------------

--
-- 表的结构 `scjh`
--

CREATE TABLE `scjh` (
  `id` int(11) NOT NULL,
  `gx_id` int(11) NOT NULL COMMENT '工序',
  `wz_id` int(11) NOT NULL COMMENT '物资',
  `sc_date` date NOT NULL COMMENT '生产日期',
  `amount` decimal(11,2) NOT NULL COMMENT '计划数量',
  `completed` decimal(11,2) NOT NULL COMMENT '实际完成量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注',
  `isactive` int(11) NOT NULL COMMENT '是否依然有效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='生产计划';

--
-- 转存表中的数据 `scjh`
--

INSERT INTO `scjh` (`id`, `gx_id`, `wz_id`, `sc_date`, `amount`, `completed`, `note`, `isactive`) VALUES
(1, 7, 9, '2016-07-25', '500.00', '0.00', '', 0);

-- --------------------------------------------------------

--
-- 表的结构 `skill`
--

CREATE TABLE `skill` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='技能定义';

--
-- 转存表中的数据 `skill`
--

INSERT INTO `skill` (`id`, `name`, `note`) VALUES
(1, '造型', '\n'),
(2, '焊接', ''),
(3, '失蜡', ''),
(4, '采购', '谈判技巧\n');

-- --------------------------------------------------------

--
-- 表的结构 `skill_grade`
--

CREATE TABLE `skill_grade` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `xl` int(4) NOT NULL COMMENT '效率'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `skill_grade`
--

INSERT INTO `skill_grade` (`id`, `name`, `xl`) VALUES
(1, '不可救药', 50),
(2, '有待改进', 60),
(3, '普通', 80),
(4, '良好', 100),
(5, '优秀', 120);

-- --------------------------------------------------------

--
-- 表的结构 `skill_work_type`
--

CREATE TABLE `skill_work_type` (
  `id` int(11) NOT NULL,
  `work_type_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工种需要的技能';

--
-- 转存表中的数据 `skill_work_type`
--

INSERT INTO `skill_work_type` (`id`, `work_type_id`, `skill_id`) VALUES
(1, 6, 1),
(2, 7, 2),
(3, 7, 1),
(4, 8, 4),
(5, 11, 3);

-- --------------------------------------------------------

--
-- 表的结构 `tj`
--

CREATE TABLE `tj` (
  `id` int(4) NOT NULL,
  `from_date` date NOT NULL COMMENT '起始日期',
  `end_date` date NOT NULL COMMENT '截止日期（含）',
  `pdf` text COLLATE utf8_unicode_ci NOT NULL COMMENT '生成的pdf文档名称',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '更新日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='企业统计';

--
-- 转存表中的数据 `tj`
--

INSERT INTO `tj` (`id`, `from_date`, `end_date`, `pdf`, `updated`) VALUES
(1, '2016-05-11', '2016-05-11', 'C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/1/ABL.sys,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AFUtil.dll,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AddEmotion.htm', '2016-05-12 07:15:22'),
(2, '2016-05-12', '2016-05-12', 'C:/Users/b19268/xampp/kf/public/upload/qygl/tj/2/AddrSearch.dll', '2016-05-12 07:37:49'),
(3, '2016-05-12', '2016-05-12', '', '2016-05-12 07:29:03'),
(4, '2016-05-12', '2016-05-12', 'C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/1/ABL.sys', '2016-05-12 07:31:29'),
(5, '2016-05-12', '2016-05-12', 'C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/1/ABL.sys,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AFUtil.dll,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AddEmotion.htm', '2016-05-12 07:32:36'),
(6, '2016-05-12', '2016-05-12', 'C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/1/ABL.sys,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AFUtil.dll,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AddEmotion.htm', '2016-05-12 07:36:38'),
(7, '2016-05-12', '2016-05-12', 'C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/1/ABL.sys,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AFUtil.dll,C:\\Users\\b19268\\xampp\\kf\\public\\upload/qygl/tj/0/AddEmotion.htm', '2016-05-12 07:37:29'),
(8, '2016-05-01', '2016-05-20', 'C:\\Users\\b19268\\xampp\\kf\\application\\export/qygl_tj_5.xlsx', '2016-05-20 07:37:32');

-- --------------------------------------------------------

--
-- 表的结构 `tj_ht_detail`
--

CREATE TABLE `tj_ht_detail` (
  `id` int(4) NOT NULL,
  `tj_id` int(4) NOT NULL COMMENT '统计ID',
  `hzhb_id` int(4) NOT NULL COMMENT '客户',
  `wz_id` int(4) NOT NULL COMMENT '物资',
  `required` float NOT NULL COMMENT '需求量',
  `finished` float NOT NULL DEFAULT '0' COMMENT '完成量',
  `total_money` float NOT NULL COMMENT '合同金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合同统计细节';

-- --------------------------------------------------------

--
-- 表的结构 `tj_wz_detail`
--

CREATE TABLE `tj_wz_detail` (
  `id` int(4) NOT NULL,
  `tj_id` int(4) NOT NULL COMMENT '统计ID',
  `wz_fl_id` int(4) NOT NULL COMMENT '物资类型',
  `amount` float NOT NULL COMMENT '采购数量',
  `total_money` float NOT NULL COMMENT '采购金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物流统计细节';

-- --------------------------------------------------------

--
-- 表的结构 `tj_zj_detail`
--

CREATE TABLE `tj_zj_detail` (
  `id` int(4) NOT NULL,
  `tj_id` int(4) NOT NULL COMMENT '统计ID',
  `zjwl_fl_id` int(4) NOT NULL COMMENT '资金往来类型',
  `total_money` float NOT NULL COMMENT '采购金额'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金统计细节';

-- --------------------------------------------------------

--
-- 表的结构 `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `unit_fl_id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `fen_zi` int(11) NOT NULL DEFAULT '1' COMMENT 'fenzi/fenmu=unit/standard_unit',
  `fen_mu` int(11) NOT NULL DEFAULT '1' COMMENT 'fenzi/fenmu=unit/standard_unit'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `unit`
--

INSERT INTO `unit` (`id`, `unit_fl_id`, `name`, `fen_zi`, `fen_mu`) VALUES
(1, 1, '小时', 1, 1),
(2, 1, '分钟', 1, 60),
(3, 1, '天', 24, 1),
(4, 1, '秒', 1, 3600),
(5, 1, '年', 8760, 1),
(6, 2, '米', 1, 1),
(7, 2, '尺', 1, 3),
(8, 2, '寸', 1, 30),
(9, 2, '丈', 10, 3),
(10, 2, '分米', 1, 10),
(11, 2, '厘米', 1, 100),
(12, 2, '毫米', 1, 1000),
(13, 2, '千米', 1000, 1),
(14, 1, '星期', 168, 1),
(15, 6, '千克', 1000, 1),
(16, 6, '克', 1, 1),
(17, 6, '市斤', 500, 1),
(18, 6, '吨', 1000000, 1),
(19, 5, '个', 1, 1),
(20, 3, '立方厘米', 1, 1),
(21, 4, '平方厘米', 1, 1),
(22, 5, '串', 1, 1),
(23, 7, '千瓦小时', 1, 1),
(24, 8, '测试用', 1, 1),
(25, 9, 'cs', 1, 1),
(26, 5, '台', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `unit_fl`
--

CREATE TABLE `unit_fl` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `unit_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `unit_fl`
--

INSERT INTO `unit_fl` (`id`, `name`, `description`, `unit_id`) VALUES
(1, '时间', '时间单位', 1),
(2, '长度', '长度单位', 6),
(3, '体积', '体积', 20),
(4, '面积', '面积', 21),
(5, '数量', '数量单位', 19),
(6, '重量', '重量单位', 16),
(7, '电量', '', 23),
(8, '测试', '测试用的', 24),
(9, '测试2', '', 25);

-- --------------------------------------------------------

--
-- 表的结构 `work_type`
--

CREATE TABLE `work_type` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='工种';

--
-- 转存表中的数据 `work_type`
--

INSERT INTO `work_type` (`id`, `name`, `note`) VALUES
(6, '造型工', ''),
(7, '蜡型焊接工', ''),
(8, '采购员', ''),
(9, '蜡型修型工', ''),
(10, '涂料工', ''),
(11, '失蜡工', ''),
(12, '浇铸炉头', ''),
(13, '浇铸锅炉工', ''),
(14, '浇铸浇注工', ''),
(15, '后处理分拣工', ''),
(16, '后处理电焊工', ''),
(17, '后处理打磨工', ''),
(18, '清砂工', ''),
(19, '分解工', ''),
(20, '抛丸工', '');

-- --------------------------------------------------------

--
-- 表的结构 `wz`
--

CREATE TABLE `wz` (
  `id` int(4) UNSIGNED NOT NULL,
  `wz_fl_id` int(4) DEFAULT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `unit_id` int(11) DEFAULT NULL COMMENT '计量单位',
  `default_price` float DEFAULT NULL COMMENT '默认单价',
  `jy_days` int(4) NOT NULL DEFAULT '180' COMMENT '积压期限',
  `wh_days` int(11) NOT NULL DEFAULT '0' COMMENT '设备维护周期',
  `midu` float NOT NULL DEFAULT '0' COMMENT '密度',
  `min_kc` int(11) NOT NULL DEFAULT '0' COMMENT '最小库存',
  `tj` float NOT NULL DEFAULT '0' COMMENT '体积',
  `bmj` float NOT NULL DEFAULT '0' COMMENT '表面积',
  `ndxs` int(11) NOT NULL DEFAULT '85' COMMENT '产品难度系数，主要关系到成品率，最容易为100，最难为1',
  `cp` int(11) NOT NULL DEFAULT '1' COMMENT '正式产品还是辅助用产品，内部周转，比如浇口棒',
  `youxiaobili` int(11) NOT NULL DEFAULT '100' COMMENT '产品有效比例，因为有浇口等因素，实际产品的占比并不是100%',
  `zuhe` int(11) NOT NULL DEFAULT '0' COMMENT '是否组合产品',
  `isactive` int(4) NOT NULL DEFAULT '1' COMMENT '是否依然在用，1：在用；2：不再用',
  `pic` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '照片文件名',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注',
  `tuzhi` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资，包括材料，产品，维修用品，办公用品，生活用品等';

--
-- 转存表中的数据 `wz`
--

INSERT INTO `wz` (`id`, `wz_fl_id`, `name`, `unit_id`, `default_price`, `jy_days`, `wh_days`, `midu`, `min_kc`, `tj`, `bmj`, `ndxs`, `cp`, `youxiaobili`, `zuhe`, `isactive`, `pic`, `note`, `tuzhi`) VALUES
(1, 4, '运输', 18, 200, 180, 0, 0, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(2, 4, '装卸', 18, 20, 180, 0, 0, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(3, 1, '石蜡', 18, 1000, 180, 0, 3, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(4, 1, '废钢', 18, 2000, 180, 0, 7.9, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(5, 8, '电力', 23, 0.8, 180, 0, 0, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(6, 3, '浇口棒-1', 19, 0.1, 180, 0, 0, 0, 500, 400, 85, 2, 100, 1, 1, '', '', ''),
(7, 3, '拨档-1', 19, 1.2, 180, 0, 0, 0, 100, 100, 85, 1, 100, 1, 1, '', '', ''),
(8, 3, '车架', 19, 2.1, 180, 0, 0, 0, 100, 300, 85, 1, 100, 1, 1, '', '', ''),
(9, 3, '拨档组I', 22, 0, 180, 0, 0, 0, 4500, 4400, 85, 1, 100, 2, 1, '', '', ''),
(10, 1, '1#石英砂', 18, 1000, 180, 0, 1, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(11, 1, '2#石英砂', 18, 1000, 180, 0, 1, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(12, 1, '3#石英砂', 18, 1000, 180, 0, 1, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(13, 1, '4#石英砂', 18, 1000, 180, 0, 1, 0, 0, 0, 85, 1, 100, 1, 1, '', '', ''),
(14, 1, '5#石英砂', 18, 1000, 180, 0, 1, 0, 0, 0, 85, 1, 100, 1, 1, '', '', '');

-- --------------------------------------------------------

--
-- 表的结构 `wz_cp_zuhe`
--

CREATE TABLE `wz_cp_zuhe` (
  `id` int(11) NOT NULL,
  `wz_id` int(11) NOT NULL COMMENT '产品',
  `input_wz_id` int(11) NOT NULL COMMENT '零件',
  `amount` float NOT NULL DEFAULT '1' COMMENT '零件数量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='组合产品描述';

--
-- 转存表中的数据 `wz_cp_zuhe`
--

INSERT INTO `wz_cp_zuhe` (`id`, `wz_id`, `input_wz_id`, `amount`) VALUES
(3, 9, 6, 1),
(4, 9, 7, 40);

-- --------------------------------------------------------

--
-- 表的结构 `wz_detail`
--

CREATE TABLE `wz_detail` (
  `id` int(11) NOT NULL,
  `wz_id` int(11) NOT NULL COMMENT '物资',
  `defect_id` int(11) NOT NULL COMMENT '缺陷情况',
  `remained` float NOT NULL COMMENT '剩余量'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资的详细情况，主要是各种质量的统计信息';

-- --------------------------------------------------------

--
-- 表的结构 `wz_fl`
--

CREATE TABLE `wz_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='物资分类，包括材料，产品，维修用品，办公用品，生活用品等';

--
-- 转存表中的数据 `wz_fl`
--

INSERT INTO `wz_fl` (`id`, `name`) VALUES
(1, '原材料'),
(2, '设备'),
(3, '产品'),
(4, '服务'),
(5, '劳保用品'),
(6, '办公用品'),
(7, '维修用品'),
(8, '能源'),
(9, '其他');

-- --------------------------------------------------------

--
-- 表的结构 `wz_sb`
--

CREATE TABLE `wz_sb` (
  `id` int(4) UNSIGNED NOT NULL,
  `wz_id` int(4) NOT NULL,
  `fix_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '设备编号，对原材料无效',
  `wh_date` date DEFAULT NULL COMMENT '设备最后一次维护日期',
  `wh_days` int(2) NOT NULL DEFAULT '100' COMMENT '设备维护周期，单位为天',
  `min_handle` int(11) NOT NULL DEFAULT '0' COMMENT '最小处理量',
  `max_handle` int(11) NOT NULL DEFAULT '0' COMMENT '最大处理量',
  `isactive` int(11) NOT NULL DEFAULT '1' COMMENT '目前是否依然可用'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='设备';

-- --------------------------------------------------------

--
-- 表的结构 `wz_sb_wh`
--

CREATE TABLE `wz_sb_wh` (
  `id` int(4) NOT NULL,
  `wz_sb_id` int(4) NOT NULL COMMENT '设备',
  `happened_date` date NOT NULL COMMENT '维修日期',
  `hb_id` int(11) NOT NULL COMMENT '维修人员',
  `cause` text COLLATE utf8_unicode_ci NOT NULL COMMENT '维修原因',
  `result` text COLLATE utf8_unicode_ci NOT NULL COMMENT '维修结果'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='设备维护记录';

-- --------------------------------------------------------

--
-- 表的结构 `wz_sb_wh_wz`
--

CREATE TABLE `wz_sb_wh_wz` (
  `id` int(4) NOT NULL,
  `wz_sb_wh_id` int(4) NOT NULL COMMENT '维护记录',
  `wz_id` int(4) NOT NULL COMMENT '消耗的物资',
  `amount` float NOT NULL DEFAULT '1' COMMENT '消耗的数量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='设备维护消耗的物资';

-- --------------------------------------------------------

--
-- 表的结构 `yg_skill`
--

CREATE TABLE `yg_skill` (
  `id` int(4) NOT NULL,
  `yg_id` int(4) NOT NULL COMMENT '员工',
  `skill_id` int(4) NOT NULL COMMENT '技能',
  `skill_grade_id` int(4) NOT NULL COMMENT '技能水平',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴技能';

--
-- 转存表中的数据 `yg_skill`
--

INSERT INTO `yg_skill` (`id`, `yg_id`, `skill_id`, `skill_grade_id`, `note`) VALUES
(5, 6, 3, 1, ''),
(6, 6, 2, 5, ''),
(7, 3, 2, 5, ''),
(8, 3, 4, 5, ''),
(14, 10, 3, 3, ''),
(15, 10, 2, 5, '');

-- --------------------------------------------------------

--
-- 表的结构 `yw`
--

CREATE TABLE `yw` (
  `id` int(4) NOT NULL,
  `name` varchar(250) COLLATE utf8_unicode_ci NOT NULL COMMENT '业务名称，概略描述业务内容',
  `yw_fl_id` int(4) DEFAULT NULL COMMENT '业务往来分类',
  `gx_id` int(11) DEFAULT '0',
  `hb_id` int(11) NOT NULL DEFAULT '0' COMMENT '合作伙伴',
  `happen_date` date NOT NULL COMMENT '业务发生日期',
  `jbr_id` int(11) NOT NULL COMMENT '经办人',
  `dj` text COLLATE utf8_unicode_ci COMMENT '单据',
  `dj_id` int(4) DEFAULT NULL COMMENT '单据记录号',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注',
  `isactive` int(11) NOT NULL DEFAULT '1',
  `creater_id` int(4) NOT NULL COMMENT '录入人',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='合作伙伴业务往来';

--
-- 转存表中的数据 `yw`
--

INSERT INTO `yw` (`id`, `name`, `yw_fl_id`, `gx_id`, `hb_id`, `happen_date`, `jbr_id`, `dj`, `dj_id`, `note`, `isactive`, `creater_id`, `created`) VALUES
(1, '2016-05-07从公司账户向备用金划拨资金20000元', 15, 0, 0, '2016-05-07', 1, NULL, 0, '', 1, 2, '2016-05-07 09:39:14'),
(2, '造型工1在2016-05-09sc生产', 3, 2, 3, '2016-05-09', 1, NULL, 0, '', 1, 2, '2016-05-09 02:42:46'),
(3, '蜡型焊接工1在2016-05-09sc生产', 3, 3, 5, '2016-05-09', 1, NULL, 0, '', 1, 2, '2016-05-09 02:44:21'),
(4, '叶霖在2016-05-09向原料供应商下采购订单', 1, 0, 17, '2016-05-09', 1, NULL, 0, '', 1, 2, '2016-05-09 09:19:38'),
(5, '收到运输人12016-05-09运送的货物', 2, 0, 15, '2016-05-09', 1, NULL, 0, '', 1, 2, '2016-05-09 09:20:11'),
(7, '蜡型清理工1在2016-05-10sc生产', 3, 3, 4, '2016-05-10', 1, NULL, 0, '', 1, 2, '2016-05-10 02:43:45'),
(8, '造型工1在2016-05-10sc生产', 3, 2, 3, '2016-05-10', 1, NULL, 0, '', 1, 2, '2016-05-10 02:53:16'),
(9, '叶霖在2016-05-12向原料供应商下采购订单', 1, 0, 17, '2016-05-12', 1, NULL, 0, '', 1, 2, '2016-05-12 09:32:44'),
(10, '叶霖在2016-05-13接到无锡采购订单', 5, 0, 20, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 03:24:26'),
(13, '叶霖在2016-05-13接到申宏采购订单', 5, 0, 19, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 05:44:23'),
(15, '叶霖在2016-05-13接到无锡采购订单', 5, 0, 20, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 06:10:37'),
(16, '叶霖在2016-05-13向原料供应商下采购订单', 1, 0, 17, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 07:26:59'),
(17, '收到运输人12016-05-13运送的货物', 2, 0, 15, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 07:33:42'),
(18, '蜡型焊接工1在2016-05-13sc生产', 3, 4, 5, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 08:59:21'),
(19, '制壳工1在2016-05-13sc生产', 3, 5, 6, '2016-05-13', 1, NULL, 0, '', 1, 2, '2016-05-13 09:11:31'),
(20, '2016-06-02接到原料供应商的发票, 总金额10000元', 17, 0, 17, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 03:00:21'),
(21, '2016-06-02接到石蜡供应商1的发票, 总金额5000元', 17, 0, 16, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 03:18:38'),
(22, '2016-06-02接到石蜡供应商1的发票, 总金额123元', 17, 0, 16, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 03:20:01'),
(23, '2016-06-02接到石蜡供应商1的发票, 总金额300元', 17, 0, 16, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 03:24:06'),
(24, '2016-06-02因支付采购款支付给原料供应商10023元', 12, 0, 17, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 09:00:24'),
(25, '2016-06-02因支付采购款支付给原料供应商10023元', 12, 0, 17, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 09:02:06'),
(26, '2016-06-02因支付采购款支付给原料供应商10023元', 12, 0, 17, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 09:18:42'),
(28, '2016-06-02因支付采购款支付给原料供应商500元', 12, 0, 17, '2016-06-02', 1, NULL, 0, '', 1, 2, '2016-06-02 09:26:12'),
(29, '叶霖在2016-06-06向原料供应商下采购订单', 1, 0, 17, '2016-06-06', 1, NULL, 0, '', 1, 2, '2016-06-06 07:49:53');

-- --------------------------------------------------------

--
-- 表的结构 `yw_fh_detail`
--

CREATE TABLE `yw_fh_detail` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个客户',
  `dingdan_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个订单',
  `pici_id` int(11) NOT NULL,
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='发货清单';

-- --------------------------------------------------------

--
-- 表的结构 `yw_fl`
--

CREATE TABLE `yw_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL COMMENT '描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='业务分类，如采购，发货，登记产出情况等';

--
-- 转存表中的数据 `yw_fl`
--

INSERT INTO `yw_fl` (`id`, `name`, `description`) VALUES
(1, 'xd下单', '从供应商处采购物资，如原材料，设备，办公用品等'),
(2, 'sh收货', '将物资从外部运输到企业'),
(3, 'sc生产', '输入生产信息，比如谁在哪道工序生产了多少某个产品，质量如何等'),
(4, 'zjbd资金变动', '资金变动，包括支付，收款，贴息等'),
(5, 'jd接单', '接收客户订单'),
(6, 'fh发货', '发货'),
(7, 'pdck盘点仓库', '盘点仓库物资'),
(8, 'pdzj盘点资金', '盘点资金账户'),
(9, 'wzyk物资移库', '物资移库'),
(10, 'th退货', '将采购的物资退回给供应商'),
(11, 'jth接退货', '接收客户退回的物资'),
(12, 'zj支付', ''),
(13, 'hk回款', ''),
(14, 'pjtx票据贴息', '票据贴息或拆分'),
(15, '账户间划拨', '从一个现金账户划拨资金到另一个现金账户'),
(16, '开出发票', ''),
(17, '接到发票', '');

-- --------------------------------------------------------

--
-- 表的结构 `yw_jth_detail`
--

CREATE TABLE `yw_jth_detail` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个客户',
  `dingdan_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个订单',
  `defect_id` int(11) NOT NULL COMMENT '主要缺陷',
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='接收退货清单';

-- --------------------------------------------------------

--
-- 表的结构 `yw_kp`
--

CREATE TABLE `yw_kp` (
  `id` int(4) NOT NULL,
  `yw_id` int(4) NOT NULL DEFAULT '0' COMMENT '业务',
  `from_date` date NOT NULL COMMENT '起始日期',
  `to_date` date NOT NULL COMMENT '结束日期',
  `amount` float NOT NULL DEFAULT '0' COMMENT '金额',
  `code` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '发票编号',
  `cyr_id` int(4) NOT NULL DEFAULT '0' COMMENT '承运人',
  `yunfei` float NOT NULL DEFAULT '0' COMMENT '运费'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='开出发票';

-- --------------------------------------------------------

--
-- 表的结构 `yw_ruku`
--

CREATE TABLE `yw_ruku` (
  `id` int(4) NOT NULL,
  `hb_id` int(11) DEFAULT NULL COMMENT '合作伙伴',
  `happen_date` date NOT NULL COMMENT '业务发生日期',
  `jbr_id` int(11) NOT NULL COMMENT '经办人',
  `cyr_id` int(11) NOT NULL DEFAULT '0' COMMENT '承运人',
  `yunshu_price` float NOT NULL DEFAULT '0' COMMENT '运输单价',
  `zxr_id` int(11) NOT NULL DEFAULT '0' COMMENT '装卸人',
  `zx_price` float DEFAULT '0' COMMENT '装卸单价',
  `weight` float DEFAULT '0' COMMENT '运输装卸总量',
  `dj_id` int(4) NOT NULL COMMENT '单据记录号',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注',
  `isactive` int(11) NOT NULL DEFAULT '1',
  `creater_id` int(4) NOT NULL COMMENT '录入人',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='入库';

-- --------------------------------------------------------

--
-- 表的结构 `yw_scdj`
--

CREATE TABLE `yw_scdj` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '业务',
  `gx_id` int(11) DEFAULT '0' COMMENT '工序',
  `wz_id` int(11) DEFAULT '0' COMMENT '产品'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='生产数据的详细信息';

-- --------------------------------------------------------

--
-- 表的结构 `yw_sh_detail`
--

CREATE TABLE `yw_sh_detail` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个客户',
  `dingdan_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个订单',
  `defect_id` int(11) NOT NULL DEFAULT '1' COMMENT '质量情况',
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='收到订购的货物清单';

--
-- 转存表中的数据 `yw_sh_detail`
--

INSERT INTO `yw_sh_detail` (`id`, `yw_id`, `hb_id`, `dingdan_id`, `defect_id`, `amount`, `note`) VALUES
(1, 5, 17, 1, 1, 2, ''),
(2, 17, 17, 2, 1, 300, '');

-- --------------------------------------------------------

--
-- 表的结构 `yw_th_detail`
--

CREATE TABLE `yw_th_detail` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL,
  `hb_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个客户',
  `dingdan_id` int(11) NOT NULL DEFAULT '0' COMMENT '哪个订单',
  `pici_id` int(11) NOT NULL,
  `amount` float NOT NULL DEFAULT '0' COMMENT '数量',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='退货清单';

-- --------------------------------------------------------

--
-- 表的结构 `yw_yunshu`
--

CREATE TABLE `yw_yunshu` (
  `id` int(4) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '业务',
  `yunshu_price` float NOT NULL DEFAULT '0' COMMENT '运输单价',
  `zxr_id` int(11) NOT NULL DEFAULT '0' COMMENT '装卸人',
  `zx_price` float DEFAULT '0' COMMENT '装卸单价',
  `weight` float NOT NULL DEFAULT '0' COMMENT '运输装卸总量',
  `kg_id` int(11) NOT NULL DEFAULT '0' COMMENT '出入库库管',
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '运输地址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='运输装卸及出入库';

--
-- 转存表中的数据 `yw_yunshu`
--

INSERT INTO `yw_yunshu` (`id`, `yw_id`, `yunshu_price`, `zxr_id`, `zx_price`, `weight`, `kg_id`, `address`) VALUES
(1, 5, 200, 0, 20, 2, 0, ''),
(2, 17, 200, 0, 20, 300, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj`
--

CREATE TABLE `yw_zj` (
  `id` int(4) NOT NULL,
  `hb_id` int(11) DEFAULT NULL COMMENT '合作伙伴',
  `happen_date` date NOT NULL COMMENT '业务发生日期',
  `jbr_id` int(11) NOT NULL COMMENT '经办人',
  `dj_id` int(4) NOT NULL COMMENT '单据记录号',
  `note` text COLLATE utf8_unicode_ci COMMENT '备注',
  `isactive` int(11) NOT NULL DEFAULT '1',
  `creater_id` int(4) NOT NULL COMMENT '录入人',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '录入时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金进出';

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj_hk`
--

CREATE TABLE `yw_zj_hk` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '资金业务',
  `zj_cause_id` int(11) NOT NULL COMMENT '资金变动原因',
  `zj_fl_id` int(11) NOT NULL DEFAULT '1' COMMENT '资金还是票据',
  `zjzh_id` int(11) DEFAULT '0' COMMENT '资金账户',
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='回款';

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj_huabo`
--

CREATE TABLE `yw_zj_huabo` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '资金业务',
  `out_zjzh_id` int(11) DEFAULT '0' COMMENT '转出资金账户',
  `in_zjzh_id` int(11) NOT NULL COMMENT '转入资金账户',
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金划拨';

--
-- 转存表中的数据 `yw_zj_huabo`
--

INSERT INTO `yw_zj_huabo` (`id`, `yw_id`, `out_zjzh_id`, `in_zjzh_id`, `amount`, `cost`) VALUES
(1, 1, 1, 2, 20000, 0);

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj_jinchu`
--

CREATE TABLE `yw_zj_jinchu` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '资金业务',
  `zj_cause_id` int(11) NOT NULL COMMENT '资金变动原因',
  `zj_fl_id` int(11) NOT NULL DEFAULT '1' COMMENT '资金还是票据',
  `zjzh_id` int(11) DEFAULT '0' COMMENT '资金账户',
  `zj_pj_id` int(11) NOT NULL,
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金变动业务';

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj_pj_tiexi`
--

CREATE TABLE `yw_zj_pj_tiexi` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '资金业务',
  `zjzh_id` int(11) DEFAULT '0' COMMENT '资金账户',
  `zj_pj_id` int(11) NOT NULL,
  `cash_zjzh_id` int(11) NOT NULL DEFAULT '0' COMMENT '现金资金账户',
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='票据贴息及拆分';

-- --------------------------------------------------------

--
-- 表的结构 `yw_zj_zhifu`
--

CREATE TABLE `yw_zj_zhifu` (
  `id` int(11) NOT NULL,
  `yw_id` int(11) NOT NULL COMMENT '资金业务',
  `zj_cause_id` int(11) NOT NULL COMMENT '资金变动原因',
  `zj_fl_id` int(11) NOT NULL DEFAULT '1' COMMENT '资金还是票据',
  `zjzh_id` int(11) DEFAULT '0' COMMENT '资金账户',
  `zj_pj_id` int(11) NOT NULL,
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='支付';

--
-- 转存表中的数据 `yw_zj_zhifu`
--

INSERT INTO `yw_zj_zhifu` (`id`, `yw_id`, `zj_cause_id`, `zj_fl_id`, `zjzh_id`, `zj_pj_id`, `amount`, `cost`) VALUES
(1, 24, 2, 1, 1, 0, 10023, 0),
(2, 25, 2, 1, 1, 0, 10023, 0),
(3, 26, 2, 1, 1, 0, 10023, 0),
(5, 28, 2, 1, 1, 0, 500, 0);

-- --------------------------------------------------------

--
-- 表的结构 `zhengjian_fl`
--

CREATE TABLE `zhengjian_fl` (
  `id` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `isactive` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `zhengjian_fl`
--

INSERT INTO `zhengjian_fl` (`id`, `name`, `isactive`) VALUES
(1, '身份证', 1),
(2, '营业执照', 1);

-- --------------------------------------------------------

--
-- 表的结构 `zjzh`
--

CREATE TABLE `zjzh` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `account_no` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '账号，可以为空',
  `bizhong_id` int(11) NOT NULL DEFAULT '1' COMMENT '币种，默认为人民币',
  `pd_date` date NOT NULL COMMENT '盘点日期',
  `init_amount` float NOT NULL COMMENT '初始总额',
  `remained` float NOT NULL DEFAULT '0' COMMENT '剩余资金',
  `zj_fl_id` int(4) NOT NULL DEFAULT '1' COMMENT '资金形式',
  `owner_id` int(11) NOT NULL COMMENT '账户管理人',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金账户，包括银行账户、备用金账户等';

--
-- 转存表中的数据 `zjzh`
--

INSERT INTO `zjzh` (`id`, `name`, `account_no`, `bizhong_id`, `pd_date`, `init_amount`, `remained`, `zj_fl_id`, `owner_id`, `created`) VALUES
(1, '公司账户', '', 1, '0000-00-00', 0, 149431, 1, 2, '2016-05-07 09:37:16'),
(2, '备用金', '', 1, '0000-00-00', 0, 20000, 1, 2, '2016-05-07 09:37:58'),
(3, '票据账户', '', 1, '0000-00-00', 0, 0, 3, 2, '2016-05-07 09:38:18');

-- --------------------------------------------------------

--
-- 表的结构 `zjzh_history`
--

CREATE TABLE `zjzh_history` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zjzh_id` int(11) NOT NULL,
  `happen_date` date NOT NULL,
  `remained` float NOT NULL DEFAULT '0' COMMENT '剩余资金',
  `yw_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金账户历史变化情况';

-- --------------------------------------------------------

--
-- 表的结构 `zjzh_pd`
--

CREATE TABLE `zjzh_pd` (
  `id` int(11) NOT NULL,
  `zjzh_id` int(11) NOT NULL COMMENT '物资',
  `happen_date` date NOT NULL COMMENT '盘点日期',
  `amount` float NOT NULL COMMENT '实际值',
  `expected_amount` float NOT NULL COMMENT '期望值',
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '备注，实际值和期望值不符的原因和可能',
  `jbr_id` int(11) NOT NULL COMMENT '盘点人'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='账户盘点';

-- --------------------------------------------------------

--
-- 表的结构 `zj_cause`
--

CREATE TABLE `zj_cause` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `zj_direct_id` int(11) NOT NULL DEFAULT '1' COMMENT '1：支付，2：收款，3：内部转账'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金变动原因，包括工资，货款，利息，采购款，运输款，其他等';

--
-- 转存表中的数据 `zj_cause`
--

INSERT INTO `zj_cause` (`id`, `name`, `zj_direct_id`) VALUES
(1, '支付工资', 1),
(2, '支付采购款', 1),
(3, '回收货款', 2),
(4, '支付利息', 1),
(6, '归还借款', 1),
(7, '回收借款', 2),
(8, '借入款项', 2),
(9, '报销差旅费，通讯费等', 1),
(10, '支付税收', 1),
(11, '支付电费', 1),
(12, '利息收入', 2),
(13, '还贷款', 1),
(14, '支付工程款', 1),
(15, '支付公关费用', 1);

-- --------------------------------------------------------

--
-- 表的结构 `zj_direct`
--

CREATE TABLE `zj_direct` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金流动方向';

--
-- 转存表中的数据 `zj_direct`
--

INSERT INTO `zj_direct` (`id`, `name`) VALUES
(1, '支付'),
(2, '回收'),
(3, '内部转账');

-- --------------------------------------------------------

--
-- 表的结构 `zj_fl`
--

CREATE TABLE `zj_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金分类，包括现金，汇票，银行承兑，现金支票等';

--
-- 转存表中的数据 `zj_fl`
--

INSERT INTO `zj_fl` (`id`, `name`) VALUES
(1, '现金'),
(2, '现金支票'),
(3, '银行承兑');

-- --------------------------------------------------------

--
-- 表的结构 `zj_jinchu`
--

CREATE TABLE `zj_jinchu` (
  `id` int(11) NOT NULL,
  `yw_zj_id` int(11) NOT NULL COMMENT '资金业务',
  `zj_cause_id` int(11) NOT NULL COMMENT '资金变动原因',
  `in_zjzh_id` int(11) DEFAULT '0' COMMENT '转入资金账户',
  `out_zjzh_id` int(11) DEFAULT '0' COMMENT '转出资金账户',
  `zj_pj_id` int(11) DEFAULT '0' COMMENT '票据',
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金变动业务';

-- --------------------------------------------------------

--
-- 表的结构 `zj_package`
--

CREATE TABLE `zj_package` (
  `id` int(4) NOT NULL,
  `zj_cause_id` int(4) NOT NULL COMMENT '资金变动原因',
  `zjzh_id` int(11) NOT NULL DEFAULT '0' COMMENT '资金账户',
  `zj_pj_id` int(11) DEFAULT '0' COMMENT '票据',
  `amount` float DEFAULT '0' COMMENT '总金额',
  `cost` float DEFAULT '0' COMMENT '资金变动费用，如转账费用等'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金变动业务';

-- --------------------------------------------------------

--
-- 表的结构 `zj_pj`
--

CREATE TABLE `zj_pj` (
  `id` int(4) UNSIGNED NOT NULL,
  `zj_fl_id` int(11) NOT NULL COMMENT '资金类型，不能是现金',
  `zj_pj_fl_id` int(4) NOT NULL DEFAULT '1' COMMENT '票据类型，和资金类型相同',
  `code` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '票据编号',
  `total_money` float NOT NULL DEFAULT '0' COMMENT '总金额',
  `expire_date` date DEFAULT NULL COMMENT '到期日',
  `from_yw_id` int(4) DEFAULT NULL COMMENT '前置业务，指产生该票据的业务',
  `to_yw_id` int(4) DEFAULT '0' COMMENT '后置业务，指后续处理该票据的业务',
  `dj_id` int(4) DEFAULT '0',
  `note` text COLLATE utf8_unicode_ci
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `zj_pj_fl`
--

CREATE TABLE `zj_pj_fl` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='资金分类，包括现金，汇票，银行承兑，现金支票等';

--
-- 转存表中的数据 `zj_pj_fl`
--

INSERT INTO `zj_pj_fl` (`id`, `name`) VALUES
(1, '现金支票'),
(2, '银行承兑'),
(3, '银行汇票');

-- --------------------------------------------------------

--
-- 表的结构 `zl`
--

CREATE TABLE `zl` (
  `id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL COMMENT '说明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='质量';

--
-- 转存表中的数据 `zl`
--

INSERT INTO `zl` (`id`, `name`, `note`) VALUES
(1, '一等品', '正品'),
(2, '二等品', '次品\n'),
(3, '三等品', '废品');

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_ck_pd`
--
CREATE TABLE `zzvw_ck_pd` (
`id` int(11)
,`pici_id` int(11)
,`happen_date` date
,`expected_amount` float
,`amount` float
,`note` text
,`jbr_id` int(11)
,`gx_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_cyr`
--
CREATE TABLE `zzvw_cyr` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_defect_gx`
--
CREATE TABLE `zzvw_defect_gx` (
`id` int(11)
,`name` varchar(30)
,`gx_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_dingdan`
--
CREATE TABLE `zzvw_dingdan` (
`id` int(11)
,`yw_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
,`price` float
,`amount` float
,`completed_amount` float
,`dingdan_status_id` int(11)
,`hb_id` int(11)
,`yw_fl_id` int(4)
,`wz_name` varchar(50)
,`unit_name` varchar(50)
,`defect` varchar(30)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_dingdan_cg`
--
CREATE TABLE `zzvw_dingdan_cg` (
`id` int(11)
,`yw_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
,`price` float
,`amount` float
,`completed_amount` float
,`dingdan_status_id` int(11)
,`hb_id` int(11)
,`yw_fl_id` int(4)
,`wz_name` varchar(50)
,`unit_name` varchar(50)
,`defect` varchar(30)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_dingdan_executing`
--
CREATE TABLE `zzvw_dingdan_executing` (
`id` int(11)
,`yw_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
,`price` float
,`amount` float
,`completed_amount` float
,`dingdan_status_id` int(11)
,`hb_id` int(11)
,`yw_fl_id` int(4)
,`wz_name` varchar(50)
,`unit_name` varchar(50)
,`defect` varchar(30)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_dingdan_xs`
--
CREATE TABLE `zzvw_dingdan_xs` (
`id` int(11)
,`yw_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
,`price` float
,`amount` float
,`completed_amount` float
,`dingdan_status_id` int(11)
,`hb_id` int(11)
,`yw_fl_id` int(4)
,`wz_name` varchar(50)
,`unit_name` varchar(50)
,`defect` varchar(30)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_gx_cg_sc`
--
CREATE TABLE `zzvw_gx_cg_sc` (
`id` int(4)
,`gx_fl_id` int(4)
,`name` varchar(20)
,`replaced_wz_id` int(11)
,`wz_id` int(11)
,`has_shell` int(11)
,`need_mj` int(11)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_gx_sc`
--
CREATE TABLE `zzvw_gx_sc` (
`id` int(4)
,`gx_fl_id` int(4)
,`name` varchar(20)
,`replaced_wz_id` int(11)
,`wz_id` int(11)
,`has_shell` int(11)
,`need_mj` int(11)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_gys`
--
CREATE TABLE `zzvw_gys` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank` varchar(100)
,`bank_account_no` varchar(100)
,`tax_no` varchar(50)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_gys_kh`
--
CREATE TABLE `zzvw_gys_kh` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_kh`
--
CREATE TABLE `zzvw_kh` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank` varchar(100)
,`bank_account_no` varchar(100)
,`tax_no` varchar(50)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_pici`
--
CREATE TABLE `zzvw_pici` (
`id` int(11)
,`name` varchar(255)
,`yw_id` int(11)
,`gx_id` int(11)
,`wz_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`price` decimal(10,4)
,`remained` float
,`ck_weizhi_id` int(11)
,`note` text
,`happen_date` date
,`created` timestamp
,`item_id` int(11)
,`hb_id` int(11)
,`yw_fl_id` int(11)
,`need_pd` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_pici_fh`
--
CREATE TABLE `zzvw_pici_fh` (
`id` int(11)
,`name` varchar(255)
,`yw_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`remained` float
,`note` text
,`happen_date` date
,`ck_weizhi_id` int(11)
,`created` timestamp
,`hb_id` int(11)
,`wz_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_pici_scdj`
--
CREATE TABLE `zzvw_pici_scdj` (
`id` int(11)
,`name` varchar(255)
,`yw_id` int(11)
,`gx_id` int(11)
,`wz_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`price` decimal(10,4)
,`remained` float
,`ck_weizhi_id` int(11)
,`note` text
,`happen_date` date
,`created` timestamp
,`hb_id` int(11)
,`yw_fl_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_pici_sh`
--
CREATE TABLE `zzvw_pici_sh` (
`id` int(11)
,`name` varchar(255)
,`yw_id` int(11)
,`dingdan_id` int(11)
,`wz_id` int(11)
,`defect_id` int(11)
,`amount` float
,`remained` float
,`note` text
,`happen_date` date
,`ck_weizhi_id` int(11)
,`created` timestamp
,`hb_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_ruku`
--
CREATE TABLE `zzvw_ruku` (
`id` int(11)
,`yw_id` int(11)
,`pici_id` int(11)
,`name` varchar(255)
,`gx_id` int(11)
,`wz_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`price` decimal(10,4)
,`remained` float
,`ck_weizhi_id` int(11)
,`note` text
,`happen_date` date
,`created` timestamp
,`item_id` int(11)
,`hb_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_stgys`
--
CREATE TABLE `zzvw_stgys` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_unit`
--
CREATE TABLE `zzvw_unit` (
`id` int(11)
,`unit_fl_id` int(11)
,`name` varchar(50)
,`fen_zi` int(11)
,`fen_mu` int(11)
,`standard_unit_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz`
--
CREATE TABLE `zzvw_wz` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
,`cp` int(11)
,`unit_name` varchar(50)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz_cp`
--
CREATE TABLE `zzvw_wz_cp` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
,`cp` int(11)
,`unit_name` varchar(50)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz_fzhcp`
--
CREATE TABLE `zzvw_wz_fzhcp` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
,`cp` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz_yl`
--
CREATE TABLE `zzvw_wz_yl` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
,`unit_name` varchar(50)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz_zccp`
--
CREATE TABLE `zzvw_wz_zccp` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
,`cp` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_wz_zhcp`
--
CREATE TABLE `zzvw_wz_zhcp` (
`id` int(4) unsigned
,`wz_fl_id` int(4)
,`name` varchar(50)
,`unit_id` int(11)
,`default_price` float
,`jy_days` int(4)
,`wh_days` int(11)
,`midu` float
,`tj` float
,`bmj` float
,`zuhe` int(11)
,`isactive` int(4)
,`pic` varchar(100)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yg`
--
CREATE TABLE `zzvw_yg` (
`id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank` varchar(100)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`enter_date` date
,`baoxian_type_id` int(11)
,`baoxian_start_date` date
,`baoxian_feiyong` float
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`isactive` int(4)
,`hb_fl_id` int(11)
,`work_type_id` int(11)
,`base_salary` int(11)
,`ticheng_ratio` int(11)
,`salary_fl_id` int(11)
,`dept_id` int(11)
,`position_id` int(11)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yg_manager`
--
CREATE TABLE `zzvw_yg_manager` (
`id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`enter_date` date
,`baoxian_type_id` int(11)
,`baoxian_start_date` date
,`baoxian_feiyong` float
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`isactive` int(4)
,`hb_fl_id` int(11)
,`work_type_id` int(11)
,`base_salary` int(11)
,`ticheng_ratio` int(11)
,`salary_fl_id` int(11)
,`dept_id` int(11)
,`position_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_fh_detail`
--
CREATE TABLE `zzvw_yw_fh_detail` (
`id` int(11)
,`yw_id` int(11)
,`hb_id` int(11)
,`dingdan_id` int(11)
,`pici_id` int(11)
,`amount` float
,`note` text
,`wz_id` int(11)
,`dingdan_amount` float
,`completed_amount` float
,`dingdan_remained` double
,`pici_remained` float
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_jd`
--
CREATE TABLE `zzvw_yw_jd` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_jfp`
--
CREATE TABLE `zzvw_yw_jfp` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`gx_id` int(11)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`fp_fl_id` int(11)
,`in_or_out` int(11)
,`from_date` date
,`to_date` date
,`code` varchar(20)
,`cyr_id` int(4)
,`yunfei` float
,`amount` decimal(11,2)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_jth_detail`
--
CREATE TABLE `zzvw_yw_jth_detail` (
`id` int(11)
,`yw_id` int(11)
,`hb_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`note` text
,`wz_id` int(11)
,`dingdan_amount` float
,`completed_amount` float
,`dingdan_remained` double
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_kp`
--
CREATE TABLE `zzvw_yw_kp` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`gx_id` int(11)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`fp_fl_id` int(11)
,`in_or_out` int(11)
,`from_date` date
,`to_date` date
,`code` varchar(20)
,`cyr_id` int(4)
,`yunfei` float
,`amount` decimal(11,2)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_scdj`
--
CREATE TABLE `zzvw_yw_scdj` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`gx_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_sh_detail`
--
CREATE TABLE `zzvw_yw_sh_detail` (
`id` int(11)
,`yw_id` int(11)
,`hb_id` int(11)
,`dingdan_id` int(11)
,`defect_id` int(11)
,`amount` float
,`note` text
,`wz_id` int(11)
,`dingdan_amount` float
,`completed_amount` float
,`dingdan_remained` double
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_th_detail`
--
CREATE TABLE `zzvw_yw_th_detail` (
`id` int(11)
,`yw_id` int(11)
,`hb_id` int(11)
,`dingdan_id` int(11)
,`pici_id` int(11)
,`amount` float
,`note` text
,`wz_id` int(11)
,`dingdan_amount` float
,`completed_amount` float
,`dingdan_remained` double
,`pici_remained` float
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_xd`
--
CREATE TABLE `zzvw_yw_xd` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_yunshu`
--
CREATE TABLE `zzvw_yw_yunshu` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`yunshu_price` float
,`zxr_id` int(11)
,`zx_price` float
,`weight` float
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_zj_hk`
--
CREATE TABLE `zzvw_yw_zj_hk` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`zj_cause_id` int(11)
,`zj_fl_id` int(11)
,`zjzh_id` int(11)
,`amount` float
,`cost` float
,`zj_pj_fl_id` int(4)
,`code` varchar(50)
,`total_money` float
,`expire_date` date
,`from_yw_id` int(4)
,`to_yw_id` int(4)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_zj_huabo`
--
CREATE TABLE `zzvw_yw_zj_huabo` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`out_zjzh_id` int(11)
,`in_zjzh_id` int(11)
,`amount` float
,`cost` float
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_zj_jinchu`
--
CREATE TABLE `zzvw_yw_zj_jinchu` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_zj_pj_tiexi`
--
CREATE TABLE `zzvw_yw_zj_pj_tiexi` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`zjzh_id` int(11)
,`zj_pj_id` int(11)
,`amount` float
,`cost` float
,`cash_zjzh_id` int(11)
,`total_money` float
,`code` varchar(50)
,`expire_date` date
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_yw_zj_zhifu`
--
CREATE TABLE `zzvw_yw_zj_zhifu` (
`id` int(4)
,`name` varchar(250)
,`yw_fl_id` int(4)
,`hb_id` int(11)
,`happen_date` date
,`jbr_id` int(11)
,`dj` text
,`note` text
,`isactive` int(11)
,`creater_id` int(4)
,`created` timestamp
,`zj_cause_id` int(11)
,`zj_fl_id` int(11)
,`zjzh_id` int(11)
,`zj_pj_id` int(11)
,`amount` float
,`cost` float
,`zj_pj_fl_id` int(4)
,`code` varchar(50)
,`total_money` float
,`expire_date` date
,`from_yw_id` int(4)
,`to_yw_id` int(4)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zjzh_cash`
--
CREATE TABLE `zzvw_zjzh_cash` (
`id` int(4)
,`name` varchar(20)
,`account_no` varchar(20)
,`bizhong_id` int(11)
,`pd_date` date
,`init_amount` float
,`remained` float
,`zj_fl_id` int(4)
,`owner_id` int(11)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zjzh_pj`
--
CREATE TABLE `zzvw_zjzh_pj` (
`id` int(4)
,`name` varchar(20)
,`account_no` varchar(20)
,`bizhong_id` int(11)
,`pd_date` date
,`init_amount` float
,`remained` float
,`zj_fl_id` int(4)
,`owner_id` int(11)
,`created` timestamp
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zj_cause_hk`
--
CREATE TABLE `zzvw_zj_cause_hk` (
`id` int(4)
,`name` varchar(20)
,`zj_direct_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zj_cause_zhifu`
--
CREATE TABLE `zzvw_zj_cause_zhifu` (
`id` int(4)
,`name` varchar(20)
,`zj_direct_id` int(11)
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zj_pj_notused`
--
CREATE TABLE `zzvw_zj_pj_notused` (
`id` int(4) unsigned
,`zj_pj_fl_id` int(4)
,`code` varchar(50)
,`total_money` float
,`expire_date` date
,`from_yw_id` int(4)
,`to_yw_id` int(4)
,`dj_id` int(4)
,`note` text
);

-- --------------------------------------------------------

--
-- 替换视图以便查看 `zzvw_zxr`
--
CREATE TABLE `zzvw_zxr` (
`id` int(11)
,`hb_fl_id` int(11)
,`name` varchar(50)
,`gender_id` int(4)
,`zhengjian_fl_id` int(11)
,`identity_no` varchar(20)
,`bank_account_no` varchar(100)
,`address` varchar(255)
,`lxr` varchar(20)
,`cell_no` varchar(20)
,`init_date` date
,`init_account_receivable` float
,`account_receivable` float
,`credit_level_id` int(11)
,`isactive` int(4)
);

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_ck_pd`
--
DROP TABLE IF EXISTS `zzvw_ck_pd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_ck_pd`  AS  select `ck_pd`.`id` AS `id`,`ck_pd`.`pici_id` AS `pici_id`,`ck_pd`.`happen_date` AS `happen_date`,`ck_pd`.`expected_amount` AS `expected_amount`,`ck_pd`.`amount` AS `amount`,`ck_pd`.`note` AS `note`,`ck_pd`.`jbr_id` AS `jbr_id`,`pici`.`gx_id` AS `gx_id`,`pici`.`wz_id` AS `wz_id`,`pici`.`defect_id` AS `defect_id` from (`ck_pd` left join `pici` on((`ck_pd`.`pici_id` = `pici`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_cyr`
--
DROP TABLE IF EXISTS `zzvw_cyr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_cyr`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive` from (`hb` left join `hb_wz` on((`hb`.`id` = `hb_wz`.`hb_id`))) where (`hb_wz`.`wz_id` = 1) group by `hb`.`id` ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_defect_gx`
--
DROP TABLE IF EXISTS `zzvw_defect_gx`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_defect_gx`  AS  select `defect`.`id` AS `id`,`defect`.`name` AS `name`,`defect_gx`.`gx_id` AS `gx_id` from (`defect_gx` left join `defect` on((`defect_gx`.`defect_id` = `defect`.`id`))) where 1 ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_dingdan`
--
DROP TABLE IF EXISTS `zzvw_dingdan`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_dingdan`  AS  select `dingdan`.`id` AS `id`,`dingdan`.`yw_id` AS `yw_id`,`dingdan`.`wz_id` AS `wz_id`,`dingdan`.`defect_id` AS `defect_id`,`dingdan`.`price` AS `price`,`dingdan`.`amount` AS `amount`,`dingdan`.`completed_amount` AS `completed_amount`,`dingdan`.`dingdan_status_id` AS `dingdan_status_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`yw_fl_id` AS `yw_fl_id`,`wz`.`name` AS `wz_name`,`unit`.`name` AS `unit_name`,`defect`.`name` AS `defect` from ((((`dingdan` left join `yw` on((`dingdan`.`yw_id` = `yw`.`id`))) left join `wz` on((`dingdan`.`wz_id` = `wz`.`id`))) left join `unit` on((`wz`.`unit_id` = `unit`.`id`))) left join `defect` on((`dingdan`.`defect_id` = `defect`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_dingdan_cg`
--
DROP TABLE IF EXISTS `zzvw_dingdan_cg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_dingdan_cg`  AS  select `zzvw_dingdan`.`id` AS `id`,`zzvw_dingdan`.`yw_id` AS `yw_id`,`zzvw_dingdan`.`wz_id` AS `wz_id`,`zzvw_dingdan`.`defect_id` AS `defect_id`,`zzvw_dingdan`.`price` AS `price`,`zzvw_dingdan`.`amount` AS `amount`,`zzvw_dingdan`.`completed_amount` AS `completed_amount`,`zzvw_dingdan`.`dingdan_status_id` AS `dingdan_status_id`,`zzvw_dingdan`.`hb_id` AS `hb_id`,`zzvw_dingdan`.`yw_fl_id` AS `yw_fl_id`,`zzvw_dingdan`.`wz_name` AS `wz_name`,`zzvw_dingdan`.`unit_name` AS `unit_name`,`zzvw_dingdan`.`defect` AS `defect` from `zzvw_dingdan` where (`zzvw_dingdan`.`yw_fl_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_dingdan_executing`
--
DROP TABLE IF EXISTS `zzvw_dingdan_executing`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_dingdan_executing`  AS  select `zzvw_dingdan`.`id` AS `id`,`zzvw_dingdan`.`yw_id` AS `yw_id`,`zzvw_dingdan`.`wz_id` AS `wz_id`,`zzvw_dingdan`.`defect_id` AS `defect_id`,`zzvw_dingdan`.`price` AS `price`,`zzvw_dingdan`.`amount` AS `amount`,`zzvw_dingdan`.`completed_amount` AS `completed_amount`,`zzvw_dingdan`.`dingdan_status_id` AS `dingdan_status_id`,`zzvw_dingdan`.`hb_id` AS `hb_id`,`zzvw_dingdan`.`yw_fl_id` AS `yw_fl_id`,`zzvw_dingdan`.`wz_name` AS `wz_name`,`zzvw_dingdan`.`unit_name` AS `unit_name`,`zzvw_dingdan`.`defect` AS `defect` from `zzvw_dingdan` where (`zzvw_dingdan`.`completed_amount` > 0) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_dingdan_xs`
--
DROP TABLE IF EXISTS `zzvw_dingdan_xs`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_dingdan_xs`  AS  select `zzvw_dingdan`.`id` AS `id`,`zzvw_dingdan`.`yw_id` AS `yw_id`,`zzvw_dingdan`.`wz_id` AS `wz_id`,`zzvw_dingdan`.`defect_id` AS `defect_id`,`zzvw_dingdan`.`price` AS `price`,`zzvw_dingdan`.`amount` AS `amount`,`zzvw_dingdan`.`completed_amount` AS `completed_amount`,`zzvw_dingdan`.`dingdan_status_id` AS `dingdan_status_id`,`zzvw_dingdan`.`hb_id` AS `hb_id`,`zzvw_dingdan`.`yw_fl_id` AS `yw_fl_id`,`zzvw_dingdan`.`wz_name` AS `wz_name`,`zzvw_dingdan`.`unit_name` AS `unit_name`,`zzvw_dingdan`.`defect` AS `defect` from `zzvw_dingdan` where (`zzvw_dingdan`.`yw_fl_id` = 5) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_gx_cg_sc`
--
DROP TABLE IF EXISTS `zzvw_gx_cg_sc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_gx_cg_sc`  AS  select `gx`.`id` AS `id`,`gx`.`gx_fl_id` AS `gx_fl_id`,`gx`.`name` AS `name`,`gx`.`replaced_wz_id` AS `replaced_wz_id`,`gx`.`wz_id` AS `wz_id`,`gx`.`has_shell` AS `has_shell`,`gx`.`need_mj` AS `need_mj`,`gx`.`note` AS `note` from `gx` where ((`gx`.`id` = 1) or (`gx`.`gx_fl_id` in (1,2,3,4,5))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_gx_sc`
--
DROP TABLE IF EXISTS `zzvw_gx_sc`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_gx_sc`  AS  select `gx`.`id` AS `id`,`gx`.`gx_fl_id` AS `gx_fl_id`,`gx`.`name` AS `name`,`gx`.`replaced_wz_id` AS `replaced_wz_id`,`gx`.`wz_id` AS `wz_id`,`gx`.`has_shell` AS `has_shell`,`gx`.`need_mj` AS `need_mj`,`gx`.`note` AS `note` from `gx` where (`gx`.`gx_fl_id` in (1,2,3,4,5)) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_gys`
--
DROP TABLE IF EXISTS `zzvw_gys`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_gys`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank` AS `bank`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`tax_no` AS `tax_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive`,`hb`.`note` AS `note` from `hb` where (`hb`.`hb_fl_id` = 3) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_gys_kh`
--
DROP TABLE IF EXISTS `zzvw_gys_kh`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_gys_kh`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive` from `hb` where ((`hb`.`hb_fl_id` = 2) or (`hb`.`hb_fl_id` = 3)) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_kh`
--
DROP TABLE IF EXISTS `zzvw_kh`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_kh`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank` AS `bank`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`tax_no` AS `tax_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive`,`hb`.`note` AS `note` from `hb` where (`hb`.`hb_fl_id` = 2) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_pici`
--
DROP TABLE IF EXISTS `zzvw_pici`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_pici`  AS  select `pici`.`id` AS `id`,`pici`.`name` AS `name`,`pici`.`yw_id` AS `yw_id`,`pici`.`gx_id` AS `gx_id`,`pici`.`wz_id` AS `wz_id`,`pici`.`dingdan_id` AS `dingdan_id`,`pici`.`defect_id` AS `defect_id`,`pici`.`amount` AS `amount`,`pici`.`price` AS `price`,`pici`.`remained` AS `remained`,`pici`.`ck_weizhi_id` AS `ck_weizhi_id`,`pici`.`note` AS `note`,`pici`.`happen_date` AS `happen_date`,`pici`.`created` AS `created`,`pici`.`item_id` AS `item_id`,`pici`.`hb_id` AS `hb_id`,`pici`.`yw_fl_id` AS `yw_fl_id`,`pici`.`need_pd` AS `need_pd` from `pici` ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_pici_fh`
--
DROP TABLE IF EXISTS `zzvw_pici_fh`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_pici_fh`  AS  select `pici`.`id` AS `id`,`pici`.`name` AS `name`,`pici`.`yw_id` AS `yw_id`,`pici`.`dingdan_id` AS `dingdan_id`,`pici`.`defect_id` AS `defect_id`,`pici`.`amount` AS `amount`,`pici`.`remained` AS `remained`,`pici`.`note` AS `note`,`pici`.`happen_date` AS `happen_date`,`pici`.`ck_weizhi_id` AS `ck_weizhi_id`,`pici`.`created` AS `created`,`yw`.`hb_id` AS `hb_id`,`pici`.`wz_id` AS `wz_id` from (`pici` left join `yw` on((`pici`.`yw_id` = `yw`.`id`))) where (`yw`.`yw_fl_id` = 5) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_pici_scdj`
--
DROP TABLE IF EXISTS `zzvw_pici_scdj`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_pici_scdj`  AS  select `zzvw_pici`.`id` AS `id`,`zzvw_pici`.`name` AS `name`,`zzvw_pici`.`yw_id` AS `yw_id`,`zzvw_pici`.`gx_id` AS `gx_id`,`zzvw_pici`.`wz_id` AS `wz_id`,`zzvw_pici`.`dingdan_id` AS `dingdan_id`,`zzvw_pici`.`defect_id` AS `defect_id`,`zzvw_pici`.`amount` AS `amount`,`zzvw_pici`.`price` AS `price`,`zzvw_pici`.`remained` AS `remained`,`zzvw_pici`.`ck_weizhi_id` AS `ck_weizhi_id`,`zzvw_pici`.`note` AS `note`,`zzvw_pici`.`happen_date` AS `happen_date`,`zzvw_pici`.`created` AS `created`,`zzvw_pici`.`hb_id` AS `hb_id`,`zzvw_pici`.`yw_fl_id` AS `yw_fl_id` from `zzvw_pici` where (`zzvw_pici`.`yw_fl_id` = 3) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_pici_sh`
--
DROP TABLE IF EXISTS `zzvw_pici_sh`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_pici_sh`  AS  select `pici`.`id` AS `id`,`pici`.`name` AS `name`,`pici`.`yw_id` AS `yw_id`,`pici`.`dingdan_id` AS `dingdan_id`,`pici`.`wz_id` AS `wz_id`,`pici`.`defect_id` AS `defect_id`,`pici`.`amount` AS `amount`,`pici`.`remained` AS `remained`,`pici`.`note` AS `note`,`pici`.`happen_date` AS `happen_date`,`pici`.`ck_weizhi_id` AS `ck_weizhi_id`,`pici`.`created` AS `created`,`yw`.`hb_id` AS `hb_id` from (`pici` left join `yw` on((`pici`.`yw_id` = `yw`.`id`))) where (`yw`.`yw_fl_id` = 2) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_ruku`
--
DROP TABLE IF EXISTS `zzvw_ruku`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_ruku`  AS  select `ruku`.`id` AS `id`,`ruku`.`yw_id` AS `yw_id`,`ruku`.`pici_id` AS `pici_id`,`pici`.`name` AS `name`,`pici`.`gx_id` AS `gx_id`,`pici`.`wz_id` AS `wz_id`,`pici`.`dingdan_id` AS `dingdan_id`,`pici`.`defect_id` AS `defect_id`,`pici`.`amount` AS `amount`,`pici`.`price` AS `price`,`pici`.`remained` AS `remained`,`pici`.`ck_weizhi_id` AS `ck_weizhi_id`,`pici`.`note` AS `note`,`pici`.`happen_date` AS `happen_date`,`pici`.`created` AS `created`,`pici`.`item_id` AS `item_id`,`pici`.`hb_id` AS `hb_id` from (`ruku` left join `pici` on((`ruku`.`pici_id` = `pici`.`id`))) where 1 ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_stgys`
--
DROP TABLE IF EXISTS `zzvw_stgys`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_stgys`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive` from (`hb` left join `hb_wz` on((`hb`.`id` = `hb_wz`.`hb_id`))) where ((`hb`.`hb_fl_id` = 3) and (`hb_wz`.`wz_id` not in (1,2))) group by `hb`.`id` ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_unit`
--
DROP TABLE IF EXISTS `zzvw_unit`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_unit`  AS  select `unit`.`id` AS `id`,`unit`.`unit_fl_id` AS `unit_fl_id`,`unit`.`name` AS `name`,`unit`.`fen_zi` AS `fen_zi`,`unit`.`fen_mu` AS `fen_mu`,`unit_fl`.`unit_id` AS `standard_unit_id` from (`unit` left join `unit_fl` on((`unit`.`unit_fl_id` = `unit_fl`.`id`))) where 1 ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz`
--
DROP TABLE IF EXISTS `zzvw_wz`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz`  AS  select `wz`.`id` AS `id`,`wz`.`wz_fl_id` AS `wz_fl_id`,`wz`.`name` AS `name`,`wz`.`unit_id` AS `unit_id`,`wz`.`default_price` AS `default_price`,`wz`.`jy_days` AS `jy_days`,`wz`.`wh_days` AS `wh_days`,`wz`.`midu` AS `midu`,`wz`.`tj` AS `tj`,`wz`.`bmj` AS `bmj`,`wz`.`zuhe` AS `zuhe`,`wz`.`isactive` AS `isactive`,`wz`.`pic` AS `pic`,`wz`.`note` AS `note`,`wz`.`cp` AS `cp`,`unit`.`name` AS `unit_name` from (`wz` left join `unit` on((`wz`.`unit_id` = `unit`.`id`))) where 1 ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz_cp`
--
DROP TABLE IF EXISTS `zzvw_wz_cp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz_cp`  AS  select `zzvw_wz`.`id` AS `id`,`zzvw_wz`.`wz_fl_id` AS `wz_fl_id`,`zzvw_wz`.`name` AS `name`,`zzvw_wz`.`unit_id` AS `unit_id`,`zzvw_wz`.`default_price` AS `default_price`,`zzvw_wz`.`jy_days` AS `jy_days`,`zzvw_wz`.`wh_days` AS `wh_days`,`zzvw_wz`.`midu` AS `midu`,`zzvw_wz`.`tj` AS `tj`,`zzvw_wz`.`bmj` AS `bmj`,`zzvw_wz`.`zuhe` AS `zuhe`,`zzvw_wz`.`isactive` AS `isactive`,`zzvw_wz`.`pic` AS `pic`,`zzvw_wz`.`note` AS `note`,`zzvw_wz`.`cp` AS `cp`,`zzvw_wz`.`unit_name` AS `unit_name` from `zzvw_wz` where (`zzvw_wz`.`wz_fl_id` = 3) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz_fzhcp`
--
DROP TABLE IF EXISTS `zzvw_wz_fzhcp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz_fzhcp`  AS  select `wz`.`id` AS `id`,`wz`.`wz_fl_id` AS `wz_fl_id`,`wz`.`name` AS `name`,`wz`.`unit_id` AS `unit_id`,`wz`.`default_price` AS `default_price`,`wz`.`jy_days` AS `jy_days`,`wz`.`wh_days` AS `wh_days`,`wz`.`midu` AS `midu`,`wz`.`tj` AS `tj`,`wz`.`bmj` AS `bmj`,`wz`.`zuhe` AS `zuhe`,`wz`.`isactive` AS `isactive`,`wz`.`pic` AS `pic`,`wz`.`note` AS `note`,`wz`.`cp` AS `cp` from `wz` where ((`wz`.`wz_fl_id` = 3) and (`wz`.`zuhe` = 1)) order by `wz`.`id` ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz_yl`
--
DROP TABLE IF EXISTS `zzvw_wz_yl`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz_yl`  AS  select `zzvw_wz`.`id` AS `id`,`zzvw_wz`.`wz_fl_id` AS `wz_fl_id`,`zzvw_wz`.`name` AS `name`,`zzvw_wz`.`unit_id` AS `unit_id`,`zzvw_wz`.`default_price` AS `default_price`,`zzvw_wz`.`jy_days` AS `jy_days`,`zzvw_wz`.`wh_days` AS `wh_days`,`zzvw_wz`.`midu` AS `midu`,`zzvw_wz`.`tj` AS `tj`,`zzvw_wz`.`bmj` AS `bmj`,`zzvw_wz`.`zuhe` AS `zuhe`,`zzvw_wz`.`isactive` AS `isactive`,`zzvw_wz`.`pic` AS `pic`,`zzvw_wz`.`note` AS `note`,`zzvw_wz`.`unit_name` AS `unit_name` from `zzvw_wz` where (`zzvw_wz`.`wz_fl_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz_zccp`
--
DROP TABLE IF EXISTS `zzvw_wz_zccp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz_zccp`  AS  select `zzvw_wz_fzhcp`.`id` AS `id`,`zzvw_wz_fzhcp`.`wz_fl_id` AS `wz_fl_id`,`zzvw_wz_fzhcp`.`name` AS `name`,`zzvw_wz_fzhcp`.`unit_id` AS `unit_id`,`zzvw_wz_fzhcp`.`default_price` AS `default_price`,`zzvw_wz_fzhcp`.`jy_days` AS `jy_days`,`zzvw_wz_fzhcp`.`wh_days` AS `wh_days`,`zzvw_wz_fzhcp`.`midu` AS `midu`,`zzvw_wz_fzhcp`.`tj` AS `tj`,`zzvw_wz_fzhcp`.`bmj` AS `bmj`,`zzvw_wz_fzhcp`.`zuhe` AS `zuhe`,`zzvw_wz_fzhcp`.`isactive` AS `isactive`,`zzvw_wz_fzhcp`.`pic` AS `pic`,`zzvw_wz_fzhcp`.`note` AS `note`,`zzvw_wz_fzhcp`.`cp` AS `cp` from `zzvw_wz_fzhcp` where (`zzvw_wz_fzhcp`.`cp` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_wz_zhcp`
--
DROP TABLE IF EXISTS `zzvw_wz_zhcp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_wz_zhcp`  AS  select `wz`.`id` AS `id`,`wz`.`wz_fl_id` AS `wz_fl_id`,`wz`.`name` AS `name`,`wz`.`unit_id` AS `unit_id`,`wz`.`default_price` AS `default_price`,`wz`.`jy_days` AS `jy_days`,`wz`.`wh_days` AS `wh_days`,`wz`.`midu` AS `midu`,`wz`.`tj` AS `tj`,`wz`.`bmj` AS `bmj`,`wz`.`zuhe` AS `zuhe`,`wz`.`isactive` AS `isactive`,`wz`.`pic` AS `pic`,`wz`.`note` AS `note` from `wz` where ((`wz`.`wz_fl_id` = 3) and (`wz`.`zuhe` = 2)) order by `wz`.`id` ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yg`
--
DROP TABLE IF EXISTS `zzvw_yg`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yg`  AS  select `hb`.`id` AS `id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank` AS `bank`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`address` AS `address`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb_yg`.`enter_date` AS `enter_date`,`hb_yg`.`baoxian_type_id` AS `baoxian_type_id`,`hb_yg`.`baoxian_start_date` AS `baoxian_start_date`,`hb_yg`.`baoxian_feiyong` AS `baoxian_feiyong`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`isactive` AS `isactive`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb_yg`.`work_type_id` AS `work_type_id`,`hb_yg`.`base_salary` AS `base_salary`,`hb_yg`.`ticheng_ratio` AS `ticheng_ratio`,`hb_yg`.`salary_fl_id` AS `salary_fl_id`,`hb_yg`.`dept_id` AS `dept_id`,`hb_yg`.`position_id` AS `position_id`,`hb`.`note` AS `note` from (`hb` left join `hb_yg` on((`hb`.`id` = `hb_yg`.`hb_id`))) where (`hb`.`hb_fl_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yg_manager`
--
DROP TABLE IF EXISTS `zzvw_yg_manager`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yg_manager`  AS  select `zzvw_yg`.`id` AS `id`,`zzvw_yg`.`name` AS `name`,`zzvw_yg`.`gender_id` AS `gender_id`,`zzvw_yg`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`zzvw_yg`.`identity_no` AS `identity_no`,`zzvw_yg`.`bank_account_no` AS `bank_account_no`,`zzvw_yg`.`address` AS `address`,`zzvw_yg`.`init_date` AS `init_date`,`zzvw_yg`.`init_account_receivable` AS `init_account_receivable`,`zzvw_yg`.`account_receivable` AS `account_receivable`,`zzvw_yg`.`credit_level_id` AS `credit_level_id`,`zzvw_yg`.`enter_date` AS `enter_date`,`zzvw_yg`.`baoxian_type_id` AS `baoxian_type_id`,`zzvw_yg`.`baoxian_start_date` AS `baoxian_start_date`,`zzvw_yg`.`baoxian_feiyong` AS `baoxian_feiyong`,`zzvw_yg`.`lxr` AS `lxr`,`zzvw_yg`.`cell_no` AS `cell_no`,`zzvw_yg`.`isactive` AS `isactive`,`zzvw_yg`.`hb_fl_id` AS `hb_fl_id`,`zzvw_yg`.`work_type_id` AS `work_type_id`,`zzvw_yg`.`base_salary` AS `base_salary`,`zzvw_yg`.`ticheng_ratio` AS `ticheng_ratio`,`zzvw_yg`.`salary_fl_id` AS `salary_fl_id`,`zzvw_yg`.`dept_id` AS `dept_id`,`zzvw_yg`.`position_id` AS `position_id` from `zzvw_yg` where (`zzvw_yg`.`position_id` = 2) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_fh_detail`
--
DROP TABLE IF EXISTS `zzvw_yw_fh_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_fh_detail`  AS  select `yw_fh_detail`.`id` AS `id`,`yw_fh_detail`.`yw_id` AS `yw_id`,`yw_fh_detail`.`hb_id` AS `hb_id`,`yw_fh_detail`.`dingdan_id` AS `dingdan_id`,`yw_fh_detail`.`pici_id` AS `pici_id`,`yw_fh_detail`.`amount` AS `amount`,`yw_fh_detail`.`note` AS `note`,`dingdan`.`wz_id` AS `wz_id`,`dingdan`.`amount` AS `dingdan_amount`,`dingdan`.`completed_amount` AS `completed_amount`,(`dingdan`.`amount` - `dingdan`.`completed_amount`) AS `dingdan_remained`,`pici`.`remained` AS `pici_remained` from ((`yw_fh_detail` left join `dingdan` on((`yw_fh_detail`.`dingdan_id` = `dingdan`.`id`))) left join `pici` on((`yw_fh_detail`.`pici_id` = `pici`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_jd`
--
DROP TABLE IF EXISTS `zzvw_yw_jd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_jd`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created` from `yw` where (`yw`.`yw_fl_id` = 5) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_jfp`
--
DROP TABLE IF EXISTS `zzvw_yw_jfp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_jfp`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`gx_id` AS `gx_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`fp`.`fp_fl_id` AS `fp_fl_id`,`fp`.`in_or_out` AS `in_or_out`,`fp`.`from_date` AS `from_date`,`fp`.`to_date` AS `to_date`,`fp`.`code` AS `code`,`fp`.`cyr_id` AS `cyr_id`,`fp`.`yunfei` AS `yunfei`,`fp`.`amount` AS `amount` from (`yw` left join `fp` on((`yw`.`id` = `fp`.`yw_id`))) where (`yw`.`yw_fl_id` = 17) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_jth_detail`
--
DROP TABLE IF EXISTS `zzvw_yw_jth_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_jth_detail`  AS  select `yw_jth_detail`.`id` AS `id`,`yw_jth_detail`.`yw_id` AS `yw_id`,`yw_jth_detail`.`hb_id` AS `hb_id`,`yw_jth_detail`.`dingdan_id` AS `dingdan_id`,`yw_jth_detail`.`defect_id` AS `defect_id`,`yw_jth_detail`.`amount` AS `amount`,`yw_jth_detail`.`note` AS `note`,`dingdan`.`wz_id` AS `wz_id`,`dingdan`.`amount` AS `dingdan_amount`,`dingdan`.`completed_amount` AS `completed_amount`,(`dingdan`.`amount` - `dingdan`.`completed_amount`) AS `dingdan_remained` from (`yw_jth_detail` left join `dingdan` on((`yw_jth_detail`.`dingdan_id` = `dingdan`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_kp`
--
DROP TABLE IF EXISTS `zzvw_yw_kp`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_kp`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`gx_id` AS `gx_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`fp`.`fp_fl_id` AS `fp_fl_id`,`fp`.`in_or_out` AS `in_or_out`,`fp`.`from_date` AS `from_date`,`fp`.`to_date` AS `to_date`,`fp`.`code` AS `code`,`fp`.`cyr_id` AS `cyr_id`,`fp`.`yunfei` AS `yunfei`,`fp`.`amount` AS `amount` from (`yw` left join `fp` on((`yw`.`id` = `fp`.`yw_id`))) where (`yw`.`yw_fl_id` = 16) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_scdj`
--
DROP TABLE IF EXISTS `zzvw_yw_scdj`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_scdj`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`gx_id` AS `gx_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created` from `yw` where (`yw`.`yw_fl_id` = 3) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_sh_detail`
--
DROP TABLE IF EXISTS `zzvw_yw_sh_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_sh_detail`  AS  select `yw_sh_detail`.`id` AS `id`,`yw_sh_detail`.`yw_id` AS `yw_id`,`yw_sh_detail`.`hb_id` AS `hb_id`,`yw_sh_detail`.`dingdan_id` AS `dingdan_id`,`yw_sh_detail`.`defect_id` AS `defect_id`,`yw_sh_detail`.`amount` AS `amount`,`yw_sh_detail`.`note` AS `note`,`dingdan`.`wz_id` AS `wz_id`,`dingdan`.`amount` AS `dingdan_amount`,`dingdan`.`completed_amount` AS `completed_amount`,(`dingdan`.`amount` - `dingdan`.`completed_amount`) AS `dingdan_remained` from (`yw_sh_detail` left join `dingdan` on((`yw_sh_detail`.`dingdan_id` = `dingdan`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_th_detail`
--
DROP TABLE IF EXISTS `zzvw_yw_th_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_th_detail`  AS  select `yw_th_detail`.`id` AS `id`,`yw_th_detail`.`yw_id` AS `yw_id`,`yw_th_detail`.`hb_id` AS `hb_id`,`yw_th_detail`.`dingdan_id` AS `dingdan_id`,`yw_th_detail`.`pici_id` AS `pici_id`,`yw_th_detail`.`amount` AS `amount`,`yw_th_detail`.`note` AS `note`,`dingdan`.`wz_id` AS `wz_id`,`dingdan`.`amount` AS `dingdan_amount`,`dingdan`.`completed_amount` AS `completed_amount`,(`dingdan`.`amount` - `dingdan`.`completed_amount`) AS `dingdan_remained`,`pici`.`remained` AS `pici_remained` from ((`yw_th_detail` left join `dingdan` on((`yw_th_detail`.`dingdan_id` = `dingdan`.`id`))) left join `pici` on((`yw_th_detail`.`pici_id` = `pici`.`id`))) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_xd`
--
DROP TABLE IF EXISTS `zzvw_yw_xd`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_xd`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created` from `yw` where (`yw`.`yw_fl_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_yunshu`
--
DROP TABLE IF EXISTS `zzvw_yw_yunshu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_yunshu`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`yw_yunshu`.`yunshu_price` AS `yunshu_price`,`yw_yunshu`.`zxr_id` AS `zxr_id`,`yw_yunshu`.`zx_price` AS `zx_price`,`yw_yunshu`.`weight` AS `weight` from (`yw` left join `yw_yunshu` on((`yw`.`id` = `yw_yunshu`.`yw_id`))) where (`yw`.`yw_fl_id` in (2,6,10,11)) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_zj_hk`
--
DROP TABLE IF EXISTS `zzvw_yw_zj_hk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_zj_hk`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`zf`.`zj_cause_id` AS `zj_cause_id`,`zf`.`zj_fl_id` AS `zj_fl_id`,`zf`.`zjzh_id` AS `zjzh_id`,`zf`.`amount` AS `amount`,`zf`.`cost` AS `cost`,`pj`.`zj_pj_fl_id` AS `zj_pj_fl_id`,`pj`.`code` AS `code`,`pj`.`total_money` AS `total_money`,`pj`.`expire_date` AS `expire_date`,`pj`.`from_yw_id` AS `from_yw_id`,`pj`.`to_yw_id` AS `to_yw_id` from ((`yw` left join `yw_zj_hk` `zf` on((`yw`.`id` = `zf`.`yw_id`))) left join `zj_pj` `pj` on((`yw`.`id` = `pj`.`from_yw_id`))) where (`yw`.`yw_fl_id` = 13) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_zj_huabo`
--
DROP TABLE IF EXISTS `zzvw_yw_zj_huabo`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_zj_huabo`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`huabo`.`out_zjzh_id` AS `out_zjzh_id`,`huabo`.`in_zjzh_id` AS `in_zjzh_id`,`huabo`.`amount` AS `amount`,`huabo`.`cost` AS `cost` from (`yw` left join `yw_zj_huabo` `huabo` on((`yw`.`id` = `huabo`.`yw_id`))) where (`yw`.`yw_fl_id` = 15) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_zj_jinchu`
--
DROP TABLE IF EXISTS `zzvw_yw_zj_jinchu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_zj_jinchu`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created` from `yw` where (`yw`.`yw_fl_id` = 4) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_zj_pj_tiexi`
--
DROP TABLE IF EXISTS `zzvw_yw_zj_pj_tiexi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_zj_pj_tiexi`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`tiexi`.`zjzh_id` AS `zjzh_id`,`tiexi`.`zj_pj_id` AS `zj_pj_id`,`tiexi`.`amount` AS `amount`,`tiexi`.`cost` AS `cost`,`tiexi`.`cash_zjzh_id` AS `cash_zjzh_id`,`zj_pj`.`total_money` AS `total_money`,`zj_pj`.`code` AS `code`,`zj_pj`.`expire_date` AS `expire_date` from ((`yw` left join `yw_zj_pj_tiexi` `tiexi` on((`yw`.`id` = `tiexi`.`yw_id`))) left join `zj_pj` on((`zj_pj`.`id` = `tiexi`.`zj_pj_id`))) where (`yw`.`yw_fl_id` = 14) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_yw_zj_zhifu`
--
DROP TABLE IF EXISTS `zzvw_yw_zj_zhifu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_yw_zj_zhifu`  AS  select `yw`.`id` AS `id`,`yw`.`name` AS `name`,`yw`.`yw_fl_id` AS `yw_fl_id`,`yw`.`hb_id` AS `hb_id`,`yw`.`happen_date` AS `happen_date`,`yw`.`jbr_id` AS `jbr_id`,`yw`.`dj` AS `dj`,`yw`.`note` AS `note`,`yw`.`isactive` AS `isactive`,`yw`.`creater_id` AS `creater_id`,`yw`.`created` AS `created`,`zf`.`zj_cause_id` AS `zj_cause_id`,`zf`.`zj_fl_id` AS `zj_fl_id`,`zf`.`zjzh_id` AS `zjzh_id`,`zf`.`zj_pj_id` AS `zj_pj_id`,`zf`.`amount` AS `amount`,`zf`.`cost` AS `cost`,`pj`.`zj_pj_fl_id` AS `zj_pj_fl_id`,`pj`.`code` AS `code`,`pj`.`total_money` AS `total_money`,`pj`.`expire_date` AS `expire_date`,`pj`.`from_yw_id` AS `from_yw_id`,`pj`.`to_yw_id` AS `to_yw_id` from ((`yw` left join `yw_zj_zhifu` `zf` on((`yw`.`id` = `zf`.`yw_id`))) left join `zj_pj` `pj` on((`zf`.`zj_pj_id` = `pj`.`id`))) where (`yw`.`yw_fl_id` = 12) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zjzh_cash`
--
DROP TABLE IF EXISTS `zzvw_zjzh_cash`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zjzh_cash`  AS  select `zjzh`.`id` AS `id`,`zjzh`.`name` AS `name`,`zjzh`.`account_no` AS `account_no`,`zjzh`.`bizhong_id` AS `bizhong_id`,`zjzh`.`pd_date` AS `pd_date`,`zjzh`.`init_amount` AS `init_amount`,`zjzh`.`remained` AS `remained`,`zjzh`.`zj_fl_id` AS `zj_fl_id`,`zjzh`.`owner_id` AS `owner_id`,`zjzh`.`created` AS `created` from `zjzh` where (`zjzh`.`zj_fl_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zjzh_pj`
--
DROP TABLE IF EXISTS `zzvw_zjzh_pj`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zjzh_pj`  AS  select `zjzh`.`id` AS `id`,`zjzh`.`name` AS `name`,`zjzh`.`account_no` AS `account_no`,`zjzh`.`bizhong_id` AS `bizhong_id`,`zjzh`.`pd_date` AS `pd_date`,`zjzh`.`init_amount` AS `init_amount`,`zjzh`.`remained` AS `remained`,`zjzh`.`zj_fl_id` AS `zj_fl_id`,`zjzh`.`owner_id` AS `owner_id`,`zjzh`.`created` AS `created` from `zjzh` where (`zjzh`.`zj_fl_id` = 2) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zj_cause_hk`
--
DROP TABLE IF EXISTS `zzvw_zj_cause_hk`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zj_cause_hk`  AS  select `zj_cause`.`id` AS `id`,`zj_cause`.`name` AS `name`,`zj_cause`.`zj_direct_id` AS `zj_direct_id` from `zj_cause` where (`zj_cause`.`zj_direct_id` = 2) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zj_cause_zhifu`
--
DROP TABLE IF EXISTS `zzvw_zj_cause_zhifu`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zj_cause_zhifu`  AS  select `zj_cause`.`id` AS `id`,`zj_cause`.`name` AS `name`,`zj_cause`.`zj_direct_id` AS `zj_direct_id` from `zj_cause` where (`zj_cause`.`zj_direct_id` = 1) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zj_pj_notused`
--
DROP TABLE IF EXISTS `zzvw_zj_pj_notused`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zj_pj_notused`  AS  select `zj_pj`.`id` AS `id`,`zj_pj`.`zj_pj_fl_id` AS `zj_pj_fl_id`,`zj_pj`.`code` AS `code`,`zj_pj`.`total_money` AS `total_money`,`zj_pj`.`expire_date` AS `expire_date`,`zj_pj`.`from_yw_id` AS `from_yw_id`,`zj_pj`.`to_yw_id` AS `to_yw_id`,`zj_pj`.`dj_id` AS `dj_id`,`zj_pj`.`note` AS `note` from `zj_pj` where (`zj_pj`.`to_yw_id` = 0) ;

-- --------------------------------------------------------

--
-- 视图结构 `zzvw_zxr`
--
DROP TABLE IF EXISTS `zzvw_zxr`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `zzvw_zxr`  AS  select `hb`.`id` AS `id`,`hb`.`hb_fl_id` AS `hb_fl_id`,`hb`.`name` AS `name`,`hb`.`gender_id` AS `gender_id`,`hb`.`zhengjian_fl_id` AS `zhengjian_fl_id`,`hb`.`identity_no` AS `identity_no`,`hb`.`bank_account_no` AS `bank_account_no`,`hb`.`address` AS `address`,`hb`.`lxr` AS `lxr`,`hb`.`cell_no` AS `cell_no`,`hb`.`init_date` AS `init_date`,`hb`.`init_account_receivable` AS `init_account_receivable`,`hb`.`account_receivable` AS `account_receivable`,`hb`.`credit_level_id` AS `credit_level_id`,`hb`.`isactive` AS `isactive` from (`hb` left join `hb_wz` on((`hb`.`id` = `hb_wz`.`hb_id`))) where ((`hb_wz`.`wz_id` = 2) or (`hb`.`hb_fl_id` = 1)) group by `hb`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `baoxian_type`
--
ALTER TABLE `baoxian_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bizhong`
--
ALTER TABLE `bizhong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calc_method`
--
ALTER TABLE `calc_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chuku`
--
ALTER TABLE `chuku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yw_id` (`yw_id`),
  ADD KEY `wz_id` (`pici_id`);

--
-- Indexes for table `ck`
--
ALTER TABLE `ck`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ck_fl`
--
ALTER TABLE `ck_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ck_pd`
--
ALTER TABLE `ck_pd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ck_weizhi`
--
ALTER TABLE `ck_weizhi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_method`
--
ALTER TABLE `contact_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `credit_level`
--
ALTER TABLE `credit_level`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_type`
--
ALTER TABLE `data_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `defect`
--
ALTER TABLE `defect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `defect_gx`
--
ALTER TABLE `defect_gx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `defect_gx_wz`
--
ALTER TABLE `defect_gx_wz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dingdan`
--
ALTER TABLE `dingdan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dingdan_jfjh`
--
ALTER TABLE `dingdan_jfjh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dingdan_status`
--
ALTER TABLE `dingdan_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dj`
--
ALTER TABLE `dj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dj_fl`
--
ALTER TABLE `dj_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc`
--
ALTER TABLE `doc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_fl`
--
ALTER TABLE `doc_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `doc_keyword`
--
ALTER TABLE `doc_keyword`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp`
--
ALTER TABLE `fp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `yw_id` (`yw_id`);

--
-- Indexes for table `fp_fl`
--
ALTER TABLE `fp_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fp_yw`
--
ALTER TABLE `fp_yw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx`
--
ALTER TABLE `gx`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gx_fl_id` (`gx_fl_id`);

--
-- Indexes for table `gx_de`
--
ALTER TABLE `gx_de`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_de_input`
--
ALTER TABLE `gx_de_input`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_de_output`
--
ALTER TABLE `gx_de_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_fl`
--
ALTER TABLE `gx_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_hjcs`
--
ALTER TABLE `gx_hjcs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_input`
--
ALTER TABLE `gx_input`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_input_defect`
--
ALTER TABLE `gx_input_defect`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_output`
--
ALTER TABLE `gx_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_pre_gx`
--
ALTER TABLE `gx_pre_gx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gx_work_type`
--
ALTER TABLE `gx_work_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gx_id` (`gx_id`),
  ADD KEY `work_type_id` (`work_type_id`);

--
-- Indexes for table `gx_wz`
--
ALTER TABLE `gx_wz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gy`
--
ALTER TABLE `gy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gy_detail`
--
ALTER TABLE `gy_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb`
--
ALTER TABLE `hb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_account_receivable`
--
ALTER TABLE `hb_account_receivable`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_contact_method`
--
ALTER TABLE `hb_contact_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_fl`
--
ALTER TABLE `hb_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_hobby`
--
ALTER TABLE `hb_hobby`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_skill`
--
ALTER TABLE `hb_skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_wz`
--
ALTER TABLE `hb_wz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_yf`
--
ALTER TABLE `hb_yf`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hb_yg`
--
ALTER TABLE `hb_yg`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ht_id` (`hb_id`);

--
-- Indexes for table `hjcs`
--
ALTER TABLE `hjcs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hobby`
--
ALTER TABLE `hobby`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl`
--
ALTER TABLE `jl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_fl`
--
ALTER TABLE `jl_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_fy_bx`
--
ALTER TABLE `jl_fy_bx`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jl_fy_fl`
--
ALTER TABLE `jl_fy_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jszb`
--
ALTER TABLE `jszb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jszb_wz`
--
ALTER TABLE `jszb_wz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jszb_wz_pici`
--
ALTER TABLE `jszb_wz_pici`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyword`
--
ALTER TABLE `keyword`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lxr`
--
ALTER TABLE `lxr`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muju`
--
ALTER TABLE `muju`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muju_from`
--
ALTER TABLE `muju_from`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `muju_type`
--
ALTER TABLE `muju_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pici`
--
ALTER TABLE `pici`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `price_model`
--
ALTER TABLE `price_model`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ruku`
--
ALTER TABLE `ruku`
  ADD PRIMARY KEY (`id`),
  ADD KEY `yw_id` (`yw_id`),
  ADD KEY `wz_id` (`pici_id`);

--
-- Indexes for table `salary_fl`
--
ALTER TABLE `salary_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scdj`
--
ALTER TABLE `scdj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scdj_input`
--
ALTER TABLE `scdj_input`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scdj_output`
--
ALTER TABLE `scdj_output`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scjh`
--
ALTER TABLE `scjh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill_grade`
--
ALTER TABLE `skill_grade`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill_work_type`
--
ALTER TABLE `skill_work_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tj`
--
ALTER TABLE `tj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tj_ht_detail`
--
ALTER TABLE `tj_ht_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tj_wz_detail`
--
ALTER TABLE `tj_wz_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tj_zj_detail`
--
ALTER TABLE `tj_zj_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_fl_id` (`unit_fl_id`);

--
-- Indexes for table `unit_fl`
--
ALTER TABLE `unit_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `work_type`
--
ALTER TABLE `work_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz`
--
ALTER TABLE `wz`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wz_fl_id` (`wz_fl_id`);

--
-- Indexes for table `wz_cp_zuhe`
--
ALTER TABLE `wz_cp_zuhe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz_detail`
--
ALTER TABLE `wz_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz_fl`
--
ALTER TABLE `wz_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz_sb`
--
ALTER TABLE `wz_sb`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz_sb_wh`
--
ALTER TABLE `wz_sb_wh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wz_sb_wh_wz`
--
ALTER TABLE `wz_sb_wh_wz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yg_skill`
--
ALTER TABLE `yg_skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw`
--
ALTER TABLE `yw`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_fh_detail`
--
ALTER TABLE `yw_fh_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_fl`
--
ALTER TABLE `yw_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_jth_detail`
--
ALTER TABLE `yw_jth_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_kp`
--
ALTER TABLE `yw_kp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_ruku`
--
ALTER TABLE `yw_ruku`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_scdj`
--
ALTER TABLE `yw_scdj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_sh_detail`
--
ALTER TABLE `yw_sh_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_th_detail`
--
ALTER TABLE `yw_th_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_yunshu`
--
ALTER TABLE `yw_yunshu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj`
--
ALTER TABLE `yw_zj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj_hk`
--
ALTER TABLE `yw_zj_hk`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj_huabo`
--
ALTER TABLE `yw_zj_huabo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj_jinchu`
--
ALTER TABLE `yw_zj_jinchu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj_pj_tiexi`
--
ALTER TABLE `yw_zj_pj_tiexi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `yw_zj_zhifu`
--
ALTER TABLE `yw_zj_zhifu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zhengjian_fl`
--
ALTER TABLE `zhengjian_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zjzh`
--
ALTER TABLE `zjzh`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zjzh_history`
--
ALTER TABLE `zjzh_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zjzh_pd`
--
ALTER TABLE `zjzh_pd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_cause`
--
ALTER TABLE `zj_cause`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_direct`
--
ALTER TABLE `zj_direct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_fl`
--
ALTER TABLE `zj_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_jinchu`
--
ALTER TABLE `zj_jinchu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_package`
--
ALTER TABLE `zj_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_pj`
--
ALTER TABLE `zj_pj`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zj_pj_fl`
--
ALTER TABLE `zj_pj_fl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zl`
--
ALTER TABLE `zl`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `baoxian_type`
--
ALTER TABLE `baoxian_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `bizhong`
--
ALTER TABLE `bizhong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `calc_method`
--
ALTER TABLE `calc_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `chuku`
--
ALTER TABLE `chuku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- 使用表AUTO_INCREMENT `ck`
--
ALTER TABLE `ck`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `ck_fl`
--
ALTER TABLE `ck_fl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- 使用表AUTO_INCREMENT `ck_pd`
--
ALTER TABLE `ck_pd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `ck_weizhi`
--
ALTER TABLE `ck_weizhi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `contact_method`
--
ALTER TABLE `contact_method`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `credit_level`
--
ALTER TABLE `credit_level`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `data_type`
--
ALTER TABLE `data_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `defect`
--
ALTER TABLE `defect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `defect_gx`
--
ALTER TABLE `defect_gx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
--
-- 使用表AUTO_INCREMENT `defect_gx_wz`
--
ALTER TABLE `defect_gx_wz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;
--
-- 使用表AUTO_INCREMENT `dept`
--
ALTER TABLE `dept`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `dingdan`
--
ALTER TABLE `dingdan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `dingdan_jfjh`
--
ALTER TABLE `dingdan_jfjh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
--
-- 使用表AUTO_INCREMENT `dingdan_status`
--
ALTER TABLE `dingdan_status`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `dj`
--
ALTER TABLE `dj`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `dj_fl`
--
ALTER TABLE `dj_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `doc`
--
ALTER TABLE `doc`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `doc_fl`
--
ALTER TABLE `doc_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `doc_keyword`
--
ALTER TABLE `doc_keyword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `fp`
--
ALTER TABLE `fp`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `fp_fl`
--
ALTER TABLE `fp_fl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `fp_yw`
--
ALTER TABLE `fp_yw`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `gx`
--
ALTER TABLE `gx`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `gx_de`
--
ALTER TABLE `gx_de`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `gx_de_input`
--
ALTER TABLE `gx_de_input`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `gx_de_output`
--
ALTER TABLE `gx_de_output`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `gx_fl`
--
ALTER TABLE `gx_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `gx_hjcs`
--
ALTER TABLE `gx_hjcs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `gx_input`
--
ALTER TABLE `gx_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;
--
-- 使用表AUTO_INCREMENT `gx_input_defect`
--
ALTER TABLE `gx_input_defect`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `gx_output`
--
ALTER TABLE `gx_output`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `gx_pre_gx`
--
ALTER TABLE `gx_pre_gx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
--
-- 使用表AUTO_INCREMENT `gx_work_type`
--
ALTER TABLE `gx_work_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `gx_wz`
--
ALTER TABLE `gx_wz`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;
--
-- 使用表AUTO_INCREMENT `gy`
--
ALTER TABLE `gy`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `gy_detail`
--
ALTER TABLE `gy_detail`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `hb`
--
ALTER TABLE `hb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `hb_account_receivable`
--
ALTER TABLE `hb_account_receivable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `hb_contact_method`
--
ALTER TABLE `hb_contact_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `hb_fl`
--
ALTER TABLE `hb_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `hb_hobby`
--
ALTER TABLE `hb_hobby`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- 使用表AUTO_INCREMENT `hb_skill`
--
ALTER TABLE `hb_skill`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `hb_wz`
--
ALTER TABLE `hb_wz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- 使用表AUTO_INCREMENT `hb_yf`
--
ALTER TABLE `hb_yf`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `hb_yg`
--
ALTER TABLE `hb_yg`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `hjcs`
--
ALTER TABLE `hjcs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `hobby`
--
ALTER TABLE `hobby`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `jl`
--
ALTER TABLE `jl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jl_fl`
--
ALTER TABLE `jl_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jl_fy_bx`
--
ALTER TABLE `jl_fy_bx`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jl_fy_fl`
--
ALTER TABLE `jl_fy_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `jszb`
--
ALTER TABLE `jszb`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `jszb_wz`
--
ALTER TABLE `jszb_wz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `jszb_wz_pici`
--
ALTER TABLE `jszb_wz_pici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `keyword`
--
ALTER TABLE `keyword`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `lxr`
--
ALTER TABLE `lxr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `muju`
--
ALTER TABLE `muju`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `muju_from`
--
ALTER TABLE `muju_from`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `muju_type`
--
ALTER TABLE `muju_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `pici`
--
ALTER TABLE `pici`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `position`
--
ALTER TABLE `position`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `price_model`
--
ALTER TABLE `price_model`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `ruku`
--
ALTER TABLE `ruku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `salary_fl`
--
ALTER TABLE `salary_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `scdj`
--
ALTER TABLE `scdj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `scdj_input`
--
ALTER TABLE `scdj_input`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `scdj_output`
--
ALTER TABLE `scdj_output`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `scjh`
--
ALTER TABLE `scjh`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `skill_grade`
--
ALTER TABLE `skill_grade`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `skill_work_type`
--
ALTER TABLE `skill_work_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `tj`
--
ALTER TABLE `tj`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- 使用表AUTO_INCREMENT `tj_ht_detail`
--
ALTER TABLE `tj_ht_detail`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tj_wz_detail`
--
ALTER TABLE `tj_wz_detail`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `tj_zj_detail`
--
ALTER TABLE `tj_zj_detail`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- 使用表AUTO_INCREMENT `unit_fl`
--
ALTER TABLE `unit_fl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `work_type`
--
ALTER TABLE `work_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- 使用表AUTO_INCREMENT `wz`
--
ALTER TABLE `wz`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- 使用表AUTO_INCREMENT `wz_cp_zuhe`
--
ALTER TABLE `wz_cp_zuhe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `wz_detail`
--
ALTER TABLE `wz_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `wz_fl`
--
ALTER TABLE `wz_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `wz_sb`
--
ALTER TABLE `wz_sb`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `wz_sb_wh`
--
ALTER TABLE `wz_sb_wh`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `wz_sb_wh_wz`
--
ALTER TABLE `wz_sb_wh_wz`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yg_skill`
--
ALTER TABLE `yg_skill`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `yw`
--
ALTER TABLE `yw`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- 使用表AUTO_INCREMENT `yw_fh_detail`
--
ALTER TABLE `yw_fh_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_fl`
--
ALTER TABLE `yw_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- 使用表AUTO_INCREMENT `yw_jth_detail`
--
ALTER TABLE `yw_jth_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_kp`
--
ALTER TABLE `yw_kp`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_ruku`
--
ALTER TABLE `yw_ruku`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_scdj`
--
ALTER TABLE `yw_scdj`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_sh_detail`
--
ALTER TABLE `yw_sh_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `yw_th_detail`
--
ALTER TABLE `yw_th_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_yunshu`
--
ALTER TABLE `yw_yunshu`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `yw_zj`
--
ALTER TABLE `yw_zj`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_zj_hk`
--
ALTER TABLE `yw_zj_hk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_zj_huabo`
--
ALTER TABLE `yw_zj_huabo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `yw_zj_jinchu`
--
ALTER TABLE `yw_zj_jinchu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_zj_pj_tiexi`
--
ALTER TABLE `yw_zj_pj_tiexi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `yw_zj_zhifu`
--
ALTER TABLE `yw_zj_zhifu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `zhengjian_fl`
--
ALTER TABLE `zhengjian_fl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `zjzh`
--
ALTER TABLE `zjzh`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `zjzh_history`
--
ALTER TABLE `zjzh_history`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `zjzh_pd`
--
ALTER TABLE `zjzh_pd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `zj_cause`
--
ALTER TABLE `zj_cause`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- 使用表AUTO_INCREMENT `zj_direct`
--
ALTER TABLE `zj_direct`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `zj_fl`
--
ALTER TABLE `zj_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `zj_jinchu`
--
ALTER TABLE `zj_jinchu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `zj_package`
--
ALTER TABLE `zj_package`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `zj_pj`
--
ALTER TABLE `zj_pj`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `zj_pj_fl`
--
ALTER TABLE `zj_pj_fl`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `zl`
--
ALTER TABLE `zl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
