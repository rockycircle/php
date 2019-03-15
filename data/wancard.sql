-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2017-07-13 16:51:22
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wancard`
--

-- --------------------------------------------------------

--
-- 表的结构 `cms_category`
--

CREATE TABLE IF NOT EXISTS `cms_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(250) NOT NULL DEFAULT '',
  `state` char(10) NOT NULL DEFAULT '',
  `nav_id` int(11) NOT NULL DEFAULT '0' COMMENT '导航条',
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `cms_category`
--

INSERT INTO `cms_category` (`category_id`, `parent_id`, `name`, `description`, `state`, `nav_id`) VALUES
(1, 0, '测试类目', '', 'closed', 0),
(12, 11, '测试类目3', '', 'open', 0),
(11, 1, '测试类目2', '', 'closed', 0),
(13, 0, '我的栏目', '', 'closed', 0),
(14, 13, '我栏目测试', '', 'open', 0),
(16, 11, 'ces1', '', 'open', 0);

-- --------------------------------------------------------

--
-- 表的结构 `cms_content`
--

CREATE TABLE IF NOT EXISTS `cms_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `content_title` varchar(250) NOT NULL DEFAULT '' COMMENT '文章标题',
  `content_text` text NOT NULL COMMENT '内容',
  `content_last_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建日期时间戳',
  `content_last_date` date DEFAULT '0000-00-00' COMMENT '创建日期',
  `content_is_release` bigint(4) NOT NULL DEFAULT '0' COMMENT '文字是否发布 0：未发布，9：已发布',
  `content_release_date` date DEFAULT NULL COMMENT '文章发布日期',
  `content_release_time` int(11) NOT NULL DEFAULT '0' COMMENT '发布时间戳',
  `content_author` varchar(25) NOT NULL DEFAULT '' COMMENT '作者',
  `content_count` int(11) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `category_id` int(11) NOT NULL COMMENT '类目ID',
  `content_index_img` varchar(200) NOT NULL DEFAULT '' COMMENT '封面图片',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- 转存表中的数据 `cms_content`
--

INSERT INTO `cms_content` (`content_id`, `content_title`, `content_text`, `content_last_time`, `content_last_date`, `content_is_release`, `content_release_date`, `content_release_time`, `content_author`, `content_count`, `category_id`, `content_index_img`) VALUES
(22, '', '&lt;img src=&quot;http://127.0.0.1/wancard/Public/app/plugins/kindeditor-4.1.10/plugins/emoticons/images/0.gif&quot; border=&quot;0&quot; alt=&quot;&quot; /&gt;', 1499176236, '2017-07-04', 0, NULL, 0, '', 0, 0, '');

-- --------------------------------------------------------

--
-- 表的结构 `cms_navigation`
--

