alter   table   customer   add   openid   varchar(200);
INSERT INTO customer (member_id,nickname,openid) SELECT member_id,nickname,openid FROM member