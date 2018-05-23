CREATE TABLE `kwd_mount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mid` int(11) DEFAULT NULL COMMENT '任务id',
  `kkwd` varchar(255) DEFAULT NULL COMMENT '关键词',
  `kwd_qx` enum('0','1') DEFAULT NULL COMMENT '关键词权限',
  `mount` int(11) DEFAULT NULL COMMENT '关键词的分量',
  `mount_re` int(11) DEFAULT NULL COMMENT '各关键词的剩余量',
  `time` timestamp NULL DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;