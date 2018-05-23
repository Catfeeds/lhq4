create table row_repeat(
id int(11) not null auto_increment,
app_id varchar(30) comment'广告id',
idfa varchar(255) comment'IDFA',
rtime datetime comment'时间',
result varchar(255) comment'返回结果', PRIMARY KEY (`id`)) engine=InnoDB default charset=utf8;
