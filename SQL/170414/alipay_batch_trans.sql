drop table if exists alipay_batch_trans;
create table alipay_batch_trans(
    id int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
    notify_id varchar(255) NOT NULL COMMENT '通知校验ID',
    batch_no char(20) NOT NULL COMMENT '转账批次号',
    pay_user_name char(20) NOT NULL COMMENT '付款账号姓名',
    pay_account_no char(20) NOT NULL COMMENT '付款账号',
    notify_time datetime NOT NULL COMMENT '通知时间',
    primary key(id)
)engine=innoDB default charset=utf8 comment='支付宝批量支付记录表';