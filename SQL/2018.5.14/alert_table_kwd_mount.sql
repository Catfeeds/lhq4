ALTER TABLE `kwd_mount`
MODIFY COLUMN `lmount`  int(11) NULL AFTER `mount_re`,
MODIFY COLUMN `lmount_re`  int(11) NULL AFTER `lmount`;
