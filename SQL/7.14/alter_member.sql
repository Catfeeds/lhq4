alter table member change status status tinyint(4);
alter table task_details change status status tinyint(4);
alter table income_details change income_type income_type tinyint(4);



alter table member change id member_id int(11);
alter table member add login_time datetime;