create table row_repeat(
id int(11) not null auto_increment,
app_id varchar(30) comment'���id',
idfa varchar(255) comment'IDFA',
rtime datetime comment'ʱ��',
result varchar(255) comment'���ؽ��', PRIMARY KEY (`id`)) engine=InnoDB default charset=utf8;
