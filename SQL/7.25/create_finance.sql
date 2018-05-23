/*
Navicat MySQL Data Transfer

Source Server         : 123
Source Server Version : 50520
Source Host           : localhost:3306
Source Database       : adbe

Target Server Type    : MYSQL
Target Server Version : 50520
File Encoding         : 65001

Date: 2016-07-25 10:36:10
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for finance
-- ----------------------------
DROP TABLE IF EXISTS `finance`;
CREATE TABLE `finance` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '财务id',
  `t_number` int(11) DEFAULT NULL COMMENT '交易号',
  `widthdraw_money` float DEFAULT NULL COMMENT '提现金额',
  `paid_money` float DEFAULT NULL COMMENT '打款金额',
  `offer` tinyint(4) DEFAULT NULL COMMENT '是否开发票',
  `invoice_title` varchar(100) DEFAULT NULL COMMENT '发票头',
  `account` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '开户人',
  `bank_account` varchar(255) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT '银行账户',
  `address` varchar(255) DEFAULT NULL COMMENT '开户地址',
  `apply_time` datetime DEFAULT NULL COMMENT '申请时间',
  `end_time` datetime DEFAULT NULL COMMENT '完结时间',
  `status` tinyint(4) DEFAULT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='财务表';

-- ----------------------------
-- Records of finance
-- ----------------------------
