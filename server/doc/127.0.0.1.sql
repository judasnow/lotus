-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2013 年 09 月 11 日 15:08
-- 服务器版本: 5.5.20
-- PHP 版本: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `lotus`
--
CREATE DATABASE `lotus` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `lotus`;

-- --------------------------------------------------------

--
-- 表的结构 `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '管理员编号',
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '登陆用户名',
  `password` int(32) NOT NULL COMMENT '登陆密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='管理员信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `invite`
--

CREATE TABLE IF NOT EXISTS `invite` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '被邀请卖家编号',
  `shop_owner_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '卖家的姓名',
  `shop_tel` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺联系方式',
  `shop_address` varchar(512) COLLATE utf8_unicode_ci NOT NULL COMMENT '卖家的店铺地址',
  `status` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'verifying' COMMENT ' 被邀请状态，默认审核中（verifying,verified）',
  `decision` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '审核结果（PASS,NOTPASS）',
  `register_code` varchar(8) COLLATE utf8_unicode_ci NOT NULL COMMENT '审核成功时的注册码',
  `notpass_message` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '审核未通过原因',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='保存卖家申请店铺的信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `product`
--

CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '产品编号',
  `class_a` int(11) NOT NULL COMMENT '产品一级分类信息',
  `class_b` int(11) NOT NULL COMMENT '产品二级分类信息',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品名称',
  `original_price` float NOT NULL COMMENT '产品原价',
  `discount` float DEFAULT NULL COMMENT '产品折扣',
  `quantity` int(11) NOT NULL COMMENT '产品数量',
  `image` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品的展示图片',
  `detail_image` varchar(512) COLLATE utf8_unicode_ci NOT NULL COMMENT '产品的细节图片',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='产品信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shop`
--

CREATE TABLE IF NOT EXISTS `shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店铺编号',
  `owner_id` int(11) NOT NULL COMMENT '店主的ID',
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺名称',
  `tel` varchar(11) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺联系方式',
  `image` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺图片',
  `address` varchar(512) COLLATE utf8_unicode_ci NOT NULL COMMENT '店铺地址',
  `show_shop_tel` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TRUE' COMMENT '显示店铺联系方式',
  `show_shop_address` varchar(16) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'TRUE' COMMENT '显示店铺地址',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='店铺信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(32) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户密码',
  `role` varchar(16) COLLATE utf8_unicode_ci NOT NULL COMMENT '用户角色（buyer,saler）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='普通用户信息表' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
