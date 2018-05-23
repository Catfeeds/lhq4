update osa_menu_url set menu_url="/backend/Log/channelClickLog" where menu_name="渠道商点击上报日志";

update osa_menu_url set menu_url="/backend/Log/channelCallbackLog" where menu_name="渠道激活回调日志";


update osa_menu_url set menu_url="/backend/Log/channelActiveLog" where menu_name="渠道激活上报日志";

update osa_menu_url set menu_url="/backend/Log/providerCallbackLog" where menu_name="广告商激活回调日志";
update osa_menu_url set menu_url="/backend/Log/duplicateLog" where menu_name="排重日志";
update osa_menu_url set menu_url="/backend/Log/msgLog" where menu_name="消息日志";
update osa_menu_url set menu_url="/backend/Log/output" where menu_name="导出数据";