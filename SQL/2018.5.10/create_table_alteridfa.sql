CREATE TABLE `alert_idfa` (
`id`  int(11) NOT NULL AUTO_INCREMENT ,
`phone`  char(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '�ֻ���' ,
`idfa`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��ʼidfa' ,
`identify`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��ʶidfa' ,
`time`  datetime NULL COMMENT '�ı�ʱ��' ,
PRIMARY KEY (`id`)
)
COMMENT='idfa�ı��'
;