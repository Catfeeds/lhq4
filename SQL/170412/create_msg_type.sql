/*
Navicat MySQL Data Transfer

Source Server         : backend
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-04-12 16:24:57
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `msg_type`
-- ----------------------------
DROP TABLE IF EXISTS `msg_type`;
CREATE TABLE `msg_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(100) CHARACTER SET utf8 NOT NULL COMMENT '类型名称',
  `create_time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1 COMMENT='消息类型';

-- ----------------------------
-- Records of msg_type
-- ----------------------------
INSERT INTO `msg_type` VALUES ('1', '新人须知（新闻公告）', '1491896701');
INSERT INTO `msg_type` VALUES ('2', '产品近况公告', '1491896868');
INSERT INTO `msg_type` VALUES ('3', '任务上线通知', '1491896886');
INSERT INTO `msg_type` VALUES ('4', '提现成功通知', '1491896903');
INSERT INTO `msg_type` VALUES ('5', '提现失败通知', '1491896914');
INSERT INTO `msg_type` VALUES ('6', '任务完成通知', '1491896925');
INSERT INTO `msg_type` VALUES ('7', '任务失败通知', '1491896937');
