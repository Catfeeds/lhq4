ALTER TABLE `app`
ADD COLUMN `bundleId`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'app包名' AFTER `url_scheme`;

ALTER TABLE `app`
CHANGE COLUMN `bundleId` `bundleid`  varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'app包名' AFTER `url_scheme`;