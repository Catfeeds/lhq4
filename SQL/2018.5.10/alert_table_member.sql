ALTER TABLE `member`
MODIFY COLUMN `creatDate`  datetime NULL COMMENT 'ע��ʱ��' AFTER `grandBuy`,
MODIFY COLUMN `last_login_time`  datetime NULL COMMENT '�ϴε�¼ʱ��' AFTER `creatDate`;

ALTER TABLE `member`
ADD COLUMN `identify`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '��ʼ��ʶ' AFTER `idfa`;

