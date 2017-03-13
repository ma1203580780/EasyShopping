/*
 Navicat Premium Data Transfer

 Source Server         : docker
 Source Server Type    : MySQL
 Source Server Version : 50717
 Source Host           : localhost
 Source Database       : exam

 Target Server Type    : MySQL
 Target Server Version : 50717
 File Encoding         : utf-8

 Date: 03/13/2017 10:05:58 AM
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `data_good`
-- ----------------------------
DROP TABLE IF EXISTS `data_good`;
CREATE TABLE `data_good` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品表',
  `name` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '商品名称',
  `price` int(20) DEFAULT NULL COMMENT '价格（单位：分）',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态 1启用  2禁用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
--  Records of `data_good`
-- ----------------------------
BEGIN;
INSERT INTO `data_good` VALUES ('1', '健力宝', '240', '1'), ('8', '纸抽', '300', '1'), ('9', '塑料袋100个', '43', '1'), ('10', '瓶装水', '123', '1'), ('11', 'iMac', '780000', '1'), ('12', '《nodejs实战》', '6900', '1'), ('13', '鼠标', '4000', '1'), ('14', '测试机', '120000', '1'), ('15', '红领巾', '300', '1'), ('16', '数据线', '800', '1'), ('17', '六味地黄丸', '2000', '1'), ('18', 'iPad Pro', '480000', '1'), ('19', '玻璃纸', '500', '1'), ('20', '书架', '200', '1'), ('21', '阿里云ECS（低配版）每月', '6000', '1'), ('22', '腾讯会员/月', '1000', '1'), ('23', '签字笔', '700', '1'), ('24', '台球桌', '399900', '1'), ('25', '耳机', '120000', '1'), ('26', 'kindle', '90000', '1'), ('27', '胶带', '200', '1');
COMMIT;

-- ----------------------------
--  Table structure for `data_plan`
-- ----------------------------
DROP TABLE IF EXISTS `data_plan`;
CREATE TABLE `data_plan` (
  `guid` char(32) NOT NULL COMMENT '采购计划表',
  `pay` int(11) DEFAULT NULL COMMENT '预算（单位：分）',
  `goods_count_min` int(11) DEFAULT NULL COMMENT '商品总数(最少)',
  `goods_count_max` int(11) DEFAULT NULL COMMENT '商品总数(最多)',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态 1可用 2停用',
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Table structure for `data_users`
-- ----------------------------
DROP TABLE IF EXISTS `data_users`;
CREATE TABLE `data_users` (
  `guid` char(32) NOT NULL COMMENT '采购系统用户表',
  `sex` int(2) DEFAULT NULL COMMENT '性别 1男 2女',
  `email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `username` varchar(20) DEFAULT '' COMMENT '用户名',
  `phone` varchar(12) DEFAULT NULL COMMENT '手机号',
  `password` varchar(100) DEFAULT '' COMMENT '密码',
  `status` int(11) DEFAULT '1' COMMENT '状态 1可用 ； 2禁用',
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `data_users`
-- ----------------------------
BEGIN;
INSERT INTO `data_users` VALUES ('1b1b2814062b11e7a487409b0ee1409e', null, null, '', '13617191021', '6954b89a4222d991be505401ae9f07f4', '1'), ('404f2814061d11e799021a5b72ec201d', null, null, '', '15350598259', '6954b89a4222d991be505401ae9f07f4', '1');
COMMIT;

-- ----------------------------
--  Table structure for `rel_plan`
-- ----------------------------
DROP TABLE IF EXISTS `rel_plan`;
CREATE TABLE `rel_plan` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT '采购计划的关联表（商品信息）',
  `plan_guid` char(32) DEFAULT NULL COMMENT '关联计划表id',
  `master_good_id` int(10) DEFAULT NULL COMMENT '关联商品id',
  `rel_good_id` int(11) DEFAULT NULL COMMENT '主商品关联的比例商品的good_id',
  `proportion` varchar(50) DEFAULT NULL COMMENT '采购比例：主商品采购3件，关联商品必须采购两件（eg：3/2）',
  `good_count_min` int(10) DEFAULT '0' COMMENT '单品最小购买数量',
  `good_count_max` int(12) DEFAULT '1000000000' COMMENT '单件商品最大采购数',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `rel_plan`
-- ----------------------------
BEGIN;
INSERT INTO `rel_plan` VALUES ('16', 'dcefb3ea078611e799f4971bc74f2028', '15', '1', '2/1', '1', '4');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
