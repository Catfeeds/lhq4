CREATE TABLE `kwd_mount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL COMMENT '����id',
  `kkwd` varchar(255) DEFAULT NULL COMMENT '�ؼ���',
  `kwd_qx` enum('0','1') DEFAULT NULL COMMENT '�ؼ���Ȩ��',
  `mount` int(11) DEFAULT NULL COMMENT '�ؼ��ʵķ���',
  `mount_re` int(11) DEFAULT NULL COMMENT '���ؼ��ʵ�ʣ����',
  `time` timestamp NULL DEFAULT NULL COMMENT 'ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;