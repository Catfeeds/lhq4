alter table mission add kwd_qx enum('0','1') COMMENT '¹Ø¼ü´ÊÈ¨ÏÞ' ; 

alter table kwd_mount DROP COLUMN kwd_qx;
