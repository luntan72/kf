-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-05-21 05:05:40
-- 服务器版本： 10.1.10-MariaDB
-- PHP Version: 5.6.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `useradmin`
--

-- --------------------------------------------------------

--
-- 表的结构 `acl`
--

CREATE TABLE `acl` (
  `id` int(4) UNSIGNED NOT NULL COMMENT 'This table links the group, action and module. this table decides the access right of a group',
  `role_id` int(4) UNSIGNED NOT NULL DEFAULT '0',
  `resource` char(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action` char(40) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `action_type`
--

CREATE TABLE `action_type` (
  `id` int(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `assign_type`
--

CREATE TABLE `assign_type` (
  `id` int(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `circle`
--

CREATE TABLE `circle` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `creater_id` int(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='圈子：交际圈';

-- --------------------------------------------------------

--
-- 表的结构 `circle_user`
--

CREATE TABLE `circle_user` (
  `id` int(4) UNSIGNED NOT NULL,
  `circle_id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='圈子包含的人';

-- --------------------------------------------------------

--
-- 表的结构 `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `email_postfix` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='company information';

--
-- 转存表中的数据 `company`
--

INSERT INTO `company` (`id`, `name`, `email_postfix`) VALUES
(1, '天成机械', 'tcjx.com');

-- --------------------------------------------------------

--
-- 表的结构 `company_config`
--

CREATE TABLE `company_config` (
  `id` int(4) UNSIGNED NOT NULL,
  `company_id` int(11) NOT NULL,
  `db_name` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `config` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `expe`
--

CREATE TABLE `expe` (
  `id` int(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(4) NOT NULL,
  `expe_type_id` int(4) NOT NULL,
  `from` date NOT NULL,
  `to` date DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `happen_place` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '地点，对学习而言是学校，对工作而言是公司',
  `expe_dept_id` int(11) NOT NULL COMMENT '职位，对学习而言是专业，对工作而言是部门',
  `expe_level_id` int(11) NOT NULL COMMENT '级别，对学习而言是学位，对工作而言是级别'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='个人经历';

-- --------------------------------------------------------

--
-- 表的结构 `expe_comment`
--

CREATE TABLE `expe_comment` (
  `id` int(4) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creater_id` int(4) UNSIGNED NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `experience_user_id` int(4) NOT NULL,
  `replyto` int(4) DEFAULT NULL,
  `emailto` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if email to the element owner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='对经历的评价';

-- --------------------------------------------------------

--
-- 表的结构 `expe_dept_id`
--

CREATE TABLE `expe_dept_id` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `expe_type_id` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `expe_level_id`
--

CREATE TABLE `expe_level_id` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `expe_type_id` int(11) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `expe_type`
--

CREATE TABLE `expe_type` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='经历类型';

-- --------------------------------------------------------

--
-- 表的结构 `functions`
--

CREATE TABLE `functions` (
  `id` int(4) UNSIGNED NOT NULL,
  `action` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `description` char(255) COLLATE utf8_unicode_ci NOT NULL,
  `url` char(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `navmenu` tinyint(1) NOT NULL DEFAULT '0',
  `pid` int(4) DEFAULT '0',
  `lft` int(4) NOT NULL DEFAULT '0',
  `rgt` int(4) NOT NULL DEFAULT '0',
  `level` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `group`
--

CREATE TABLE `group` (
  `id` int(4) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `group`
--

INSERT INTO `group` (`id`, `name`, `description`) VALUES
(1, 'manager', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `group_user`
--

CREATE TABLE `group_user` (
  `id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `group_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `group_user`
--

INSERT INTO `group_user` (`id`, `user_id`, `group_id`) VALUES
(3, 1, 1),
(6, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `index_role`
--

CREATE TABLE `index_role` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `index_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='不同的角色在首页应显示的内容';

-- --------------------------------------------------------

--
-- 表的结构 `interest_big_type`
--

CREATE TABLE `interest_big_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `interest_rate`
--

CREATE TABLE `interest_rate` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `interest_type`
--

CREATE TABLE `interest_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `interest_big_type_id` int(11) NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `interest_user`
--

CREATE TABLE `interest_user` (
  `id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `interest_type_id` int(4) NOT NULL,
  `interest_rate_id` int(11) NOT NULL DEFAULT '1',
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `creater_id` int(11) NOT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `oper` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `db_table` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `log`
--

INSERT INTO `log` (`id`, `created`, `creater_id`, `params`, `oper`, `db_table`) VALUES
(1, '2016-03-08 12:22:33', 1, '{"container":"mainContent","db":"useradmin","table":"user","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"username\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":222},\\"password\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":222},\\"password_salt\\":{\\"order\\":3,\\"hidden\\":false,\\"width\\":222},\\"nickname\\":{\\"order\\":4,\\"hidden\\":false,\\"width\\":222},\\"email\\":{\\"order\\":5,\\"hidden\\":false,\\"width\\":222},\\"company_id\\":{\\"order\\":6,\\"hidden\\":false,\\"width\\":222},\\"created\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":100},\\"status_id\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":224}}","real_table":"user"}', 'saveCookie', 'useradmin.user'),
(2, '2016-03-08 13:40:52', 1, '{"db":"useradmin","table":"user","parent":"0","company_id":"1","username":"admin","nickname":"admin","email":"admin@kuafusoft.com","role_id":"1","group_id":"1","id":"1","real_table":"user"}', 'beforeSave', 'useradmin.user'),
(3, '2016-03-08 13:40:53', 1, '{"db":"useradmin","table":"user","parent":"0","cloneit":"false","company_id":"1","username":"admin","nickname":"admin","email":"admin@kuafusoft.com","role_id":"1","group_id":"1","id":"1","real_table":"user"}', 'save', 'useradmin.user'),
(4, '2016-04-09 02:20:15', 1, '{"db":"useradmin","table":"role","parent":"0","name":"qygl","description":"\\u4f01\\u4e1a\\u7ba1\\u7406","id":"0","real_table":"role"}', 'beforeSave', 'useradmin.role'),
(5, '2016-04-09 02:20:15', 1, '{"db":"useradmin","table":"role","parent":"0","cloneit":"false","name":"qygl","description":"\\u4f01\\u4e1a\\u7ba1\\u7406","id":"0","real_table":"role"}', 'save', 'useradmin.role'),
(6, '2016-04-09 04:50:27', 1, '{"db":"useradmin","table":"user","parent":"0","company_id":"1","username":"admin","nickname":"admin","email":"admin@kuafusoft.com","role_id":"1","group_id":"1","id":"1","real_table":"user"}', 'beforeSave', 'useradmin.user'),
(7, '2016-04-09 04:50:27', 1, '{"db":"useradmin","table":"user","parent":"0","cloneit":"false","company_id":"1","username":"admin","nickname":"admin","email":"admin@kuafusoft.com","role_id":"1","group_id":"1","id":"1","real_table":"user"}', 'save', 'useradmin.user'),
(8, '2016-04-09 04:56:32', 1, '{"db":"useradmin","table":"role","parent":"0","name":"admin","description":"\\u7cfb\\u7edf\\u7ba1\\u7406\\u5458","user_ids":["1"],"id":"1","real_table":"role"}', 'beforeSave', 'useradmin.role'),
(9, '2016-04-09 04:56:32', 1, '{"db":"useradmin","table":"role","parent":"0","cloneit":"false","name":"admin","description":"\\u7cfb\\u7edf\\u7ba1\\u7406\\u5458","user_ids":["1"],"id":"1","real_table":"role"}', 'save', 'useradmin.role'),
(10, '2016-04-09 04:57:48', 1, '{"db":"useradmin","table":"role","parent":"0","name":"qygl","description":"\\u4f01\\u4e1a\\u7ba1\\u7406","user_ids":["2"],"id":"2","real_table":"role"}', 'beforeSave', 'useradmin.role'),
(11, '2016-04-09 04:57:49', 1, '{"db":"useradmin","table":"role","parent":"0","cloneit":"false","name":"qygl","description":"\\u4f01\\u4e1a\\u7ba1\\u7406","user_ids":["2"],"id":"2","real_table":"role"}', 'save', 'useradmin.role'),
(12, '2016-04-09 13:33:28', 1, '{"db":"useradmin","table":"user","parent":"0","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'beforeSave', 'useradmin.user'),
(13, '2016-04-09 13:33:29', 1, '{"db":"useradmin","table":"user","parent":"0","cloneit":"false","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'save', 'useradmin.user'),
(14, '2016-04-09 13:34:17', 1, '{"db":"useradmin","table":"user","parent":"0","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["1","2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'beforeSave', 'useradmin.user'),
(15, '2016-04-09 13:34:18', 1, '{"db":"useradmin","table":"user","parent":"0","cloneit":"false","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["1","2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'save', 'useradmin.user'),
(16, '2016-04-11 11:43:14', 2, '{"db":"useradmin","table":"role","parent":"0","name":"workflow","description":"\\u5de5\\u4f5c\\u6d41\\u7a0b","user_ids":["2"],"id":"0","real_table":"role"}', 'beforeSave', 'useradmin.role'),
(17, '2016-04-11 11:43:14', 2, '{"db":"useradmin","table":"role","parent":"0","cloneit":"false","name":"workflow","description":"\\u5de5\\u4f5c\\u6d41\\u7a0b","user_ids":["2"],"id":"0","real_table":"role"}', 'save', 'useradmin.role'),
(18, '2016-04-11 11:43:29', 2, '{"db":"useradmin","table":"user","parent":"0","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["1","2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'beforeSave', 'useradmin.user'),
(19, '2016-04-11 11:43:29', 2, '{"db":"useradmin","table":"user","parent":"0","cloneit":"false","company_id":"1","username":"yye","nickname":"\\u53f6\\u6c38\\u5229","email":"kuafusoft@gmail.com","role_ids":["1","2"],"group_ids":["1"],"id":"2","real_table":"user"}', 'save', 'useradmin.user'),
(20, '2016-04-15 11:57:17', 2, '{"db":"xt","table":"testcase_ver","cell_id":"dir","id":"0","subdir":"my23097","real_table":"testcase_ver"}', 'refreshcell', 'xt.testcase_ver'),
(21, '2016-04-15 11:57:23', 2, '{"db":"xt","table":"testcase_ver","cell_id":"dir","id":"0","subdir":"my23097","real_table":"testcase_ver"}', 'refreshcell', 'xt.testcase_ver'),
(22, '2016-04-15 11:57:26', 2, '{"db":"xt","table":"testcase_ver","cell_id":"dir","id":"0","subdir":"my23097","real_table":"testcase_ver"}', 'refreshcell', 'xt.testcase_ver'),
(23, '2016-04-15 12:03:58', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u5ba2\\u6237","id":"0","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(24, '2016-04-15 12:03:59', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u5ba2\\u6237","id":"0","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(25, '2016-04-15 12:04:06', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u4f9b\\u5e94\\u5546","id":"0","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(26, '2016-04-15 12:04:06', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u4f9b\\u5e94\\u5546","id":"0","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(27, '2016-04-15 12:04:14', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u5458\\u5de5","id":"0","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(28, '2016-04-15 12:04:14', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u5458\\u5de5","id":"0","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(29, '2016-04-15 12:04:28', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u6295\\u8d44\\u4eba","id":"0","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(30, '2016-04-15 12:04:28', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u6295\\u8d44\\u4eba","id":"0","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(31, '2016-04-15 12:05:53', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u91d1\\u878d\\u76f8\\u5173\\u4eba","id":"4","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(32, '2016-04-15 12:05:53', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u91d1\\u878d\\u76f8\\u5173\\u4eba","id":"4","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(33, '2016-04-15 12:06:19', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u5458\\u5de51","id":"1","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(34, '2016-04-15 12:06:19', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u5458\\u5de51","id":"1","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(35, '2016-04-15 12:06:36', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u5ba2\\u6237","id":"2","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(36, '2016-04-15 12:06:36', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u5ba2\\u6237","id":"2","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(37, '2016-04-15 12:06:46', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u4f9b\\u5e94\\u5546","id":"3","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(38, '2016-04-15 12:06:46', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u4f9b\\u5e94\\u5546","id":"3","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(39, '2016-04-15 12:06:55', 2, '{"db":"qygl","table":"hb_fl","parent":"0","name":"\\u5458\\u5de5","id":"1","real_table":"hb_fl"}', 'beforeSave', 'qygl.hb_fl'),
(40, '2016-04-15 12:06:55', 2, '{"db":"qygl","table":"hb_fl","parent":"0","cloneit":"false","name":"\\u5458\\u5de5","id":"1","real_table":"hb_fl"}', 'save', 'qygl.hb_fl'),
(41, '2016-04-15 12:07:40', 2, '{"db":"qygl","table":"zj_fl","parent":"0","name":"\\u73b0\\u91d1","id":"0","real_table":"zj_fl"}', 'beforeSave', 'qygl.zj_fl'),
(42, '2016-04-15 12:07:41', 2, '{"db":"qygl","table":"zj_fl","parent":"0","cloneit":"false","name":"\\u73b0\\u91d1","id":"0","real_table":"zj_fl"}', 'save', 'qygl.zj_fl'),
(43, '2016-04-15 12:07:46', 2, '{"db":"qygl","table":"zj_fl","parent":"0","name":"\\u7968\\u636e","id":"0","real_table":"zj_fl"}', 'beforeSave', 'qygl.zj_fl'),
(44, '2016-04-15 12:07:46', 2, '{"db":"qygl","table":"zj_fl","parent":"0","cloneit":"false","name":"\\u7968\\u636e","id":"0","real_table":"zj_fl"}', 'save', 'qygl.zj_fl'),
(45, '2016-04-15 12:13:23', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u91cd\\u91cf","unit_id":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(46, '2016-04-15 12:13:23', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u91cd\\u91cf","unit_id":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(47, '2016-04-15 12:13:34', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u9762\\u79ef","unit_id":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(48, '2016-04-15 12:13:34', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u9762\\u79ef","unit_id":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(49, '2016-04-15 12:13:39', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u4f53\\u79ef","unit_id":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(50, '2016-04-15 12:13:40', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u4f53\\u79ef","unit_id":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(51, '2016-04-15 12:13:47', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u6e29\\u5ea6","unit_id":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(52, '2016-04-15 12:13:47', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u6e29\\u5ea6","unit_id":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(53, '2016-04-15 12:14:33', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"zzvw_unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(54, '2016-04-15 12:14:33', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"zzvw_unit"}', 'save', 'qygl.zzvw_unit'),
(55, '2016-04-15 12:16:37', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1","id":"0","real_table":"zzvw_unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(56, '2016-04-15 12:16:37', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1","id":"0","real_table":"zzvw_unit"}', 'save', 'qygl.zzvw_unit'),
(57, '2016-04-15 12:18:24', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"\\u624b\\u673a","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(58, '2016-04-15 12:18:24', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"\\u624b\\u673a","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(59, '2016-04-15 12:18:30', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"\\u56fa\\u5b9a\\u7535\\u8bdd","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(60, '2016-04-15 12:18:30', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"\\u56fa\\u5b9a\\u7535\\u8bdd","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(61, '2016-04-15 12:18:35', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"\\u4f20\\u771f","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(62, '2016-04-15 12:18:35', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"\\u4f20\\u771f","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(63, '2016-04-15 12:18:40', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"\\u7535\\u5b50\\u90ae\\u4ef6","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(64, '2016-04-15 12:18:40', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"\\u7535\\u5b50\\u90ae\\u4ef6","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(65, '2016-04-15 12:18:45', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"QQ","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(66, '2016-04-15 12:18:46', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"QQ","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(67, '2016-04-15 12:18:50', 2, '{"db":"qygl","table":"contact_method","parent":"0","name":"\\u5fae\\u4fe1","id":"0","real_table":"contact_method"}', 'beforeSave', 'qygl.contact_method'),
(68, '2016-04-15 12:18:51', 2, '{"db":"qygl","table":"contact_method","parent":"0","cloneit":"false","name":"\\u5fae\\u4fe1","id":"0","real_table":"contact_method"}', 'save', 'qygl.contact_method'),
(69, '2016-04-15 12:20:24', 2, '{"db":"qygl","table":"skill_grade","parent":"0","name":"\\u7279\\u522b\\u7a81\\u51fa","xl":"120","id":"0","real_table":"skill_grade"}', 'beforeSave', 'qygl.skill_grade'),
(70, '2016-04-15 12:20:25', 2, '{"db":"qygl","table":"skill_grade","parent":"0","cloneit":"false","name":"\\u7279\\u522b\\u7a81\\u51fa","xl":"120","id":"0","real_table":"skill_grade"}', 'save', 'qygl.skill_grade'),
(71, '2016-04-15 12:20:33', 2, '{"db":"qygl","table":"skill_grade","parent":"0","name":"\\u4f18\\u79c0","xl":"100","id":"0","real_table":"skill_grade"}', 'beforeSave', 'qygl.skill_grade'),
(72, '2016-04-15 12:20:33', 2, '{"db":"qygl","table":"skill_grade","parent":"0","cloneit":"false","name":"\\u4f18\\u79c0","xl":"100","id":"0","real_table":"skill_grade"}', 'save', 'qygl.skill_grade'),
(73, '2016-04-15 12:20:43', 2, '{"db":"qygl","table":"skill_grade","parent":"0","name":"\\u826f\\u597d","xl":"80","id":"0","real_table":"skill_grade"}', 'beforeSave', 'qygl.skill_grade'),
(74, '2016-04-15 12:20:43', 2, '{"db":"qygl","table":"skill_grade","parent":"0","cloneit":"false","name":"\\u826f\\u597d","xl":"80","id":"0","real_table":"skill_grade"}', 'save', 'qygl.skill_grade'),
(75, '2016-04-15 12:20:56', 2, '{"db":"qygl","table":"skill_grade","parent":"0","name":"\\u666e\\u901a","xl":"70","id":"0","real_table":"skill_grade"}', 'beforeSave', 'qygl.skill_grade'),
(76, '2016-04-15 12:20:56', 2, '{"db":"qygl","table":"skill_grade","parent":"0","cloneit":"false","name":"\\u666e\\u901a","xl":"70","id":"0","real_table":"skill_grade"}', 'save', 'qygl.skill_grade'),
(77, '2016-04-15 12:21:20', 2, '{"db":"qygl","table":"skill_grade","parent":"0","name":"\\u8f83\\u5dee","xl":"60","id":"0","real_table":"skill_grade"}', 'beforeSave', 'qygl.skill_grade'),
(78, '2016-04-15 12:21:20', 2, '{"db":"qygl","table":"skill_grade","parent":"0","cloneit":"false","name":"\\u8f83\\u5dee","xl":"60","id":"0","real_table":"skill_grade"}', 'save', 'qygl.skill_grade'),
(79, '2016-04-15 12:22:11', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u5546\\u4e1a\\u8c08\\u5224","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(80, '2016-04-15 12:22:11', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u5546\\u4e1a\\u8c08\\u5224","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(81, '2016-04-15 12:22:18', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u4eba\\u5458\\u7ba1\\u7406","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(82, '2016-04-15 12:22:19', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u4eba\\u5458\\u7ba1\\u7406","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(83, '2016-04-15 12:22:32', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u7535\\u710a","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(84, '2016-04-15 12:22:32', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u7535\\u710a","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(85, '2016-04-15 12:22:43', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u8131\\u8721","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(86, '2016-04-15 12:22:43', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u8131\\u8721","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(87, '2016-04-15 12:23:01', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u5236\\u58f3","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(88, '2016-04-15 12:23:02', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u5236\\u58f3","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(89, '2016-04-15 12:23:23', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u9020\\u8721\\u578b","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(90, '2016-04-15 12:23:23', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u9020\\u8721\\u578b","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(91, '2016-04-15 12:23:38', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u7535\\u6c14\\u4fee\\u7406","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(92, '2016-04-15 12:23:38', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u7535\\u6c14\\u4fee\\u7406","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(93, '2016-04-15 12:23:50', 2, '{"db":"qygl","table":"skill","parent":"0","name":"\\u673a\\u68b0\\u4fee\\u7406","note":"","id":"0","real_table":"skill"}', 'beforeSave', 'qygl.skill'),
(94, '2016-04-15 12:23:50', 2, '{"db":"qygl","table":"skill","parent":"0","cloneit":"false","name":"\\u673a\\u68b0\\u4fee\\u7406","note":"","id":"0","real_table":"skill"}', 'save', 'qygl.skill'),
(95, '2016-04-15 12:26:05', 2, '{"db":"qygl","table":"work_type","parent":"0","name":"\\u8721\\u578b\\u5de5","note":"","skill_work_type_id":["6"],"id":"0","real_table":"work_type"}', 'beforeSave', 'qygl.work_type'),
(96, '2016-04-15 12:26:05', 2, '{"db":"qygl","table":"work_type","parent":"0","cloneit":"false","name":"\\u8721\\u578b\\u5de5","note":"","skill_work_type_id":["6"],"id":"0","real_table":"work_type"}', 'save', 'qygl.work_type'),
(97, '2016-04-15 12:26:18', 2, '{"db":"qygl","table":"work_type","parent":"0","name":"\\u8721\\u578b\\u6e05\\u7406\\u5de5","note":"","skill_work_type_id":["6"],"id":"0","real_table":"work_type"}', 'beforeSave', 'qygl.work_type'),
(98, '2016-04-15 12:26:18', 2, '{"db":"qygl","table":"work_type","parent":"0","cloneit":"false","name":"\\u8721\\u578b\\u6e05\\u7406\\u5de5","note":"","skill_work_type_id":["6"],"id":"0","real_table":"work_type"}', 'save', 'qygl.work_type'),
(99, '2016-04-15 14:40:29', 2, '{"db":"qygl","table":"work_type","parent":"0","name":"\\u8721\\u578b\\u5de5","note":"","skill_work_type_id":["3","6"],"id":"1","real_table":"work_type"}', 'beforeSave', 'qygl.work_type'),
(100, '2016-04-15 14:40:29', 2, '{"db":"qygl","table":"work_type","parent":"0","cloneit":"false","name":"\\u8721\\u578b\\u5de5","note":"","skill_work_type_id":["3","6"],"id":"1","real_table":"work_type"}', 'save', 'qygl.work_type'),
(101, '2016-04-15 14:41:34', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u666e\\u901a\\u5458\\u5de5","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(102, '2016-04-15 14:41:34', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u666e\\u901a\\u5458\\u5de5","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(103, '2016-04-15 14:41:55', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u8f66\\u95f4\\u7ba1\\u7406\\u5458","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(104, '2016-04-15 14:41:55', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u8f66\\u95f4\\u7ba1\\u7406\\u5458","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(105, '2016-04-15 14:42:03', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u540e\\u52e4\\u603b\\u7ba1","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(106, '2016-04-15 14:42:03', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u540e\\u52e4\\u603b\\u7ba1","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(107, '2016-04-15 14:42:11', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u8d22\\u52a1\\u4e3b\\u7ba1","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(108, '2016-04-15 14:42:11', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u8d22\\u52a1\\u4e3b\\u7ba1","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(109, '2016-04-15 14:42:15', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u51fa\\u7eb3","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(110, '2016-04-15 14:42:16', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u51fa\\u7eb3","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(111, '2016-04-15 14:42:23', 2, '{"db":"qygl","table":"position","parent":"0","name":"\\u603b\\u7ecf\\u7406","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'beforeSave', 'qygl.position'),
(112, '2016-04-15 14:42:23', 2, '{"db":"qygl","table":"position","parent":"0","cloneit":"false","name":"\\u603b\\u7ecf\\u7406","zhize":"","isactive":"1","id":"0","real_table":"position"}', 'save', 'qygl.position'),
(113, '2016-04-15 14:51:33', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u8d22\\u52a1\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(114, '2016-04-15 14:51:33', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u8d22\\u52a1\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(115, '2016-04-15 14:51:39', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u8ba1\\u5212\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(116, '2016-04-15 14:51:40', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u8ba1\\u5212\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(117, '2016-04-15 14:51:45', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u5ba1\\u8ba1\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(118, '2016-04-15 14:51:46', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u5ba1\\u8ba1\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(119, '2016-04-15 14:51:54', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u603b\\u7ecf\\u7406\\u5ba4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(120, '2016-04-15 14:51:54', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u603b\\u7ecf\\u7406\\u5ba4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(121, '2016-04-15 14:52:17', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u6d47\\u94f8\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(122, '2016-04-15 14:52:17', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u6d47\\u94f8\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(123, '2016-04-15 14:52:23', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u540e\\u5904\\u7406\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(124, '2016-04-15 14:52:23', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u540e\\u5904\\u7406\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(125, '2016-04-15 14:52:50', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u5931\\u8721\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(126, '2016-04-15 14:52:50', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u5931\\u8721\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(127, '2016-04-15 14:52:58', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u5236\\u58f3\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(128, '2016-04-15 14:52:59', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u5236\\u58f3\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(129, '2016-04-15 14:53:18', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u7ec4\\u6811\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(130, '2016-04-15 14:53:19', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u7ec4\\u6811\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(131, '2016-04-15 14:53:25', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u9020\\u578b\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(132, '2016-04-15 14:53:25', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u9020\\u578b\\u8f66\\u95f4","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(133, '2016-04-15 14:53:40', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u7ef4\\u4fee\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(134, '2016-04-15 14:53:40', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u7ef4\\u4fee\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(135, '2016-04-15 14:53:50', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u9500\\u552e\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(136, '2016-04-15 14:53:50', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u9500\\u552e\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(137, '2016-04-15 14:53:55', 2, '{"db":"qygl","table":"dept","parent":"0","name":"\\u91c7\\u8d2d\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'beforeSave', 'qygl.dept'),
(138, '2016-04-15 14:53:55', 2, '{"db":"qygl","table":"dept","parent":"0","cloneit":"false","name":"\\u91c7\\u8d2d\\u90e8\\u95e8","note":"","id":"0","real_table":"dept"}', 'save', 'qygl.dept'),
(139, '2016-04-17 07:20:17', 2, '{"db":"qygl","table":"credit_level","parent":"0","name":"\\u826f\\u597d","total":"100000","duration":"60","note":"\\u4fe1\\u7528\\u826f\\u597d","id":"0","real_table":"credit_level"}', 'beforeSave', 'qygl.credit_level'),
(140, '2016-04-17 07:20:17', 2, '{"db":"qygl","table":"credit_level","parent":"0","cloneit":"false","name":"\\u826f\\u597d","total":"100000","duration":"60","note":"\\u4fe1\\u7528\\u826f\\u597d","id":"0","real_table":"credit_level"}', 'save', 'qygl.credit_level'),
(141, '2016-04-17 07:20:30', 2, '{"db":"qygl","table":"credit_level","parent":"0","name":"\\u666e\\u901a","total":"50000","duration":"30","note":"\\u666e\\u901a","id":"0","real_table":"credit_level"}', 'beforeSave', 'qygl.credit_level'),
(142, '2016-04-17 07:20:30', 2, '{"db":"qygl","table":"credit_level","parent":"0","cloneit":"false","name":"\\u666e\\u901a","total":"50000","duration":"30","note":"\\u666e\\u901a","id":"0","real_table":"credit_level"}', 'save', 'qygl.credit_level'),
(143, '2016-04-17 07:21:01', 2, '{"db":"qygl","table":"credit_level","parent":"0","name":"\\u4e0d\\u660e","total":"0","duration":"0","note":"\\u60c5\\u51b5\\u4e0d\\u660e","id":"0","real_table":"credit_level"}', 'beforeSave', 'qygl.credit_level'),
(144, '2016-04-17 07:21:01', 2, '{"db":"qygl","table":"credit_level","parent":"0","cloneit":"false","name":"\\u4e0d\\u660e","total":"0","duration":"0","note":"\\u60c5\\u51b5\\u4e0d\\u660e","id":"0","real_table":"credit_level"}', 'save', 'qygl.credit_level'),
(145, '2016-04-17 07:22:01', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'beforeSave', 'qygl.zzvw_yg'),
(146, '2016-04-17 07:22:01', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'save', 'qygl.zzvw_yg'),
(147, '2016-04-17 07:23:22', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'beforeSave', 'qygl.zzvw_yg'),
(148, '2016-04-17 07:23:22', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'save', 'qygl.zzvw_yg'),
(149, '2016-04-17 07:23:41', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'beforeSave', 'qygl.zzvw_yg'),
(150, '2016-04-17 07:23:42', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u53f6\\u9716","gender_id":"1","zhengjian_fl_id":"1","identity_no":"\\u554a\\u554a\\u554a\\u554a","credit_level_id":"1","bank_account_no":"\\u554a\\u554a\\u554a\\u554a","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"","position_id":"","salary_fl_id":"","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","hb_hobby_id":"","lxr":"","cell_no":"","hb_contact_method":{"data":[{"contact_method_id":"5","content":"123"},{"contact_method_id":"6","content":"123345"}]},"id":"0","real_table":"zzvw_yg"}', 'save', 'qygl.zzvw_yg'),
(151, '2016-04-17 07:31:05', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u9605\\u8bfb","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(152, '2016-04-17 07:31:05', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u9605\\u8bfb","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(153, '2016-04-17 07:31:11', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u65c5\\u6e38","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(154, '2016-04-17 07:31:11', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u65c5\\u6e38","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(155, '2016-04-17 07:31:20', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u559d\\u9152","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(156, '2016-04-17 07:31:20', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u559d\\u9152","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(157, '2016-04-17 07:31:25', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u4ea4\\u53cb","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(158, '2016-04-17 07:31:25', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u4ea4\\u53cb","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(159, '2016-04-17 07:31:35', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u8fd0\\u52a8","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(160, '2016-04-17 07:31:35', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u8fd0\\u52a8","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(161, '2016-04-17 07:31:39', 2, '{"db":"qygl","table":"hobby","parent":"0","name":"\\u97f3\\u4e50","note":"","id":"0","real_table":"hobby"}', 'beforeSave', 'qygl.hobby'),
(162, '2016-04-17 07:31:39', 2, '{"db":"qygl","table":"hobby","parent":"0","cloneit":"false","name":"\\u97f3\\u4e50","note":"","id":"0","real_table":"hobby"}', 'save', 'qygl.hobby'),
(163, '2016-04-17 07:33:22', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"zzvw_yg"}', 'beforeSave', 'qygl.zzvw_yg'),
(164, '2016-04-17 07:33:22', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"zzvw_yg"}', 'save', 'qygl.zzvw_yg'),
(165, '2016-04-17 07:40:15', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"zzvw_yg"}', 'beforeSave', 'qygl.zzvw_yg'),
(166, '2016-04-17 07:40:15', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"zzvw_yg"}', 'save', 'qygl.zzvw_yg'),
(167, '2016-04-17 07:40:55', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"hb"}', 'beforeSave', 'qygl.zzvw_yg'),
(168, '2016-04-17 07:40:56', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u554a","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"hb"}', 'save', 'qygl.zzvw_yg'),
(169, '2016-04-17 07:42:19', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","name":"\\u53f6","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"hb"}', 'beforeSave', 'qygl.zzvw_yg'),
(170, '2016-04-17 07:42:19', 2, '{"db":"qygl","table":"zzvw_yg","parent":"0","cloneit":"false","name":"\\u53f6","gender_id":"1","zhengjian_fl_id":"1","identity_no":"111","credit_level_id":"2","bank_account_no":"111","init_date":"2016-04-17","init_account_receivable":"0","account_receivable":"0","address":"","enter_date":"2016-04-17","work_type_id":"1","dept_id":"10","position_id":"1","salary_fl_id":"1","base_salary":"800","ticheng_ratio":"0","baoxian_type_id":"1","baoxian_start_date":"2016-04-17","baoxian_feiyong":"0","lxr":"","cell_no":"","hb_hobby_id":["4","1"],"hb_contact_method":{"data":[{"contact_method_id":"5","content":"111"},{"contact_method_id":"3","content":"111111"}]},"hb_skill":{"data":[{"skill_id":"6","skill_grade_id":"4","note":""}]},"id":"0","real_table":"hb"}', 'save', 'qygl.zzvw_yg'),
(171, '2016-04-17 07:47:36', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_yg","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":104},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":104},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"init_date\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":33},\\"init_account_receivable\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":104},\\"account_receivable\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":104},\\"address\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":104},\\"enter_date\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":34},\\"work_type_id\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":104},\\"dept_id\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":104},\\"position_id\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":104},\\"salary_fl_id\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":104},\\"base_salary\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":104},\\"ticheng_ratio\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":104},\\"baoxian_type_id\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":104},\\"baoxian_start_date\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":35},\\"baoxian_feiyong\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":86},\\"hb_skill\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":104},\\"hb_hobby_id\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":104},\\"lxr\\":{\\"order\\":24,\\"hidden\\":true,\\"width\\":97},\\"cell_no\\":{\\"order\\":25,\\"hidden\\":true,\\"width\\":91},\\"isactive\\":{\\"order\\":26,\\"hidden\\":false,\\"width\\":55}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_yg'),
(172, '2016-04-17 07:47:44', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_yg","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":104},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":104},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"init_date\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":33},\\"init_account_receivable\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":104},\\"account_receivable\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":104},\\"address\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":127},\\"enter_date\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":34},\\"work_type_id\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":81},\\"dept_id\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":104},\\"position_id\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":104},\\"salary_fl_id\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":104},\\"base_salary\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":104},\\"ticheng_ratio\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":104},\\"baoxian_type_id\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":104},\\"baoxian_start_date\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":35},\\"baoxian_feiyong\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":86},\\"hb_skill\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":104},\\"hb_hobby_id\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":104},\\"lxr\\":{\\"order\\":24,\\"hidden\\":true,\\"width\\":97},\\"cell_no\\":{\\"order\\":25,\\"hidden\\":true,\\"width\\":91},\\"isactive\\":{\\"order\\":26,\\"hidden\\":false,\\"width\\":54}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_yg'),
(173, '2016-04-17 07:47:47', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_yg","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":104},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":104},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"init_date\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":33},\\"init_account_receivable\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":104},\\"account_receivable\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":104},\\"address\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":147},\\"enter_date\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":34},\\"work_type_id\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":61},\\"dept_id\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":104},\\"position_id\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":104},\\"salary_fl_id\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":104},\\"base_salary\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":104},\\"ticheng_ratio\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":104},\\"baoxian_type_id\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":104},\\"baoxian_start_date\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":35},\\"baoxian_feiyong\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":86},\\"hb_skill\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":104},\\"hb_hobby_id\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":104},\\"lxr\\":{\\"order\\":24,\\"hidden\\":true,\\"width\\":97},\\"cell_no\\":{\\"order\\":25,\\"hidden\\":true,\\"width\\":91},\\"isactive\\":{\\"order\\":26,\\"hidden\\":false,\\"width\\":53}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_yg');
INSERT INTO `log` (`id`, `created`, `creater_id`, `params`, `oper`, `db_table`) VALUES
(174, '2016-04-17 07:47:49', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_yg","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":104},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":104},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"init_date\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":33},\\"init_account_receivable\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":104},\\"account_receivable\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":104},\\"address\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":147},\\"enter_date\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":34},\\"work_type_id\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":61},\\"dept_id\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":104},\\"position_id\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":104},\\"salary_fl_id\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":104},\\"base_salary\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":104},\\"ticheng_ratio\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":104},\\"baoxian_type_id\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":104},\\"baoxian_start_date\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":35},\\"baoxian_feiyong\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":86},\\"hb_skill\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":138},\\"hb_hobby_id\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":70},\\"lxr\\":{\\"order\\":24,\\"hidden\\":true,\\"width\\":97},\\"cell_no\\":{\\"order\\":25,\\"hidden\\":true,\\"width\\":91},\\"isactive\\":{\\"order\\":26,\\"hidden\\":false,\\"width\\":52}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_yg'),
(175, '2016-04-17 07:48:05', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_yg","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":105},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":105},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"init_date\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":33},\\"init_account_receivable\\":{\\"order\\":8,\\"hidden\\":false,\\"width\\":105},\\"account_receivable\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":99},\\"address\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":154},\\"enter_date\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":34},\\"work_type_id\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":61},\\"dept_id\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":105},\\"position_id\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":105},\\"salary_fl_id\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":105},\\"base_salary\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":105},\\"ticheng_ratio\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":105},\\"baoxian_type_id\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":105},\\"baoxian_start_date\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":35},\\"baoxian_feiyong\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":86},\\"hb_skill\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":139},\\"hb_hobby_id\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":70},\\"lxr\\":{\\"order\\":24,\\"hidden\\":true,\\"width\\":97},\\"cell_no\\":{\\"order\\":25,\\"hidden\\":true,\\"width\\":91},\\"isactive\\":{\\"order\\":26,\\"hidden\\":false,\\"width\\":49}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_yg'),
(176, '2016-04-21 11:30:38', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u6570\\u91cf","description":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(177, '2016-04-21 11:30:38', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u6570\\u91cf","description":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(178, '2016-04-21 11:30:58', 2, '{"db":"qygl","table":"unit_fl","id":"1","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(179, '2016-04-21 11:31:06', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u5343\\u514b","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(180, '2016-04-21 11:31:06', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u5343\\u514b","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(181, '2016-04-21 11:31:10', 2, '{"db":"qygl","table":"unit_fl","id":"1","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(182, '2016-04-21 11:31:19', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1000","standard_unit_id":"1","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(183, '2016-04-21 11:31:20', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u514b","fen_zi":"1","fen_mu":"1000","standard_unit_id":"1","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(184, '2016-04-21 11:31:22', 2, '{"db":"qygl","table":"unit_fl","id":"1","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(185, '2016-04-21 11:31:46', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u5e02\\u65a4","fen_zi":"1","fen_mu":"2","standard_unit_id":"1","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(186, '2016-04-21 11:31:47', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u5e02\\u65a4","fen_zi":"1","fen_mu":"2","standard_unit_id":"1","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(187, '2016-04-21 11:31:50', 2, '{"db":"qygl","table":"unit_fl","id":"1","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(188, '2016-04-21 11:32:01', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"1","name":"\\u5428","fen_zi":"1000","fen_mu":"1","standard_unit_id":"1","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(189, '2016-04-21 11:32:01', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"1","name":"\\u5428","fen_zi":"1000","fen_mu":"1","standard_unit_id":"1","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(190, '2016-04-21 11:32:30', 2, '{"db":"qygl","table":"unit_fl","id":"3","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(191, '2016-04-21 11:32:40', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"3","name":"\\u7acb\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(192, '2016-04-21 11:32:40', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"3","name":"\\u7acb\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(193, '2016-04-21 11:32:43', 2, '{"db":"qygl","table":"unit_fl","id":"3","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(194, '2016-04-21 11:33:03', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"3","name":"\\u7acb\\u65b9\\u7c73","fen_zi":"1000000","fen_mu":"1","standard_unit_id":"5","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(195, '2016-04-21 11:33:03', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"3","name":"\\u7acb\\u65b9\\u7c73","fen_zi":"1000000","fen_mu":"1","standard_unit_id":"5","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(196, '2016-04-21 11:33:05', 2, '{"db":"qygl","table":"unit_fl","id":"3","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(197, '2016-04-21 11:33:38', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"3","name":"\\u5347\\uff08\\u7acb\\u65b9\\u5206\\u7c73\\uff09","fen_zi":"100","fen_mu":"1","standard_unit_id":"5","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(198, '2016-04-21 11:33:38', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"3","name":"\\u5347\\uff08\\u7acb\\u65b9\\u5206\\u7c73\\uff09","fen_zi":"100","fen_mu":"1","standard_unit_id":"5","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(199, '2016-04-21 11:34:18', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"3","name":"\\u5347\\uff08\\u7acb\\u65b9\\u5206\\u7c73\\uff09","fen_zi":"1000","fen_mu":"1","standard_unit_id":"5","id":"7","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(200, '2016-04-21 11:34:18', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"3","name":"\\u5347\\uff08\\u7acb\\u65b9\\u5206\\u7c73\\uff09","fen_zi":"1000","fen_mu":"1","standard_unit_id":"5","id":"7","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(201, '2016-04-21 11:34:54', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u957f\\u5ea6","description":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(202, '2016-04-21 11:34:54', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u957f\\u5ea6","description":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(203, '2016-04-21 11:35:01', 2, '{"db":"qygl","table":"unit_fl","id":"6","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(204, '2016-04-21 11:35:07', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"6","name":"\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(205, '2016-04-21 11:35:07', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"6","name":"\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(206, '2016-04-21 11:35:09', 2, '{"db":"qygl","table":"unit_fl","id":"6","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(207, '2016-04-21 11:35:18', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"6","name":"\\u5398\\u7c73","fen_zi":"1","fen_mu":"100","standard_unit_id":"8","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(208, '2016-04-21 11:35:18', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"6","name":"\\u5398\\u7c73","fen_zi":"1","fen_mu":"100","standard_unit_id":"8","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(209, '2016-04-21 11:35:20', 2, '{"db":"qygl","table":"unit_fl","id":"6","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(210, '2016-04-21 11:35:32', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"6","name":"\\u5206\\u7c73","fen_zi":"1","fen_mu":"10","standard_unit_id":"8","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(211, '2016-04-21 11:35:32', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"6","name":"\\u5206\\u7c73","fen_zi":"1","fen_mu":"10","standard_unit_id":"8","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(212, '2016-04-21 11:35:40', 2, '{"db":"qygl","table":"unit_fl","id":"6","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(213, '2016-04-21 11:35:48', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"6","name":"\\u5c3a","fen_zi":"1","fen_mu":"3","standard_unit_id":"8","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(214, '2016-04-21 11:35:48', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"6","name":"\\u5c3a","fen_zi":"1","fen_mu":"3","standard_unit_id":"8","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(215, '2016-04-21 11:35:59', 2, '{"db":"qygl","table":"unit_fl","id":"6","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(216, '2016-04-21 11:36:09', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"6","name":"\\u6beb\\u7c73","fen_zi":"1","fen_mu":"1000","standard_unit_id":"8","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(217, '2016-04-21 11:36:10', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"6","name":"\\u6beb\\u7c73","fen_zi":"1","fen_mu":"1000","standard_unit_id":"8","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(218, '2016-04-21 11:36:51', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u957f\\u5ea6","description":"","unit_id":"9","id":"6","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(219, '2016-04-21 11:36:51', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u957f\\u5ea6","description":"","unit_id":"9","id":"6","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(220, '2016-04-21 11:43:26', 2, '{"db":"qygl","table":"unit_fl","parent":"0","name":"\\u5bc6\\u5ea6","description":"","id":"0","real_table":"unit_fl"}', 'beforeSave', 'qygl.unit_fl'),
(221, '2016-04-21 11:43:26', 2, '{"db":"qygl","table":"unit_fl","parent":"0","cloneit":"false","name":"\\u5bc6\\u5ea6","description":"","id":"0","real_table":"unit_fl"}', 'save', 'qygl.unit_fl'),
(222, '2016-04-21 11:44:37', 2, '{"db":"qygl","table":"unit_fl","id":"7","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(223, '2016-04-21 11:44:49', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"7","name":"\\u514b\\/\\u7acb\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(224, '2016-04-21 11:44:49', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"7","name":"\\u514b\\/\\u7acb\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(225, '2016-04-21 11:44:51', 2, '{"db":"qygl","table":"unit_fl","id":"7","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(226, '2016-04-21 11:45:47', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"7","name":"\\u5343\\u514b\\/\\u7acb\\u65b9\\u5206\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"13","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(227, '2016-04-21 11:45:47', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"7","name":"\\u5343\\u514b\\/\\u7acb\\u65b9\\u5206\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"13","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(228, '2016-04-21 11:45:50', 2, '{"db":"qygl","table":"unit_fl","id":"7","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(229, '2016-04-21 11:46:47', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"7","name":"\\u5428\\/\\u7acb\\u65b9\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"13","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(230, '2016-04-21 11:46:47', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"7","name":"\\u5428\\/\\u7acb\\u65b9\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"13","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(231, '2016-04-21 11:47:05', 2, '{"db":"qygl","table":"unit_fl","id":"2","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(232, '2016-04-21 11:47:14', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(233, '2016-04-21 11:47:14', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u5398\\u7c73","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(234, '2016-04-21 11:47:17', 2, '{"db":"qygl","table":"unit_fl","id":"2","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(235, '2016-04-21 11:47:29', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u5206\\u7c73","fen_zi":"100","fen_mu":"1","standard_unit_id":"16","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(236, '2016-04-21 11:47:30', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u5206\\u7c73","fen_zi":"100","fen_mu":"1","standard_unit_id":"16","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(237, '2016-04-21 11:47:36', 2, '{"db":"qygl","table":"unit_fl","id":"2","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(238, '2016-04-21 11:49:06', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u7c73","fen_zi":"10000","fen_mu":"1","standard_unit_id":"16","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(239, '2016-04-21 11:49:06', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"2","name":"\\u5e73\\u65b9\\u7c73","fen_zi":"10000","fen_mu":"1","standard_unit_id":"16","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(240, '2016-04-21 11:49:31', 2, '{"db":"qygl","table":"unit_fl","id":"4","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(241, '2016-04-21 11:49:40', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"4","name":"\\u6444\\u6c0f\\u5ea6","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(242, '2016-04-21 11:49:40', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"4","name":"\\u6444\\u6c0f\\u5ea6","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(243, '2016-04-21 11:50:07', 2, '{"db":"qygl","table":"unit_fl","id":"5","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(244, '2016-04-21 11:50:12', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"5","name":"\\u4e2a","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(245, '2016-04-21 11:50:12', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"5","name":"\\u4e2a","fen_zi":"1","fen_mu":"1","standard_unit_id":"","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(246, '2016-04-21 11:50:58', 2, '{"db":"qygl","table":"unit_fl","id":"5","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(247, '2016-04-21 11:51:05', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","unit_fl_id":"5","name":"\\u4e32","fen_zi":"1","fen_mu":"1","standard_unit_id":"20","id":"0","real_table":"unit"}', 'beforeSave', 'qygl.zzvw_unit'),
(248, '2016-04-21 11:51:05', 2, '{"db":"qygl","table":"zzvw_unit","parent":"0","cloneit":"false","unit_fl_id":"5","name":"\\u4e32","fen_zi":"1","fen_mu":"1","standard_unit_id":"20","id":"0","real_table":"unit"}', 'save', 'qygl.zzvw_unit'),
(249, '2016-04-21 11:51:07', 2, '{"db":"qygl","table":"unit_fl","id":"5","real_table":"unit_fl"}', 'get_standard_unit', 'qygl.unit_fl'),
(250, '2016-04-21 11:51:32', 2, '{"db":"qygl","table":"zl","parent":"0","name":"\\u4f18","note":"","id":"0","real_table":"zl"}', 'beforeSave', 'qygl.zl'),
(251, '2016-04-21 11:51:32', 2, '{"db":"qygl","table":"zl","parent":"0","cloneit":"false","name":"\\u4f18","note":"","id":"0","real_table":"zl"}', 'save', 'qygl.zl'),
(252, '2016-04-21 11:51:39', 2, '{"db":"qygl","table":"zl","parent":"0","name":"\\u826f","note":"","id":"0","real_table":"zl"}', 'beforeSave', 'qygl.zl'),
(253, '2016-04-21 11:51:39', 2, '{"db":"qygl","table":"zl","parent":"0","cloneit":"false","name":"\\u826f","note":"","id":"0","real_table":"zl"}', 'save', 'qygl.zl'),
(254, '2016-04-21 11:51:44', 2, '{"db":"qygl","table":"zl","parent":"0","name":"\\u666e\\u901a","note":"","id":"0","real_table":"zl"}', 'beforeSave', 'qygl.zl'),
(255, '2016-04-21 11:51:44', 2, '{"db":"qygl","table":"zl","parent":"0","cloneit":"false","name":"\\u666e\\u901a","note":"","id":"0","real_table":"zl"}', 'save', 'qygl.zl'),
(256, '2016-04-21 11:51:52', 2, '{"db":"qygl","table":"zl","parent":"0","name":"\\u5dee","note":"","id":"0","real_table":"zl"}', 'beforeSave', 'qygl.zl'),
(257, '2016-04-21 11:51:52', 2, '{"db":"qygl","table":"zl","parent":"0","cloneit":"false","name":"\\u5dee","note":"","id":"0","real_table":"zl"}', 'save', 'qygl.zl'),
(258, '2016-04-21 12:02:55', 2, '{"db":"qygl","table":"defect","parent":"0","name":"\\u6b63\\u54c1","zl_id":"1","description":"\\u6ca1\\u6709\\u7f3a\\u9677","id":"0","real_table":"defect"}', 'beforeSave', 'qygl.defect'),
(259, '2016-04-21 12:02:55', 2, '{"db":"qygl","table":"defect","parent":"0","cloneit":"false","name":"\\u6b63\\u54c1","zl_id":"1","description":"\\u6ca1\\u6709\\u7f3a\\u9677","id":"0","real_table":"defect"}', 'save', 'qygl.defect'),
(260, '2016-04-21 12:55:22', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u539f\\u6599","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(261, '2016-04-21 12:55:22', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u539f\\u6599","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(262, '2016-04-21 12:55:58', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u8bbe\\u5907","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(263, '2016-04-21 12:55:59', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u8bbe\\u5907","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(264, '2016-04-21 12:56:20', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u4ea7\\u54c1","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(265, '2016-04-21 12:56:20', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u4ea7\\u54c1","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(266, '2016-04-21 12:56:26', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u670d\\u52a1","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(267, '2016-04-21 12:56:26', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u670d\\u52a1","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(268, '2016-04-21 12:56:35', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u52b3\\u4fdd\\u4ea7\\u54c1","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(269, '2016-04-21 12:56:35', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u52b3\\u4fdd\\u4ea7\\u54c1","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(270, '2016-04-21 12:56:46', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u529e\\u516c\\u7528\\u54c1","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(271, '2016-04-21 12:56:46', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u529e\\u516c\\u7528\\u54c1","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(272, '2016-04-21 12:56:57', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u7ef4\\u4fee\\u7528\\u54c1","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(273, '2016-04-21 12:56:58', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u7ef4\\u4fee\\u7528\\u54c1","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(274, '2016-04-21 12:57:04', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u80fd\\u6e90","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(275, '2016-04-21 12:57:04', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u80fd\\u6e90","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(276, '2016-04-21 12:57:08', 2, '{"db":"qygl","table":"wz_fl","parent":"0","name":"\\u5176\\u4ed6","id":"0","real_table":"wz_fl"}', 'beforeSave', 'qygl.wz_fl'),
(277, '2016-04-21 12:57:08', 2, '{"db":"qygl","table":"wz_fl","parent":"0","cloneit":"false","name":"\\u5176\\u4ed6","id":"0","real_table":"wz_fl"}', 'save', 'qygl.wz_fl'),
(278, '2016-04-21 12:57:50', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u7f6e\\u6362","note":"\\u7528\\u4e00\\u79cd\\u6750\\u6599\\u7f6e\\u6362\\u53e6\\u4e00\\u79cd\\u6750\\u6599","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(279, '2016-04-21 12:57:50', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u7f6e\\u6362","note":"\\u7528\\u4e00\\u79cd\\u6750\\u6599\\u7f6e\\u6362\\u53e6\\u4e00\\u79cd\\u6750\\u6599","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(280, '2016-04-21 12:58:13', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u7ec4\\u5408","note":"\\u591a\\u4e2a\\u90e8\\u4ef6\\u7ec4\\u5408\\u6210\\u4e00\\u4e2a\\u65b0\\u7684\\u4ea7\\u54c1","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(281, '2016-04-21 12:58:13', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u7ec4\\u5408","note":"\\u591a\\u4e2a\\u90e8\\u4ef6\\u7ec4\\u5408\\u6210\\u4e00\\u4e2a\\u65b0\\u7684\\u4ea7\\u54c1","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(282, '2016-04-21 12:58:35', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u5206\\u89e3","note":"\\u5c06\\u4e00\\u4e2a\\u4ea7\\u54c1\\u5206\\u89e3\\u6210\\u591a\\u4e2a\\u4ea7\\u54c1","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(283, '2016-04-21 12:58:35', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u5206\\u89e3","note":"\\u5c06\\u4e00\\u4e2a\\u4ea7\\u54c1\\u5206\\u89e3\\u6210\\u591a\\u4e2a\\u4ea7\\u54c1","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(284, '2016-04-21 12:59:06', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u6d82\\u88f9","note":"\\u5728\\u4ea7\\u54c1\\u5916\\u90e8\\u6d82\\u88f9\\u4e0a\\u4e00\\u5c42\\u5176\\u4ed6\\u6750\\u6599","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(285, '2016-04-21 12:59:06', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u6d82\\u88f9","note":"\\u5728\\u4ea7\\u54c1\\u5916\\u90e8\\u6d82\\u88f9\\u4e0a\\u4e00\\u5c42\\u5176\\u4ed6\\u6750\\u6599","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(286, '2016-04-21 12:59:13', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u52a0\\u5de5","note":"","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(287, '2016-04-21 12:59:13', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u52a0\\u5de5","note":"","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(288, '2016-04-21 12:59:26', 2, '{"db":"qygl","table":"gx_fl","parent":"0","name":"\\u975e\\u751f\\u4ea7\\u6027\\u5de5\\u5e8f","note":"","id":"0","real_table":"gx_fl"}', 'beforeSave', 'qygl.gx_fl'),
(289, '2016-04-21 12:59:26', 2, '{"db":"qygl","table":"gx_fl","parent":"0","cloneit":"false","name":"\\u975e\\u751f\\u4ea7\\u6027\\u5de5\\u5e8f","note":"","id":"0","real_table":"gx_fl"}', 'save', 'qygl.gx_fl'),
(290, '2016-04-22 12:43:22', 2, '{"container":"mainContent","db":"qygl","table":"zzvw_gys","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":40},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":183},\\"gender_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":183},\\"zhengjian_fl_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":150},\\"identity_no\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"credit_level_id\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"bank_account_no\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"account_receivable\\":{\\"order\\":7,\\"hidden\\":false,\\"width\\":183},\\"address\\":{\\"order\\":8,\\"hidden\\":true,\\"width\\":150},\\"hb_contact_method\\":{\\"order\\":9,\\"hidden\\":false,\\"width\\":183},\\"wz_id\\":{\\"order\\":10,\\"hidden\\":false,\\"width\\":183},\\"hobby_id\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":183},\\"lxr\\":{\\"order\\":12,\\"hidden\\":false,\\"width\\":183},\\"cell_no\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":183},\\"isactive\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":58}}","real_table":"hb"}', 'saveCookie', 'qygl.zzvw_gys'),
(291, '2016-04-25 11:38:43', 2, '{"container":"mainContent","db":"qygl","table":"wz","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":false,\\"width\\":39},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":98},\\"wz_fl_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":98},\\"unit_id\\":{\\"order\\":3,\\"hidden\\":false,\\"width\\":98},\\"unit_name\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"price1\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"min_kc1\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"max_kc1\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":150},\\"ck_weizhi_id1\\":{\\"order\\":8,\\"hidden\\":true,\\"width\\":150},\\"remained1\\":{\\"order\\":9,\\"hidden\\":true,\\"width\\":150},\\"pd_days1\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"zuhe\\":{\\"order\\":11,\\"hidden\\":false,\\"width\\":98},\\"wz_cp_zuhe\\":{\\"order\\":12,\\"hidden\\":false,\\"width\\":132},\\"jszb_wz\\":{\\"order\\":13,\\"hidden\\":false,\\"width\\":119},\\"gx_wz\\":{\\"order\\":14,\\"hidden\\":false,\\"width\\":108},\\"muju\\":{\\"order\\":15,\\"hidden\\":false,\\"width\\":98},\\"wz_sb\\":{\\"order\\":16,\\"hidden\\":false,\\"width\\":88},\\"jy_days\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":81},\\"midu\\":{\\"order\\":18,\\"hidden\\":false,\\"width\\":73},\\"tj\\":{\\"order\\":19,\\"hidden\\":false,\\"width\\":66},\\"bmj\\":{\\"order\\":20,\\"hidden\\":false,\\"width\\":55},\\"hb_id\\":{\\"order\\":21,\\"hidden\\":false,\\"width\\":98},\\"pic\\":{\\"order\\":22,\\"hidden\\":false,\\"width\\":60},\\"note\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":50},\\"isactive\\":{\\"order\\":24,\\"hidden\\":false,\\"width\\":31}}","real_table":"wz"}', 'saveCookie', 'qygl.wz'),
(292, '2016-04-25 11:39:58', 2, '{"container":"mainContent","db":"qygl","table":"wz","type":"display","content":"{\\"id\\":{\\"order\\":0,\\"hidden\\":true,\\"width\\":152},\\"name\\":{\\"order\\":1,\\"hidden\\":false,\\"width\\":422},\\"wz_fl_id\\":{\\"order\\":2,\\"hidden\\":false,\\"width\\":422},\\"unit_id\\":{\\"order\\":3,\\"hidden\\":true,\\"width\\":98},\\"unit_name\\":{\\"order\\":4,\\"hidden\\":true,\\"width\\":150},\\"price1\\":{\\"order\\":5,\\"hidden\\":true,\\"width\\":150},\\"min_kc1\\":{\\"order\\":6,\\"hidden\\":true,\\"width\\":150},\\"max_kc1\\":{\\"order\\":7,\\"hidden\\":true,\\"width\\":150},\\"ck_weizhi_id1\\":{\\"order\\":8,\\"hidden\\":true,\\"width\\":150},\\"remained1\\":{\\"order\\":9,\\"hidden\\":true,\\"width\\":150},\\"pd_days1\\":{\\"order\\":10,\\"hidden\\":true,\\"width\\":150},\\"zuhe\\":{\\"order\\":11,\\"hidden\\":true,\\"width\\":105},\\"wz_cp_zuhe\\":{\\"order\\":12,\\"hidden\\":true,\\"width\\":153},\\"jszb_wz\\":{\\"order\\":13,\\"hidden\\":true,\\"width\\":154},\\"gx_wz\\":{\\"order\\":14,\\"hidden\\":true,\\"width\\":157},\\"muju\\":{\\"order\\":15,\\"hidden\\":true,\\"width\\":158},\\"wz_sb\\":{\\"order\\":16,\\"hidden\\":true,\\"width\\":158},\\"jy_days\\":{\\"order\\":17,\\"hidden\\":false,\\"width\\":352},\\"midu\\":{\\"order\\":18,\\"hidden\\":true,\\"width\\":148},\\"tj\\":{\\"order\\":19,\\"hidden\\":true,\\"width\\":149},\\"bmj\\":{\\"order\\":20,\\"hidden\\":true,\\"width\\":137},\\"hb_id\\":{\\"order\\":21,\\"hidden\\":true,\\"width\\":270},\\"pic\\":{\\"order\\":22,\\"hidden\\":true,\\"width\\":200},\\"note\\":{\\"order\\":23,\\"hidden\\":false,\\"width\\":218},\\"isactive\\":{\\"order\\":24,\\"hidden\\":false,\\"width\\":127}}","real_table":"wz"}', 'saveCookie', 'qygl.wz'),
(293, '2016-04-26 10:54:34', 2, '{"db":"qygl","table":"hb","id":"1","real_table":"hb"}', 'get_info', 'qygl.hb'),
(294, '2016-04-26 10:54:39', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"2","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(295, '2016-04-26 13:15:22', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"2","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(296, '2016-04-26 13:15:24', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"1","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(297, '2016-04-26 13:15:26', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"0","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(298, '2016-04-26 13:15:27', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"1","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(299, '2016-04-26 13:16:17', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"0","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(300, '2016-04-26 13:16:19', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"1","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(301, '2016-04-26 13:17:36', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"2","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(302, '2016-04-26 13:17:38', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"1","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(303, '2016-04-26 13:17:41', 2, '{"db":"qygl","table":"zjzh","zj_fl_id":"2","real_table":"zjzh"}', 'get_zjzh_by_zj_fl', 'qygl.zjzh'),
(304, '2016-04-26 13:17:49', 2, '{"db":"qygl","table":"hb","id":"1","real_table":"hb"}', 'get_info', 'qygl.hb'),
(305, '2016-05-21 02:54:34', 2, '{"db":"qygl","table":"tj","parent":"0","from_date":"2016-05-21","end_date":"2016-05-21","id":"0","real_table":"tj"}', 'beforeSave', 'qygl.tj'),
(306, '2016-05-21 03:03:15', 2, '{"db":"qygl","table":"tj","parent":"0","from_date":"2016-05-21","end_date":"2016-05-21","id":"0","real_table":"tj"}', 'beforeSave', 'qygl.tj');

-- --------------------------------------------------------

--
-- 表的结构 `measurement_index`
--

CREATE TABLE `measurement_index` (
  `id` int(11) NOT NULL,
  `name` int(11) NOT NULL,
  `description` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `memorial_day`
--

CREATE TABLE `memorial_day` (
  `id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `memorial_day` date NOT NULL,
  `memorial_day_type_id` int(11) NOT NULL,
  `note` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `memorial_day_comment`
--

CREATE TABLE `memorial_day_comment` (
  `id` int(4) UNSIGNED NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `createrid` int(4) UNSIGNED NOT NULL DEFAULT '1',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `memorial_day_id` int(4) NOT NULL,
  `replyto` int(4) DEFAULT NULL,
  `emailto` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if email to the element owner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `memorial_day_type`
--

CREATE TABLE `memorial_day_type` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `role`
--

CREATE TABLE `role` (
  `id` int(4) NOT NULL,
  `name` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` char(255) COLLATE utf8_unicode_ci DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `role`
--

INSERT INTO `role` (`id`, `name`, `description`) VALUES
(1, 'admin', '系统管理员'),
(2, 'qygl', '企业管理'),
(3, 'workflow', '工作流程');

-- --------------------------------------------------------

--
-- 表的结构 `role_user`
--

CREATE TABLE `role_user` (
  `id` int(4) NOT NULL,
  `role_id` int(4) NOT NULL DEFAULT '1',
  `user_id` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `role_user`
--

INSERT INTO `role_user` (`id`, `role_id`, `user_id`) VALUES
(3, 1, 1),
(9, 1, 2),
(10, 2, 2);

-- --------------------------------------------------------

--
-- 表的结构 `status`
--

CREATE TABLE `status` (
  `id` int(4) NOT NULL,
  `name` char(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `description` char(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `status`
--

INSERT INTO `status` (`id`, `name`, `description`) VALUES
(1, 'active', ''),
(2, 'locked', '');

-- --------------------------------------------------------

--
-- 表的结构 `task`
--

CREATE TABLE `task` (
  `id` int(4) NOT NULL,
  `task_type_id` int(4) NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `action_type_id` int(4) NOT NULL DEFAULT '1',
  `task_priority_id` int(4) NOT NULL,
  `deadline` date NOT NULL,
  `progress` int(11) NOT NULL,
  `controller_id` int(11) NOT NULL,
  `task_result_id` int(11) DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `isactive` int(1) NOT NULL,
  `creater_id` int(4) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `task_priority`
--

CREATE TABLE `task_priority` (
  `id` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `task_result`
--

CREATE TABLE `task_result` (
  `id` int(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `task_type`
--

CREATE TABLE `task_type` (
  `id` int(4) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `note` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `test`
--

CREATE TABLE `test` (
  `a` int(11) NOT NULL,
  `b` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(4) NOT NULL,
  `username` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '202cb962ac59075b964b07152d234b70',
  `password_salt` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '123456',
  `nickname` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `company_id` int(11) DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status_id` int(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user information';

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `password_salt`, `nickname`, `email`, `company_id`, `created`, `status_id`) VALUES
(1, 'admin', '202cb962ac59075b964b07152d234b70', '123456', 'admin', 'admin@kuafusoft.com', 1, '2016-03-08 12:01:20', 1),
(2, 'yye', '202cb962ac59075b964b07152d234b70', '123456', '叶永利', 'kuafusoft@gmail.com', 1, '2016-04-09 03:37:46', 1);

-- --------------------------------------------------------

--
-- 表的结构 `user_config`
--

CREATE TABLE `user_config` (
  `id` int(4) UNSIGNED NOT NULL,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `database` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_cookie`
--

CREATE TABLE `user_cookie` (
  `id` int(4) UNSIGNED NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `type` char(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` char(50) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- 转存表中的数据 `user_cookie`
--

INSERT INTO `user_cookie` (`id`, `user_id`, `type`, `name`, `content`, `modified`) VALUES
(8, 1, 'rowNum', 'useradmin_company', '{"rowNum":"100"}', '2016-03-08 12:04:38'),
(12, 1, 'display', 'useradmin_user', '{"id":{"order":0,"hidden":true,"width":40},"username":{"order":1,"hidden":false,"width":222},"password":{"order":2,"hidden":false,"width":222},"password_salt":{"order":3,"hidden":false,"width":222},"nickname":{"order":4,"hidden":false,"width":222},"email":{"order":5,"hidden":false,"width":222},"company_id":{"order":6,"hidden":false,"width":222},"created":{"order":7,"hidden":true,"width":100},"status_id":{"order":8,"hidden":false,"width":224}}', '2016-03-08 12:22:33'),
(24, 1, 'rowNum', 'useradmin_group', '{"rowNum":"100"}', '2016-04-09 02:20:25'),
(34, 1, 'rowNum', 'useradmin_role', '{"rowNum":"100"}', '2016-04-09 05:31:21'),
(37, 1, 'rowNum', 'useradmin_user', '{"rowNum":"100"}', '2016-04-09 13:34:18'),
(42, 2, 'rowNum', 'xt_testcase', '{"rowNum":"100"}', '2016-04-11 11:33:47'),
(46, 2, 'rowNum', 'useradmin_role', '{"rowNum":"100"}', '2016-04-11 11:43:33'),
(48, 2, 'rowNum', 'qygl_zzvw_kh', '{"rowNum":"100"}', '2016-04-11 13:30:55'),
(57, 2, 'rowNum', 'xt_testcase_testpoint', '{"rowNum":"100"}', '2016-04-14 14:02:58'),
(77, 2, 'rowNum', 'xt_testcase_module', '{"rowNum":"100"}', '2016-04-14 14:39:07'),
(80, 2, 'rowNum', 'xt_zzvw_prj', '{"rowNum":"100"}', '2016-04-15 11:29:21'),
(92, 2, 'rowNum', 'xt_testcase_ver', '{"rowNum":"100"}', '2016-04-15 11:57:31'),
(164, 2, 'rowNum', 'qygl_hb_yg', '{"rowNum":"100"}', '2016-04-15 14:54:10'),
(197, 2, 'display', 'qygl_zzvw_yg', '{"id":{"order":0,"hidden":true,"width":40},"name":{"order":1,"hidden":false,"width":105},"gender_id":{"order":2,"hidden":false,"width":105},"zhengjian_fl_id":{"order":3,"hidden":true,"width":150},"identity_no":{"order":4,"hidden":true,"width":150},"credit_level_id":{"order":5,"hidden":true,"width":150},"bank_account_no":{"order":6,"hidden":true,"width":150},"init_date":{"order":7,"hidden":true,"width":33},"init_account_receivable":{"order":8,"hidden":false,"width":105},"account_receivable":{"order":9,"hidden":false,"width":99},"address":{"order":10,"hidden":true,"width":150},"hb_contact_method":{"order":11,"hidden":false,"width":154},"enter_date":{"order":12,"hidden":true,"width":34},"work_type_id":{"order":13,"hidden":false,"width":61},"dept_id":{"order":14,"hidden":false,"width":105},"position_id":{"order":15,"hidden":false,"width":105},"salary_fl_id":{"order":16,"hidden":false,"width":105},"base_salary":{"order":17,"hidden":false,"width":105},"ticheng_ratio":{"order":18,"hidden":false,"width":105},"baoxian_type_id":{"order":19,"hidden":false,"width":105},"baoxian_start_date":{"order":20,"hidden":true,"width":35},"baoxian_feiyong":{"order":21,"hidden":true,"width":86},"hb_skill":{"order":22,"hidden":false,"width":139},"hb_hobby_id":{"order":23,"hidden":false,"width":70},"lxr":{"order":24,"hidden":true,"width":97},"cell_no":{"order":25,"hidden":true,"width":91},"isactive":{"order":26,"hidden":false,"width":49}}', '2016-04-17 07:48:05'),
(222, 2, 'rowNum', 'qygl_unit_fl', '{"rowNum":"100"}', '2016-04-21 11:43:26'),
(234, 2, 'rowNum', 'qygl_zzvw_unit', '{"rowNum":"100"}', '2016-04-21 11:51:05'),
(244, 2, 'rowNum', 'qygl_hobby', '{"rowNum":"100"}', '2016-04-21 11:53:44'),
(245, 2, 'rowNum', 'qygl_credit_level', '{"rowNum":"100"}', '2016-04-21 11:53:46'),
(246, 2, 'rowNum', 'qygl_hb_fl', '{"rowNum":"100"}', '2016-04-21 11:53:49'),
(247, 2, 'rowNum', 'qygl_jszb', '{"rowNum":"100"}', '2016-04-21 11:53:53'),
(248, 2, 'rowNum', 'qygl_skill', '{"rowNum":"100"}', '2016-04-21 11:53:55'),
(249, 2, 'rowNum', 'qygl_skill_grade', '{"rowNum":"100"}', '2016-04-21 11:53:57'),
(250, 2, 'rowNum', 'qygl_dept', '{"rowNum":"100"}', '2016-04-21 11:53:58'),
(251, 2, 'rowNum', 'qygl_position', '{"rowNum":"100"}', '2016-04-21 11:54:00'),
(252, 2, 'rowNum', 'qygl_contact_method', '{"rowNum":"100"}', '2016-04-21 11:54:01'),
(253, 2, 'rowNum', 'qygl_work_type', '{"rowNum":"100"}', '2016-04-21 11:54:02'),
(254, 2, 'rowNum', 'qygl_zj_cause', '{"rowNum":"100"}', '2016-04-21 11:54:04'),
(255, 2, 'rowNum', 'qygl_zj_fl', '{"rowNum":"100"}', '2016-04-21 11:54:05'),
(256, 2, 'rowNum', 'qygl_gx_de', '{"rowNum":"100"}', '2016-04-21 11:54:10'),
(274, 2, 'rowNum', 'qygl_gx_fl', '{"rowNum":"100"}', '2016-04-21 12:59:26'),
(285, 2, 'rowNum', 'qygl_defect', '{"rowNum":"100"}', '2016-04-21 14:01:12'),
(288, 2, 'display', 'qygl_zzvw_gys', '{"id":{"order":0,"hidden":true,"width":40},"name":{"order":1,"hidden":false,"width":183},"gender_id":{"order":2,"hidden":false,"width":183},"zhengjian_fl_id":{"order":3,"hidden":true,"width":150},"identity_no":{"order":4,"hidden":true,"width":150},"credit_level_id":{"order":5,"hidden":true,"width":150},"bank_account_no":{"order":6,"hidden":true,"width":150},"account_receivable":{"order":7,"hidden":false,"width":183},"address":{"order":8,"hidden":true,"width":150},"hb_contact_method":{"order":9,"hidden":false,"width":183},"wz_id":{"order":10,"hidden":false,"width":183},"hobby_id":{"order":11,"hidden":false,"width":183},"lxr":{"order":12,"hidden":false,"width":183},"cell_no":{"order":13,"hidden":false,"width":183},"isactive":{"order":14,"hidden":false,"width":58}}', '2016-04-22 12:43:22'),
(313, 2, 'display', 'qygl_wz', '{"id":{"order":0,"hidden":true,"width":152},"name":{"order":1,"hidden":false,"width":422},"wz_fl_id":{"order":2,"hidden":false,"width":422},"unit_id":{"order":3,"hidden":true,"width":98},"unit_name":{"order":4,"hidden":true,"width":150},"price1":{"order":5,"hidden":true,"width":150},"min_kc1":{"order":6,"hidden":true,"width":150},"max_kc1":{"order":7,"hidden":true,"width":150},"ck_weizhi_id1":{"order":8,"hidden":true,"width":150},"remained1":{"order":9,"hidden":true,"width":150},"pd_days1":{"order":10,"hidden":true,"width":150},"zuhe":{"order":11,"hidden":true,"width":105},"wz_cp_zuhe":{"order":12,"hidden":true,"width":153},"jszb_wz":{"order":13,"hidden":true,"width":154},"gx_wz":{"order":14,"hidden":true,"width":157},"muju":{"order":15,"hidden":true,"width":158},"wz_sb":{"order":16,"hidden":true,"width":158},"jy_days":{"order":17,"hidden":false,"width":352},"midu":{"order":18,"hidden":true,"width":148},"tj":{"order":19,"hidden":true,"width":149},"bmj":{"order":20,"hidden":true,"width":137},"hb_id":{"order":21,"hidden":true,"width":270},"pic":{"order":22,"hidden":true,"width":200},"note":{"order":23,"hidden":false,"width":218},"isactive":{"order":24,"hidden":false,"width":127}}', '2016-04-25 11:39:58'),
(320, 2, 'rowNum', 'qygl_yw', '{"rowNum":"100"}', '2016-04-25 13:53:18'),
(332, 2, 'rowNum', 'qygl_unit', '{"rowNum":"ALL"}', '2016-04-25 14:01:23'),
(339, 2, 'rowNum', 'qygl_zzvw_yg', '{"rowNum":"100"}', '2016-05-07 15:22:31'),
(340, 2, 'rowNum', 'qygl_hb_contact_method', '{"rowNum":"ALL"}', '2016-05-07 15:22:31'),
(341, 2, 'rowNum', 'qygl_hb_skill', '{"rowNum":"ALL"}', '2016-05-07 15:22:31'),
(345, 2, 'rowNum', 'qygl_zzvw_yw_xd', '{"rowNum":"100"}', '2016-05-07 15:23:41'),
(347, 2, 'rowNum', 'qygl_zzvw_yw_yunshu', '{"rowNum":"100"}', '2016-05-07 15:24:03'),
(348, 2, 'rowNum', 'qygl_zzvw_yw_zj_zhifu', '{"rowNum":"100"}', '2016-05-07 15:24:15'),
(349, 2, 'rowNum', 'qygl_zzvw_yw_zj_hk', '{"rowNum":"100"}', '2016-05-07 15:24:17'),
(350, 2, 'rowNum', 'qygl_zzvw_yw_zj_huabo', '{"rowNum":"100"}', '2016-05-07 15:24:19'),
(351, 2, 'rowNum', 'qygl_zzvw_yw_zj_pj_tiexi', '{"rowNum":"100"}', '2016-05-07 15:24:20'),
(352, 2, 'rowNum', 'qygl_zjzh', '{"rowNum":"100"}', '2016-05-07 15:24:21'),
(353, 2, 'rowNum', 'qygl_zj_pj', '{"rowNum":"100"}', '2016-05-07 15:24:23'),
(354, 2, 'rowNum', 'qygl_wz', '{"rowNum":"100"}', '2016-05-07 15:24:28'),
(355, 2, 'rowNum', 'qygl_zzvw_pici', '{"rowNum":"100"}', '2016-05-07 15:24:30'),
(356, 2, 'rowNum', 'qygl_wz_fl', '{"rowNum":"100"}', '2016-05-07 15:24:32'),
(357, 2, 'rowNum', 'qygl_zl', '{"rowNum":"100"}', '2016-05-07 15:24:37'),
(358, 2, 'rowNum', 'qygl_gx', '{"rowNum":"100"}', '2016-05-07 15:24:39'),
(359, 2, 'rowNum', 'qygl_gx_input', '{"rowNum":"ALL"}', '2016-05-07 15:24:39'),
(360, 2, 'rowNum', 'qygl_gx_output', '{"rowNum":"ALL"}', '2016-05-07 15:24:39'),
(361, 2, 'rowNum', 'qygl_zzvw_gys', '{"rowNum":"100"}', '2016-05-10 10:43:09'),
(362, 2, 'rowNum', 'qygl_tj', '{"rowNum":"100"}', '2016-05-21 02:54:29'),
(363, 2, 'rowNum', 'qygl_zzvw_yw_scdj', '{"rowNum":"100"}', '2016-05-21 03:04:52'),
(369, 2, 'rowNum', 'qygl_zzvw_pici_scdj', '{"rowNum":"ALL"}', '2016-05-21 03:04:52');

-- --------------------------------------------------------

--
-- 表的结构 `user_message`
--

CREATE TABLE `user_message` (
  `id` int(4) UNSIGNED NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `reply_id` int(4) NOT NULL DEFAULT '0',
  `from` int(4) NOT NULL DEFAULT '0',
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `handled` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_report_to`
--

CREATE TABLE `user_report_to` (
  `id` int(10) UNSIGNED NOT NULL,
  `ps` text COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `manager_id` int(11) NOT NULL,
  `solid_line_manager_id` int(11) NOT NULL,
  `dotted_line_manager_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_subscribe`
--

CREATE TABLE `user_subscribe` (
  `id` int(4) UNSIGNED NOT NULL,
  `user_id` int(4) NOT NULL DEFAULT '0',
  `object` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `object_id` int(4) DEFAULT NULL,
  `oper` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- 表的结构 `user_task`
--

CREATE TABLE `user_task` (
  `id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `task_id` int(4) NOT NULL,
  `assigner_id` int(4) NOT NULL,
  `assign_type_id` int(4) NOT NULL,
  `task_result_id` int(4) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acl`
--
ALTER TABLE `acl`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `action_type`
--
ALTER TABLE `action_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assign_type`
--
ALTER TABLE `assign_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle`
--
ALTER TABLE `circle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `circle_user`
--
ALTER TABLE `circle_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company_config`
--
ALTER TABLE `company_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expe`
--
ALTER TABLE `expe`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expe_comment`
--
ALTER TABLE `expe_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expe_dept_id`
--
ALTER TABLE `expe_dept_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expe_level_id`
--
ALTER TABLE `expe_level_id`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expe_type`
--
ALTER TABLE `expe_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `functions`
--
ALTER TABLE `functions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `group`
--
ALTER TABLE `group`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `group_user`
--
ALTER TABLE `group_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `index_role`
--
ALTER TABLE `index_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_big_type`
--
ALTER TABLE `interest_big_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_rate`
--
ALTER TABLE `interest_rate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_type`
--
ALTER TABLE `interest_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interest_user`
--
ALTER TABLE `interest_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memorial_day`
--
ALTER TABLE `memorial_day`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memorial_day_comment`
--
ALTER TABLE `memorial_day_comment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `memorial_day_type`
--
ALTER TABLE `memorial_day_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_priority`
--
ALTER TABLE `task_priority`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_result`
--
ALTER TABLE `task_result`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_type`
--
ALTER TABLE `task_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `user_config`
--
ALTER TABLE `user_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_cookie`
--
ALTER TABLE `user_cookie`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_message`
--
ALTER TABLE `user_message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_report_to`
--
ALTER TABLE `user_report_to`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_subscribe`
--
ALTER TABLE `user_subscribe`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`object`,`object_id`);

--
-- Indexes for table `user_task`
--
ALTER TABLE `user_task`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `acl`
--
ALTER TABLE `acl`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'This table links the group, action and module. this table decides the access right of a group', AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `action_type`
--
ALTER TABLE `action_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `assign_type`
--
ALTER TABLE `assign_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `circle`
--
ALTER TABLE `circle`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `circle_user`
--
ALTER TABLE `circle_user`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `company_config`
--
ALTER TABLE `company_config`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `expe`
--
ALTER TABLE `expe`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `expe_comment`
--
ALTER TABLE `expe_comment`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `expe_dept_id`
--
ALTER TABLE `expe_dept_id`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `expe_level_id`
--
ALTER TABLE `expe_level_id`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `expe_type`
--
ALTER TABLE `expe_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `functions`
--
ALTER TABLE `functions`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `group`
--
ALTER TABLE `group`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- 使用表AUTO_INCREMENT `group_user`
--
ALTER TABLE `group_user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `index_role`
--
ALTER TABLE `index_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `interest_big_type`
--
ALTER TABLE `interest_big_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `interest_rate`
--
ALTER TABLE `interest_rate`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `interest_type`
--
ALTER TABLE `interest_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `interest_user`
--
ALTER TABLE `interest_user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=307;
--
-- 使用表AUTO_INCREMENT `memorial_day`
--
ALTER TABLE `memorial_day`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `memorial_day_comment`
--
ALTER TABLE `memorial_day_comment`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `memorial_day_type`
--
ALTER TABLE `memorial_day_type`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `role`
--
ALTER TABLE `role`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- 使用表AUTO_INCREMENT `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- 使用表AUTO_INCREMENT `status`
--
ALTER TABLE `status`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `task`
--
ALTER TABLE `task`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `task_priority`
--
ALTER TABLE `task_priority`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `task_result`
--
ALTER TABLE `task_result`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `task_type`
--
ALTER TABLE `task_type`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `user_config`
--
ALTER TABLE `user_config`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user_cookie`
--
ALTER TABLE `user_cookie`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370;
--
-- 使用表AUTO_INCREMENT `user_message`
--
ALTER TABLE `user_message`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user_report_to`
--
ALTER TABLE `user_report_to`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user_subscribe`
--
ALTER TABLE `user_subscribe`
  MODIFY `id` int(4) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- 使用表AUTO_INCREMENT `user_task`
--
ALTER TABLE `user_task`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
