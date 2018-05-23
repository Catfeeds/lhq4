ALTER TABLE `member`
MODIFY COLUMN `creatDate`  datetime NULL COMMENT '注册时间' AFTER `grandBuy`,
MODIFY COLUMN `last_login_time`  datetime NULL COMMENT '上次登录时间' AFTER `creatDate`;

ALTER TABLE `member`
ADD COLUMN `identify`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '初始标识' AFTER `idfa`;

