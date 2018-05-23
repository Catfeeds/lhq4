create table message(
message_id int(11) not null auto_increment,
title varchar(100) comment'消息主题',
content varchar(255) comment'消息内容',
msg_from varchar(20) comment'消息来源',
msg_to int(11) comment'消息去向',
status tinyint(2) comment'消息状态',
create_time datetime comment'创建时间',
display tinyint(2) comment'是否显示',
PRIMARY KEY (`message_id`)) engine=MyISAM default charset=utf8;
