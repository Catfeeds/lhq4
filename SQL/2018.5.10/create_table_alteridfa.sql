CREATE TABLE `alert_idfa` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`phone`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '手机号' ,
`idfa`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '初始idfa' ,
`identify`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '标识idfa' ,
`time`  datetime NULL COMMENT '改变时间' ,
PRIMARY KEY (`id`)
)
COMMENT='idfa改变表'
;