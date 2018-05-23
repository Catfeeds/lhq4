ALTER TABLE `channel_log` ADD INDEX( `app_id`, `chan_id`);
ALTER TABLE `provider_log` ADD INDEX( `app_id`, `chan_id`);
ALTER TABLE `channel_callback_log` ADD INDEX( `app_id`, `chan_id`);
ALTER TABLE `channel_log` ADD INDEX( `app_id`, `chan_id`, `provider_id`);