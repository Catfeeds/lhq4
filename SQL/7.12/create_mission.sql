/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-12 17:11:12
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for mission
-- ----------------------------
DROP TABLE IF EXISTS `mission`;
CREATE TABLE `mission` (
  `mission_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `mission_name` varchar(50) DEFAULT NULL COMMENT '任务名称',
  `kwd` varchar(50) DEFAULT NULL COMMENT '关键字',
  `app_id` int(11) DEFAULT NULL COMMENT '广告id',
  `price` float DEFAULT NULL COMMENT '价格',
  `num` int(11) DEFAULT NULL COMMENT '数量',
  `channel_id` varchar(11) DEFAULT NULL COMMENT '渠道选择',
  `start_time` datetime DEFAULT NULL COMMENT '开始时间',
  `end_time` datetime DEFAULT NULL COMMENT '结束时间',
  `scale` float DEFAULT NULL COMMENT '回调比例',
  PRIMARY KEY (`mission_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='任务表';

-- ----------------------------
-- Records of mission
-- ----------------------------
INSERT INTO `mission` VALUES ('1', '途牛', '土木', '4', '34', '20', '2', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '50');
INSERT INTO `mission` VALUES ('2', '消消乐', '土木', '5', '34', '20', '2', '2016-07-03 12:22:12', '2016-07-13 12:22:12', '50');
