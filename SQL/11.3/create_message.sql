create table message(
message_id int(11) not null auto_increment,
title varchar(100) comment'��Ϣ����',
content varchar(255) comment'��Ϣ����',
msg_from varchar(20) comment'��Ϣ��Դ',
msg_to int(11) comment'��Ϣȥ��',
status tinyint(2) comment'��Ϣ״̬',
create_time datetime comment'����ʱ��',
display tinyint(2) comment'�Ƿ���ʾ',
PRIMARY KEY (`message_id`)) engine=MyISAM default charset=utf8;
