ALTER TABLE `member` CHANGE `sex` `sex` ENUM('1','2') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '性别';