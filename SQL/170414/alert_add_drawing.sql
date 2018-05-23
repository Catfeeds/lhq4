alter table drawing add pay_time datetime comment '转账时间';
alter table drawing add reason varchar(100) comment '转账成功或失败原因';
alter table drawing add wd_no CHAR(30) NOT NULL AFTER `nickname` comment '流水号';