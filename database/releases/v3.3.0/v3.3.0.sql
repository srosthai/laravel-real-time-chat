create table `settings` (`id` bigint unsigned not null auto_increment primary key, `key` varchar(191) not null, `value` varchar(191) not null, `created_at` timestamp null, `updated_at`
timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `users` add index `users_name_index`(`name`);
alter table `users` add index `users_email_index`(`email`);
 alter table `users` add index `users_phone_index`(`phone`);
 alter table `reported_users` add index `reported_users_created_at_index`(`created_at`);
alter table `roles` add index `roles_name_index`(`name`);
 alter table `conversations` add index `conversations_created_at_index`(`created_at`);
alter table `notifications` add index `notifications_created_at_index`(`created_at`);
alter table `groups` add index `groups_name_index`(`name`);
 alter table `group_users` add index `group_users_role_index`(`role`);

