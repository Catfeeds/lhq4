/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-29 09:23:09
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for drawing
-- ----------------------------
DROP TABLE IF EXISTS `drawing`;
CREATE TABLE `drawing` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `member_id` int(11) DEFAULT NULL COMMENT '用户id',
  `alipy` varchar(100) DEFAULT NULL COMMENT '微信昵称、支付宝账户',
  `phone` varchar(50) DEFAULT NULL COMMENT '手机',
  `alipy_name` varchar(50) DEFAULT NULL COMMENT '支付宝姓名',
  `wd_money` float DEFAULT NULL COMMENT '提款金额',
  `wd_way` tinyint(4) DEFAULT NULL COMMENT '提现方式',
  `wd_time` datetime DEFAULT NULL COMMENT '提款时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='提款';

-- ----------------------------
-- Records of drawing
-- ----------------------------
INSERT INTO `drawing` VALUES ('1', '1', 'hgaogha', '13456322111', 'hhshhh', '23', null, '2016-07-25 00:00:00', '1');
INSERT INTO `drawing` VALUES ('5', '2', 'hgaogha', '13456322111', '卡视角', '23', null, '2016-07-25 00:00:00', '1');
INSERT INTO `drawing` VALUES ('6', '1', 'hgha', '134522111', 'hhshhh', '243', null, '2016-06-25 00:00:00', '1');
INSERT INTO `drawing` VALUES ('7', '1', 'hgaogha', '13456322111', 'h哈女sh', '23', null, '2016-07-25 00:00:00', '2');
INSERT INTO `drawing` VALUES ('8', '3', 'hgaogha', '134322111', '几十块h', '123', null, '2016-08-25 00:00:00', '2');
