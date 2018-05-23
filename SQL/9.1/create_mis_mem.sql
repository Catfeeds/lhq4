create table mem_mis (
id int(11) not null auto_increment,
member_id int(11) comment'会员id',
mission_id int(11) comment'任务id ',
status tinyint(4) comment'任务状态', PRIMARY KEY (`id`)) engine=MyISAM default charset=utf8;
