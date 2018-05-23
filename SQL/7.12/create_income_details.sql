/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-12 17:11:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for income_details
-- ----------------------------
DROP TABLE IF EXISTS `income_details`;
CREATE TABLE `income_details` (
  `income_id` int(11) NOT NULL AUTO_INCREMENT COMMENT ' ',
  `member_id` int(11) DEFAULT NULL COMMENT '用户id',
  `income` float DEFAULT NULL COMMENT '收益',
  `time` datetime NOT NULL COMMENT '时间',
  `mission_id` int(11) DEFAULT NULL COMMENT '任务id',
  `income_type` varchar(20) DEFAULT NULL COMMENT '收益类型',
  PRIMARY KEY (`income_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='收益明细';

-- ----------------------------
-- Records of income_details
-- ----------------------------
INSERT INTO `income_details` VALUES ('1', '1', '21', '2016-07-03 12:22:12', '1', '完成任务');
INSERT INTO `income_details` VALUES ('2', '2', '21', '2016-07-13 12:22:12', '2', '完成任务');
INSERT INTO `income_details` VALUES ('3', '3', '21', '2016-07-15 12:22:12', '1', '完成任务');
INSERT INTO `income_details` VALUES ('4', '2', '21', '2016-07-23 12:22:12', '3', '完成任务');
