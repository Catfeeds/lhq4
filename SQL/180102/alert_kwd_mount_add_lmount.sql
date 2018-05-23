alter table kwd_mount add column lmount int(11) not null after mount_re;
alter table kwd_mount add column lmount_re int(11) not null after lmount;
