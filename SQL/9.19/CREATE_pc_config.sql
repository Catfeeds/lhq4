CREATE TABLE `pc_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键 ID',
  `app_id` int(10) NOT NULL COMMENT '广告应用ID',
  `config_name` varchar(255) NOT NULL COMMENT '配置信息名称',
  `repeat_url` varchar(255) NOT NULL COMMENT '配置URL',
  `config_if` varchar(255) NOT NULL COMMENT '配置返回结果',
  `request` enum('get','post') NOT NULL COMMENT '配置请求方式',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;