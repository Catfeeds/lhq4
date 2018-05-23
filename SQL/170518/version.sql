drop table if exists version;
create table version(
    id mediumint unsigned not null auto_increment comment 'id',
    version  varchar(30) not null comment '类型名称',
    app_name varchar(30) not null comment '应用名称',
    status enum('0','1') not null default '1' comment'开关控制',
    primary key(id)
)engine=InnoDB default charset=utf8 comment'版本url控制表';