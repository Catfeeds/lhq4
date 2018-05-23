/*
Navicat MySQL Data Transfer

Source Server         : ddsd
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : diandian

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2017-09-18 17:55:48
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for osa_menu_url
-- ----------------------------
DROP TABLE IF EXISTS `osa_menu_url`;
CREATE TABLE `osa_menu_url` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_name` varchar(50) NOT NULL,
  `menu_url` varchar(255) NOT NULL,
  `module_id` int(11) NOT NULL,
  `is_show` tinyint(4) NOT NULL COMMENT '是否在sidebar里出现',
  `online` int(11) NOT NULL DEFAULT '1' COMMENT '在线状态，还是下线状态，即可用，不可用。',
  `shortcut_allowed` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '是否允许快捷访问',
  `menu_desc` varchar(255) DEFAULT NULL,
  `father_menu` int(11) NOT NULL DEFAULT '0' COMMENT '上一级菜单',
  PRIMARY KEY (`menu_id`),
  UNIQUE KEY `menu_url` (`menu_url`)
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8 COMMENT='功能链接（菜单链接）';

-- ----------------------------
-- Records of osa_menu_url
-- ----------------------------
INSERT INTO `osa_menu_url` VALUES ('1', '首页', '/Backend/', '1', '0', '1', '1', '后台首页', '0');
INSERT INTO `osa_menu_url` VALUES ('2', '账号列表', '/backend/panel/users', '1', '1', '1', '1', '账号列表', '0');
INSERT INTO `osa_menu_url` VALUES ('3', '修改账号', '/backend/panel/user_modify', '1', '0', '1', '0', '修改账号', '2');
INSERT INTO `osa_menu_url` VALUES ('4', '新建账号', '/backend/panel/user_add', '1', '0', '1', '1', '新建账号', '2');
INSERT INTO `osa_menu_url` VALUES ('5', '个人信息', '/backend/panel/profile', '1', '0', '1', '1', '个人信息', '0');
INSERT INTO `osa_menu_url` VALUES ('6', '账号组成员', '/panel/group.php', '1', '0', '1', '0', '显示账号组详情及该组成员', '7');
INSERT INTO `osa_menu_url` VALUES ('7', '账号组管理', '/backend/panel/groups', '1', '1', '1', '1', '增加管理员', '0');
INSERT INTO `osa_menu_url` VALUES ('8', '修改账号组', '/backend/panel/group_modify', '1', '0', '1', '0', '修改账号组', '7');
INSERT INTO `osa_menu_url` VALUES ('9', '新建账号组', '/backend/panel/group_add', '1', '0', '1', '1', '新建账号组', '7');
INSERT INTO `osa_menu_url` VALUES ('10', '权限管理', '/backend/panel/group_role', '1', '1', '1', '1', '用户权限依赖于账号组的权限', '0');
INSERT INTO `osa_menu_url` VALUES ('11', '菜单模块', '/backend/panel/modules', '1', '1', '1', '1', '菜单里的模块', '0');
INSERT INTO `osa_menu_url` VALUES ('12', '编辑菜单模块', '/backend/panel/module_modify', '1', '0', '1', '0', '编辑模块', '11');
INSERT INTO `osa_menu_url` VALUES ('13', '添加菜单模块', '/backend/panel/module_add', '1', '0', '1', '1', '添加菜单模块', '11');
INSERT INTO `osa_menu_url` VALUES ('14', '功能列表', '/backend/panel/menus', '1', '1', '1', '1', '菜单功能及可访问的链接', '0');
INSERT INTO `osa_menu_url` VALUES ('15', '增加功能', '/backend/panel/menu_add', '1', '0', '1', '1', '增加功能', '14');
INSERT INTO `osa_menu_url` VALUES ('16', '功能修改', '/backend/panel/menu_modify', '1', '0', '1', '0', '修改功能', '14');
INSERT INTO `osa_menu_url` VALUES ('17', '设置模板', '/backend/panel/set', '1', '0', '1', '1', '设置模板', '0');
INSERT INTO `osa_menu_url` VALUES ('18', '便签管理', '/panel/quicknotes.php', '1', '1', '1', '1', 'quick note', '0');
INSERT INTO `osa_menu_url` VALUES ('19', '菜单链接列表', '/backend/panel/module', '1', '0', '1', '0', '显示模块详情及该模块下的菜单', '11');
INSERT INTO `osa_menu_url` VALUES ('20', '登入', '/backend/login', '1', '0', '1', '1', '登入页面', '0');
INSERT INTO `osa_menu_url` VALUES ('21', '操作记录', '/backend/panel/syslog', '1', '1', '1', '1', '用户操作的历史行为', '0');
INSERT INTO `osa_menu_url` VALUES ('22', '系统信息', '/backend/panel/system', '1', '1', '1', '1', '显示系统相关信息', '0');
INSERT INTO `osa_menu_url` VALUES ('23', 'ajax访问修改快捷菜单', '/ajax/shortcut.php', '1', '0', '1', '0', 'ajax请求', '0');
INSERT INTO `osa_menu_url` VALUES ('24', '添加便签', '/panel/quicknote_add.php', '1', '0', '1', '1', '添加quicknote的内容', '18');
INSERT INTO `osa_menu_url` VALUES ('25', '修改便签', '/panel/quicknote_modify.php', '1', '0', '1', '0', '修改quicknote的内容', '18');
INSERT INTO `osa_menu_url` VALUES ('26', '系统设置', '/backend/panel/setting', '1', '0', '1', '0', '系统设置', '0');
INSERT INTO `osa_menu_url` VALUES ('101', '渠道配置信息', '/Backend/Channelconfig/channelConfig', '2', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('103', '广告主点击上报配置', '/Backend/Providerconfig/providerConfig', '2', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('105', '广告商激活回调日志', '/backend/Log/providerCallbackLog', '3', '1', '1', '1', '日志信息', '0');
INSERT INTO `osa_menu_url` VALUES ('106', '渠道商点击上报日志', '/backend/Log/channelClickLog', '3', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('107', '添加渠道配置信息', '/Backend/Channelconfig/channelConfig_add', '2', '0', '1', '1', '添加渠道配置信息', '101');
INSERT INTO `osa_menu_url` VALUES ('108', '添加广告商配置信息', '/Backend/Providerconfig/providerConfig_add', '2', '0', '1', '1', null, '103');
INSERT INTO `osa_menu_url` VALUES ('109', '修改渠道配置信息', '/Backend/Channelconfig/channelConfig_modify', '2', '0', '1', '1', null, '101');
INSERT INTO `osa_menu_url` VALUES ('110', '修改广告商配置信息', '/Backend/Providerconfig/providerConfig_modify', '2', '0', '1', '1', null, '103');
INSERT INTO `osa_menu_url` VALUES ('111', '渠道管理', '/Backend/Channel/channel', '2', '1', '1', '1', '管理渠道信息', '0');
INSERT INTO `osa_menu_url` VALUES ('112', '广告商管理', '/Backend/Provider/provider', '2', '1', '1', '1', '广告商信息管理', '0');
INSERT INTO `osa_menu_url` VALUES ('113', '广告应用管理', '/Backend/App/app', '2', '1', '1', '1', '应用信息管理', '0');
INSERT INTO `osa_menu_url` VALUES ('114', '添加渠道', '/Backend/Channel/channel_add', '2', '0', '1', '1', null, '111');
INSERT INTO `osa_menu_url` VALUES ('115', '修改渠道信息', '/Backend/Channel/channel_modify', '2', '0', '1', '1', null, '111');
INSERT INTO `osa_menu_url` VALUES ('116', '添加广告商', '/Backend/Provider/providerAdd', '2', '0', '1', '1', null, '112');
INSERT INTO `osa_menu_url` VALUES ('117', '修改广告商信息', '/Backend/Provider/providerModify', '2', '0', '1', '1', null, '112');
INSERT INTO `osa_menu_url` VALUES ('118', '添加应用', '/Backend/App/appAdd', '2', '0', '1', '1', null, '113');
INSERT INTO `osa_menu_url` VALUES ('119', '修改应用信息', '/Backend/App/appModify', '2', '0', '1', '1', null, '113');
INSERT INTO `osa_menu_url` VALUES ('121', '广告主报表', '/Backend/ProviderReportForm/providerReportForm', '4', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('123', '渠道报表', '/Backend/ChannelReportForm/channelReportForm', '4', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('124', '渠道激活回调日志', '/backend/Log/channelCallbackLog', '3', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('125', '媒介渠道', '/Backend/Channelres/channelRes', '5', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('126', '商务产品', '/Backend/Productres/productRes', '5', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('127', '添加媒介渠道', '/Backend/Channelres/channelRes_add', '5', '0', '1', '1', null, '125');
INSERT INTO `osa_menu_url` VALUES ('128', '修改媒介渠道信息', '/Backend/Channelres/channelRes_modify', '5', '0', '1', '1', null, '125');
INSERT INTO `osa_menu_url` VALUES ('130', '添加商务产品', '/Backend/Productres/productResAdd', '5', '0', '1', '1', null, '126');
INSERT INTO `osa_menu_url` VALUES ('131', '修改商务产品信息', '/Backend/Productres/productResModify', '5', '0', '1', '1', null, '126');
INSERT INTO `osa_menu_url` VALUES ('132', '导出数据', '/backend/Log/output', '3', '1', '1', '1', null, '0');
INSERT INTO `osa_menu_url` VALUES ('133', '任务分类', '/Backend/Task/task', '2', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('134', '添加任务分类', '/Backend/Task/taskAdd', '2', '0', '1', '1', '', '133');
INSERT INTO `osa_menu_url` VALUES ('135', '修改任务分类', '/Backend/Task/taskModify', '2', '0', '1', '1', '', '133');
INSERT INTO `osa_menu_url` VALUES ('136', '广告分类', '/Backend/Adtype/adType', '2', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('137', '添加广告分类', '/Backend/Adtype/adTypeAdd', '2', '0', '1', '1', '', '136');
INSERT INTO `osa_menu_url` VALUES ('138', '修改广告分类', '/Backend/Adtype/adTypeModify', '2', '0', '1', '1', '', '136');
INSERT INTO `osa_menu_url` VALUES ('139', '会员列表', '/Backend/Member/member', '6', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('141', '会员关系列表', '/Backend/Member/memberRel', '6', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('143', '查看详情', '/Backend/Member/memberDetails', '6', '0', '1', '1', '', '141');
INSERT INTO `osa_menu_url` VALUES ('144', '会员统计', '/Backend/Member/stat', '6', '1', '1', '1', '', '139');
INSERT INTO `osa_menu_url` VALUES ('145', '任务详情', '/Backend/Member/taskDetails', '6', '0', '1', '1', '', '139');
INSERT INTO `osa_menu_url` VALUES ('146', '收益明细', '/Backend/Member/mem_incomeDetails', '6', '0', '1', '1', '', '139');
INSERT INTO `osa_menu_url` VALUES ('147', 'ajax处理', '/ajax/myajax.php ', '6', '0', '1', '1', 'ajax', '139');
INSERT INTO `osa_menu_url` VALUES ('148', '计划列表', '/Backend/Mission/mission', '2', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('149', '计划添加', '/Backend/Mission/missionAdd', '2', '0', '1', '1', '', '157');
INSERT INTO `osa_menu_url` VALUES ('150', '计划编辑', '/Backend/Mission/missionModify', '2', '0', '1', '1', '', '157');
INSERT INTO `osa_menu_url` VALUES ('151', '渠道应用列表', '/Backend/Channel/channelList', '2', '0', '1', '1', '渠道所有应用列表', '111');
INSERT INTO `osa_menu_url` VALUES ('152', '广告财务列表', '/Backend/Finance/adFinancial', '7', '1', '1', '1', '广告财务列表', '0');
INSERT INTO `osa_menu_url` VALUES ('153', '财务列表', '/Backend/Finance/financeList', '7', '1', '1', '1', '财务列表', '0');
INSERT INTO `osa_menu_url` VALUES ('154', '平台用户钱包列表', '/backend/usersPurse.php', '7', '0', '1', '1', '平台用户钱包列表', '0');
INSERT INTO `osa_menu_url` VALUES ('155', '提现列表', '/Backend/Finance/drawings', '7', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('156', '财务审核', '/Backend/Finance/audit', '7', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('157', '财务审核状态修改', '/ajax/drawingAjax.php', '7', '0', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('158', '广告的财务列表', '/Backend/Finance/adFinancialList', '7', '0', '1', '1', '查看明细', '165');
INSERT INTO `osa_menu_url` VALUES ('159', '钱包明细列表', '/Backend/Finance/fin_incomeDetails', '7', '0', '1', '1', '查看明细', '166');
INSERT INTO `osa_menu_url` VALUES ('160', '平台钱包明细列表', '/backend/purseList.php', '7', '0', '1', '1', '查看明细', '167');
INSERT INTO `osa_menu_url` VALUES ('161', '首页', '/mobile/index.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('162', '关于', '/mobile/about.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('163', '绑定手机', '/mobile/bindmobile.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('164', '绑定微信', '/mobile/bindweixin.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('165', '账单', '/mobile/books.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('166', '交易', '/mobile/busniss.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('167', '秘籍', '/mobile/cheats.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('168', '资料', '/mobile/details.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('169', '兑换', '/mobile/exchange.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('170', '邀请', '/mobile/invite.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('171', '更多', '/mobile/more.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('172', '我的', '/mobile/my.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('173', '消息', '/mobile/news.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('174', '问题', '/mobile/problem.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('175', '服务', '/mobile/service.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('176', '分享', '/mobile/share.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('177', '任务', '/mobile/task.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('178', '提现', '/mobile/tixian.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('179', '试玩', '/mobile/try.php', '8', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('180', '财务审核状态修改', '/ajax/statusAjax2.php', '7', '0', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('181', '提交审核', '/ajax/heajax.php ', '8', '0', '1', '1', '提交审核', '0');
INSERT INTO `osa_menu_url` VALUES ('182', '时间倒计时', ' /ajax/sjajax.php ', '8', '0', '1', '1', '时间倒计时', '0');
INSERT INTO `osa_menu_url` VALUES ('183', '取消任务', '/ajax/qxStatusAjax.php ', '8', '0', '1', '1', '取消任务', '0');
INSERT INTO `osa_menu_url` VALUES ('184', '验证码', '/ajax/yzmAjax.php', '8', '0', '1', '1', '验证码', '0');
INSERT INTO `osa_menu_url` VALUES ('185', '二维码', '/mobile/qrcode.php', '8', '0', '1', '1', '二维码', '0');
INSERT INTO `osa_menu_url` VALUES ('186', '短信验证', '/ajax/sendvercode.php\r\n', '8', '0', '1', '1', '短信验证', '0');
INSERT INTO `osa_menu_url` VALUES ('187', '进度表', '/Backend/Message/mem_mis', '8', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('194', '微信消息回复', '/Backend/Message/message_shows', '8', '1', '1', '1', '', '0');
INSERT INTO `osa_menu_url` VALUES ('195', '编辑回复', '/Backend/Message/reply', '8', '0', '1', '1', '', '194');
INSERT INTO `osa_menu_url` VALUES ('196', '排重配置信息', '/Backend/Pcconfig/pc_config', '2', '1', '1', '1', ' ', '0');
INSERT INTO `osa_menu_url` VALUES ('197', '修改排重配置信息', '/Backend/Pcconfig/pcConfig_modify', '2', '0', '1', '1', '', '223');
INSERT INTO `osa_menu_url` VALUES ('198', '添加排重配置信息', '/Backend/Pcconfig/pcConfig_add', '2', '0', '1', '1', ' ', '223');
INSERT INTO `osa_menu_url` VALUES ('199', '排重日志', '/backend/Log/duplicateLog', '3', '1', '1', '1', '排重信息', '0');
INSERT INTO `osa_menu_url` VALUES ('204', '渠道激活上报日志', '/backend/Log/channelActiveLog', '3', '1', '1', '1', '渠道激活上报日志', '0');
INSERT INTO `osa_menu_url` VALUES ('205', '通知中心', '/Backend/Message/message', '8', '1', '1', '1', '消息通知', '0');
INSERT INTO `osa_menu_url` VALUES ('206', '消息添加', '/Backend/Message/message_add', '8', '0', '1', '1', '添加消息', '234');
INSERT INTO `osa_menu_url` VALUES ('207', '修改消息', '/Backend/Message/message_modify', '8', '0', '1', '1', '修改消息', '234');
INSERT INTO `osa_menu_url` VALUES ('208', '选择发送人', '/Backend/Message/msg_add', '8', '0', '1', '1', '选择发送人', '234');
INSERT INTO `osa_menu_url` VALUES ('209', '消息日志', '/backend/Log/msgLog', '3', '1', '1', '1', '消息状态', '0');
INSERT INTO `osa_menu_url` VALUES ('210', '广告主激活上报配置', '/Backend/Providerconfig/activeConfig', '2', '1', '1', '1', '广告主激活上报配置', '0');
INSERT INTO `osa_menu_url` VALUES ('211', '修改广告主激活上报配置', '/Backend/Providerconfig/activeConfig_modify', '2', '0', '1', '1', '修改广告主激活上报配置', '210');
INSERT INTO `osa_menu_url` VALUES ('212', '添加广告主激活上报配置', '/Backend/Providerconfig/activeConfig_add', '2', '0', '1', '1', '添加广告主激活上报配置', '210');
INSERT INTO `osa_menu_url` VALUES ('213', '激活页面显示', '/Backend/IsShowActive/index', '8', '1', '1', '1', '激活页面提示', '0');
INSERT INTO `osa_menu_url` VALUES ('214', '消息类型列表', '/Backend/MsgType/msgType', '8', '1', '1', '1', '消息', '0');
INSERT INTO `osa_menu_url` VALUES ('215', '消息类型添加', '/Backend/MsgType/msgTypeAdd', '8', '0', '1', '1', '添加', '0');
INSERT INTO `osa_menu_url` VALUES ('216', '消息类型修改', '/Backend/MsgType/msgTypeModify', '8', '0', '1', '1', '修改', '0');
INSERT INTO `osa_menu_url` VALUES ('217', '消息模板列表', '/Backend/Message/msgModule', '8', '1', '1', '1', '消息', '0');
INSERT INTO `osa_menu_url` VALUES ('218', '消息模板添加', '/Backend/Message/msgModule_add', '8', '0', '1', '1', '添加', '0');
INSERT INTO `osa_menu_url` VALUES ('219', '消息模板修改', '/Backend/Message/msgModule_modify', '8', '0', '1', '1', '修改', '0');
INSERT INTO `osa_menu_url` VALUES ('220', '版本url控制', '/Backend/VersionCheck/index', '8', '1', '1', '1', '对不同版本url的控制', '0');
INSERT INTO `osa_menu_url` VALUES ('221', '标签管理', '/Backend/Label/label', '2', '1', '1', '1', '标签', '0');
INSERT INTO `osa_menu_url` VALUES ('222', '标签添加', '/Backend/Label/labelAdd', '2', '0', '1', '1', '添加', '0');
INSERT INTO `osa_menu_url` VALUES ('223', '标签修改', '/Backend/Label/labelModify', '2', '0', '1', '1', '修改', '0');
