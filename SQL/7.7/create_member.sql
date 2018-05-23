/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-07 14:24:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '会员id',
  `pid` int(11) NOT NULL COMMENT '邀请人id',
  `idfa` varchar(100) DEFAULT NULL COMMENT 'IDFA',
  `member_name` varchar(50) NOT NULL COMMENT '会员名',
  `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
  `pic` varchar(100) DEFAULT NULL COMMENT '头像',
  `age` tinyint(4) DEFAULT NULL COMMENT '年龄',
  `sex` varchar(10) DEFAULT NULL COMMENT '性别',
  `country` varchar(20) DEFAULT NULL COMMENT '国家',
  `city` varchar(20) DEFAULT NULL COMMENT '城市',
  `user_num` varchar(25) DEFAULT NULL COMMENT '会员编号',
  `phone` varchar(50) DEFAULT NULL COMMENT '电话',
  `open_udid` varchar(200) DEFAULT NULL COMMENT 'open_udid',
  `income` float DEFAULT NULL COMMENT '总收入',
  `balance` float DEFAULT NULL COMMENT '账号余额',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `source` varchar(100) DEFAULT NULL COMMENT '用户来源',
  `add_time` datetime DEFAULT NULL COMMENT '加入时间',
  `ip` varchar(32) DEFAULT NULL COMMENT '加入ip',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of member
-- ----------------------------
INSERT INTO `member` VALUES ('1', '1', '3D32ED3EE5-9968-4F25-A015-DE3CFF569577', '张三', 'swd', '', '12', '男', null, null, '0000010012', '18333272473', '', '122.5', '30', '0', '', '2016-07-03 12:22:12', '');
INSERT INTO `member` VALUES ('2', '1', 'F814CC44-6186-4807-8524-0897BCE5A228', '张三2', null, null, null, null, null, null, '0000010013', '18333322473', '', '122.5', '30', '0', '', '2016-07-05 12:22:12', '');
INSERT INTO `member` VALUES ('3', '2', 'A2FAFA1F-39B0-725E-0973-08CB819C9E12', '张三3', null, null, null, null, null, null, '0000010013', '18334566543', '', '122.5', '30', '0', '', '2016-07-07 12:22:12', '');
