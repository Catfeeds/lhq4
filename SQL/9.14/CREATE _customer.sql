CREATE TABLE `customer` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '��ϢID',
  `member_id` bigint(20) NOT NULL COMMENT 'member_id',
  `nickname` char(50) NOT NULL COMMENT '��Ϣ������',
  `message` varchar(200) NOT NULL COMMENT '��Ϣ��',
  `time_stamp` datetime NOT NULL COMMENT '��Ϣ����ʱ��',
  `reply` varchar(200) NOT NULL COMMENT '�ظ�����Ϣ',
  `time_reply` datetime NOT NULL COMMENT '��Ϣ�ظ�ʱ��',
  `state` varchar(200) NOT NULL COMMENT '״̬',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;


