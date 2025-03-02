 create table `user_devices` (`id` bigint unsigned not null auto_increment primary key, `user_id` int unsigned not null, `player_id` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
 
alter table `user_devices` add constraint `user_devices_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade on update cascade
