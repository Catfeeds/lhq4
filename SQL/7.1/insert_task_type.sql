/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-01 11:09:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for task_type
-- ----------------------------
DROP TABLE IF EXISTS `task_type`;
CREATE TABLE `task_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务 id',
  `task_name` varchar(50) NOT NULL COMMENT '任务名称',
  `create_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of task_type
-- ----------------------------
INSERT INTO `task_type` VALUES ('1', '免费任务', '1467342448');
INSERT INTO `task_type` VALUES ('2', '注册任务', '1467342456');
INSERT INTO `task_type` VALUES ('3', '付费任务', '1467342466');
INSERT INTO `task_type` VALUES ('4', '绑定微信', '1467342491');
INSERT INTO `task_type` VALUES ('5', '完善个人资料', '1467342511');
