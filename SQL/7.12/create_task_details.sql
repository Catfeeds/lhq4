/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-12 17:11:45
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for task_details
-- ----------------------------
DROP TABLE IF EXISTS `task_details`;
CREATE TABLE `task_details` (
  `detail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `app_id` int(11) DEFAULT NULL COMMENT '广告id',
  `mission_id` int(11) NOT NULL COMMENT '所属计划id',
  `price` float DEFAULT NULL COMMENT '广告价格',
  `member_id` int(11) DEFAULT NULL COMMENT '用户id',
  `start_time` datetime DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `status` varchar(20) DEFAULT NULL COMMENT '状态',
  `ip` varchar(100) DEFAULT NULL COMMENT 'ip',
  PRIMARY KEY (`detail_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='任务详情表';

-- ----------------------------
-- Records of task_details
-- ----------------------------
INSERT INTO `task_details` VALUES ('1', '7', '1', '2.5', '1', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '进行中', '');
INSERT INTO `task_details` VALUES ('2', '8', '2', '2.5', '1', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '进行中', '');
INSERT INTO `task_details` VALUES ('3', '4', '1', '2.5', '2', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '已完成', '');
INSERT INTO `task_details` VALUES ('4', '5', '2', '2.5', '1', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '已作废', '');
