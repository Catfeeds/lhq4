ALTER TABLE `member`
MODIFY COLUMN `yzm_time`  datetime NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '��֤ʱ��' AFTER `yzm_code`,
MODIFY COLUMN `creatDate`  datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'ע��ʱ��' AFTER `grandBuy`,
MODIFY COLUMN `last_login_time`  datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '�ϴε�¼ʱ��' AFTER `creatDate`;

