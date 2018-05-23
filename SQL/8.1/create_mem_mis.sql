/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-08-01 18:10:51
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mem_mis
-- ----------------------------
DROP TABLE IF EXISTS `mem_mis`;
CREATE TABLE `mem_mis` (
  `member_id` int(11) DEFAULT NULL COMMENT '会员id',
  `mission_id` int(11) DEFAULT NULL COMMENT '任务id ',
  `status` tinyint(4) DEFAULT NULL COMMENT '任务状态',
  KEY `member_id` (`member_id`),
  KEY `mission_id` (`mission_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of mem_mis
-- ----------------------------
INSERT INTO `mem_mis` VALUES ('1', '1', '1');
INSERT INTO `mem_mis` VALUES ('1', '2', '2');
INSERT INTO `mem_mis` VALUES ('1', '3', '3');
