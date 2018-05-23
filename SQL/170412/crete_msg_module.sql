/*
Navicat MySQL Data Transfer

Source Server         : backend
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-04-12 16:23:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `msg_module`
-- ----------------------------
DROP TABLE IF EXISTS `msg_module`;
CREATE TABLE `msg_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `m_name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '标题',
  `m_content` text CHARACTER SET utf8 COMMENT '内容',
  `m_types` tinyint(2) DEFAULT NULL COMMENT '消息类型',
  `m_time` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 COMMENT='消息模板';

-- ----------------------------
-- Records of msg_module
-- ----------------------------
INSERT INTO `msg_module` VALUES ('1', '提现成功通知', '您的提现已经审核通过，预计几分后到达支付宝，请稍后查看。（ps:手机支付宝有信息延迟现象，请到电脑端查 看）', '4', '2017-04-12 09:30:25');
INSERT INTO `msg_module` VALUES ('2', '提现失败通知', '抱歉，您的提现审核失败，请联系官方客服。', '5', '2017-04-12 09:30:49');
INSERT INTO `msg_module` VALUES ('3', '任务上线提醒', '报告帮主，新任务?即将上线，订好闹钟，不要错过哦！', '3', '2017-04-12 09:31:48');
INSERT INTO `msg_module` VALUES ('4', '任务试玩成功通知', '你试玩的？任务成功，收益已经到达账户。具体试玩收益明细，请到【收支明细】里查看。', '6', '2017-04-12 09:33:11');
INSERT INTO `msg_module` VALUES ('5', '任务试玩失败通知', '因为没有遵循正确的试玩步骤，导致？任务不能返现，见谅！', '7', '2017-04-12 09:34:12');
