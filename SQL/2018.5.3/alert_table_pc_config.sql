ALTER TABLE `pc_config`
MODIFY COLUMN `request`  enum('get','post','newpost') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '��������ʽ' AFTER `config_if`;

