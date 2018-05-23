CREATE TABLE `customer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '消息ID',
  `member_id` bigint(20) NOT NULL COMMENT 'member_id',
  `nickname` char(50) NOT NULL COMMENT '消息发送者',
  `message` varchar(200) NOT NULL COMMENT '消息体',
  `time_stamp` datetime NOT NULL COMMENT '消息发送时间',
  `reply` varchar(200) NOT NULL COMMENT '回复的消息',
  `time_reply` datetime NOT NULL COMMENT '消息回复时间',
  `state` varchar(200) NOT NULL COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


