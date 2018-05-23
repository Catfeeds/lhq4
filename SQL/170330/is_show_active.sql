/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : diandian

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2017-03-30 16:49:30
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for is_show_active
-- ----------------------------
DROP TABLE IF EXISTS `is_show_active`;
CREATE TABLE `is_show_active` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否开启激活页面',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='激活页面表';

-- ----------------------------
-- Records of is_show_active
-- ----------------------------
INSERT INTO `is_show_active` VALUES ('1', '0');