CREATE TABLE IF NOT EXISTS `cms_navigation` (
  `navi_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `navi_title` varchar(50) NOT NULL DEFAULT '' COMMENT '内容',
  `navi_link` varchar(200) NOT NULL DEFAULT '' COMMENT '链接',
  `navi_sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`navi_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '客户ID',
  `wx_code` varchar(254) NOT NULL COMMENT '微信CODE',
  `wx_img` varchar(254) NOT NULL COMMENT '微信头像',
  `seller_orders_num` int(11) NOT NULL COMMENT '出售订单数',
  `buy_orders_num` int(11) NOT NULL DEFAULT '0' COMMENT '购买订单数',
  `customer_credit_grade` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '信用分',
  `customer_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '可用余额',
  `customer_no_balance` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '冻结余额',
  `customer_create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建日期',
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `orders_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '产品基本ID',
  `orders_num` int(11) NOT NULL DEFAULT '0' COMMENT '订单数量',
  `orders_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总价',
  `orders_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单状态：1 待支付，2：待使用，3：纠纷中，8：已取消，9：已完成',
  `orders_create` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建日期',
  `orders_pay` varchar(50) NOT NULL DEFAULT '微信支付' COMMENT '支付方式',
  `orders_merchant_id` varchar(250) NOT NULL DEFAULT '' COMMENT '商户ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户ID',
  PRIMARY KEY (`orders_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `orders_product`
--

CREATE TABLE IF NOT EXISTS `orders_product` (
  `orders_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_user_id` int(11) NOT NULL DEFAULT '11' COMMENT '用户产品id',
  `category_id` int(11) NOT NULL DEFAULT '11' COMMENT '类目ID',
  `product_id` int(11) NOT NULL DEFAULT '11' COMMENT '产品ID',
  `product_title` varchar(254) NOT NULL DEFAULT '' COMMENT '产品名称',
  `product_origin_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '产品市场价',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '产品售价',
  `product_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '订单产品状态：1 待支付，2：待使用，3：纠纷中，8：已取消，9：已完成',
  `product_code` varchar(254) NOT NULL DEFAULT '' COMMENT '产品兑换码',
  `product_qr_code` varchar(254) NOT NULL DEFAULT '' COMMENT '二维码地址',
  `product_create_dayte` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建日期',
  PRIMARY KEY (`orders_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键',
  `product_title` varchar(255) NOT NULL DEFAULT '' COMMENT '产品标题',
  `product_info` text COMMENT '产品介绍',
  `product_origin_price` decimal(10,2) NOT NULL COMMENT '市场价',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `product_merchant` varchar(254) NOT NULL DEFAULT '' COMMENT '商户',
  `product_help` text NOT NULL COMMENT '使用说明',
  `product_from` varchar(254) NOT NULL DEFAULT '' COMMENT '商品来源',
  `product_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1：售出 9：下架',
  `product_use_address` varchar(254) NOT NULL DEFAULT '' COMMENT '商品适用门店',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目ID',
  `product_create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建日期',
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='产品表' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `product_category`
--

CREATE TABLE IF NOT EXISTS `product_category` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品类目ID',
  `category_title` varchar(255) NOT NULL DEFAULT '',
  `category_img` varchar(255) NOT NULL DEFAULT '',
  `category_pid` int(11) NOT NULL DEFAULT '0' COMMENT '父ID',
  `category_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态：1启用  9：关闭',
  `category_desc` text NOT NULL COMMENT '类目说明',
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- 转存表中的数据 `product_category`
--

INSERT INTO `product_category` (`category_id`, `category_title`, `category_img`, `category_pid`, `category_status`, `category_desc`) VALUES
(4, '测定', '/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg', 0, 1, '<p>\r\n	<img src="/wancard/Public/UploadFile/image/20170712/20170712210409_22189.jpg" alt="" />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	测试数据解析\r\n</p>'),
(5, '测定', '/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg', 0, 1, ''),
(6, '测定', '/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg', 0, 1, ''),
(8, '测定', '/wancard/Public/UploadFile/image/20170712/20170712210409_22189.jpg', 0, 1, '纯纯粹粹&lt;img src=&quot;/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg&quot; alt=&quot;&quot; /&gt;'),
(9, '我的类目', '/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg', 0, 1, '<img src="/wancard/Public/UploadFile/image/20170712/20170712210248_42000.jpg" alt="" />');

-- --------------------------------------------------------

--
-- 表的结构 `product_user`
--

CREATE TABLE IF NOT EXISTS `product_user` (
  `product_customer_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户产品ID',
  `product_id` int(11) NOT NULL DEFAULT '0',
  `product_title` varchar(254) NOT NULL DEFAULT '',
  `product_from` varchar(254) NOT NULL DEFAULT '',
  `product_desc` varchar(254) NOT NULL DEFAULT '' COMMENT '产品备注说明',
  `product_origin_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '市场价',
  `product_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
  `product_merchant` varchar(254) NOT NULL DEFAULT '' COMMENT '商户',
  `product_code` varchar(254) NOT NULL DEFAULT '' COMMENT '产品兑换码',
  `product_create_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '商品创建日期',
  `product_expire_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '商品过期日期',
  `product_info` text NOT NULL,
  `product_help` text NOT NULL,
  `product_use_address` varchar(254) NOT NULL DEFAULT '',
  `category_id` int(11) NOT NULL DEFAULT '0' COMMENT '类目ID',
  `customer_id` int(11) NOT NULL DEFAULT '0' COMMENT '客户ID',
  `product_status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1:出售中，2：已出售  3：纠纷中 9：取消',
  `product_qr_code` varchar(254) NOT NULL DEFAULT '' COMMENT '二维码',
  PRIMARY KEY (`product_customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `rbac_access`
--

CREATE TABLE IF NOT EXISTS `rbac_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `rbac_group`
--

CREATE TABLE IF NOT EXISTS `rbac_group` (
  `id` smallint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `title` varchar(50) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `show` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `rbac_group`
--

INSERT INTO `rbac_group` (`id`, `name`, `title`, `create_time`, `update_time`, `status`, `sort`, `show`) VALUES
(2, 'App', '应用中心', 1222841259, 0, 1, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_node`
--

CREATE TABLE IF NOT EXISTS `rbac_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `group_id` tinyint(3) unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=137 ;

--
-- 转存表中的数据 `rbac_node`
--

INSERT INTO `rbac_node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`, `type`, `group_id`) VALUES
(49, 'read', '查看', 1, '', NULL, 30, 3, 0, 0),
(39, 'index', '列表', 1, '', NULL, 30, 3, 0, 0),
(37, 'resume', '恢复', 1, '', NULL, 30, 3, 0, 0),
(91, 'add', '添加', 1, '添加', NULL, 90, 3, 0, 0),
(35, 'foreverdelete', '删除', 1, '', NULL, 30, 3, 0, 0),
(34, 'update', '更新', 1, '', NULL, 30, 3, 0, 0),
(33, 'edit', '编辑', 1, '', NULL, 30, 3, 0, 0),
(95, 'Student/student', '学生信息', 1, '学生信息', NULL, 94, 2, 0, 0),
(7, 'User/user', '后台用户', 1, '', 4, 1, 2, 0, 2),
(6, 'Role/role', '角色管理', 1, '', 3, 1, 2, 0, 2),
(2, 'Node/node', '节点管理', 1, '', 2, 1, 2, 0, 2),
(1, 'Rbac', '用户管理', 1, '', NULL, 0, 1, 0, 0),
(50, 'main', '空白首页', 1, '', NULL, 40, 3, 0, 0),
(93, 'Content', '内容管理', 1, '内容管理', NULL, 88, 2, 0, 0),
(92, 'insert', '添加', 1, '添加角色', NULL, 6, 3, 0, 0),
(89, 'Catalog/catalog', '栏目管理', 1, '栏目管理', NULL, 88, 2, 0, 0),
(131, 'Category/category', '类目管理', 1, '', NULL, 129, 2, 0, 0),
(96, 'School/college', '学部信息', 1, '班级信息', NULL, 94, 2, 0, 0),
(97, 'School/discipline', '专业信息', 1, '专业信息', NULL, 94, 2, 0, 0),
(130, 'Content/content', '内容列表', 1, '', NULL, 129, 2, 0, 0),
(102, 'Orders/info', '采购单信息', 1, '', NULL, 101, 2, 0, 0),
(104, 'System/info', '配置信息', 1, '', NULL, 103, 2, 0, 0),
(105, 'Orders/project', '项目信息', 1, '', NULL, 101, 2, 0, 0),
(106, 'School/classInfo', '班级信息', 1, '班级信息', NULL, 94, 2, 0, 0),
(121, 'Curriculum/curriculum', '课程信息', 1, '课程信息', NULL, 109, 2, 0, 0),
(112, 'Reportforms/statements', '供应商总报表', 1, '', NULL, 108, 2, 0, 0),
(113, 'Reportforms/supplierinfo', '供应商流水表', 1, '', NULL, 108, 2, 0, 0),
(114, 'User/passwd', '信息维护', 1, '', NULL, 1, 2, 0, 0),
(116, 'Product/stock', '材料库存', 1, '', NULL, 115, 2, 0, 0),
(117, 'Product/stockinfo', '材料出入库信息', 1, '', NULL, 115, 2, 0, 0),
(118, 'Product/stockrefund', '材料退款信息', 1, '', NULL, 115, 2, 0, 0),
(129, 'Content', '内容管理', 1, '', NULL, 0, 1, 0, 0),
(120, 'Curriculum/mgCurriculum', '专业课程安排', 1, '', NULL, 109, 2, 0, 0),
(123, 'TeachReview/myReviewInfo', '我的评价', 1, '我的评价', NULL, 122, 2, 0, 0),
(125, 'Curriculum/myCurriculum', '我的课程', 1, '我的课程', NULL, 109, 2, 0, 0),
(126, 'TeachReview/teachReview', '课程评价信息', 1, '我的评价', NULL, 122, 2, 0, 0),
(127, 'TeachReview/teachLabel', '评价标签', 1, '', NULL, 122, 2, 0, 0),
(128, 'TeachReview/teachreviewinfo', '教师评价信息', 1, '', NULL, 122, 2, 0, 0),
(133, 'Product', '产品管理', 1, '', NULL, 0, 1, 0, 0),
(134, 'ProductCatg/productCatg', '类目管理', 1, '', NULL, 133, 2, 0, 0),
(135, 'Product/product', '产品基本信息', 1, '', NULL, 133, 2, 0, 0),
(136, 'Product/prolist', '产品列表', 1, '', NULL, 133, 2, 0, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_role`
--

CREATE TABLE IF NOT EXISTS `rbac_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ename` varchar(5) DEFAULT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parentId` (`pid`),
  KEY `ename` (`ename`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- 转存表中的数据 `rbac_role`
--

INSERT INTO `rbac_role` (`id`, `name`, `pid`, `status`, `remark`, `ename`, `create_time`, `update_time`) VALUES
(9, '后台组', NULL, 1, '后台数据查阅', NULL, 1468147456, 0);

-- --------------------------------------------------------

--
-- 表的结构 `rbac_role_user`
--

CREATE TABLE IF NOT EXISTS `rbac_role_user` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` char(32) DEFAULT NULL,
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `rbac_role_user`
--

INSERT INTO `rbac_role_user` (`role_id`, `user_id`) VALUES
(9, '1');

-- --------------------------------------------------------

--
-- 表的结构 `rbac_user`
--

CREATE TABLE IF NOT EXISTS `rbac_user` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(64) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `password` char(32) NOT NULL,
  `last_login_time` int(11) unsigned DEFAULT '0',
  `last_login_ip` varchar(40) DEFAULT NULL,
  `login_count` mediumint(8) unsigned DEFAULT '0',
  `verify` varchar(32) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `remark` varchar(255) NOT NULL,
  `create_time` int(11) unsigned NOT NULL,
  `update_time` int(11) unsigned NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `type_id` tinyint(2) unsigned DEFAULT '0',
  `info` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `account` (`account`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `rbac_user`
--

INSERT INTO `rbac_user` (`id`, `account`, `nickname`, `password`, `last_login_time`, `last_login_ip`, `login_count`, `verify`, `email`, `remark`, `create_time`, `update_time`, `status`, `type_id`, `info`) VALUES
(1, 'admin', '管理员', '21232f297a57a5a743894a0e4a801fc3', 1499932930, '127.0.0.1', 1817, '8888', 'liu21st@gmail.com', '备注信息', 1222907803, 1326266696, 1, 0, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
