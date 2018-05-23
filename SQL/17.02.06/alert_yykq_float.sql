alter table yytb_order modify cost float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '金额';
alter table yytb_periods modify total float(10,2) NOT NULL  COMMENT '商品总价';
alter table yytb_recharge modify money float(10,2) NOT NULL  COMMENT '充值金额';
