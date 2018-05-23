/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-01 09:57:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ad_type
-- ----------------------------
DROP TABLE IF EXISTS `ad_type`;
CREATE TABLE `ad_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '任务id',
  `task_name` varchar(50) NOT NULL COMMENT '任务名称',
  `create_time` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
