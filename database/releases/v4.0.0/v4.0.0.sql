create table `zoom_meetings` (`id` bigint unsigned not null auto_increment primary key, `topic` varchar(191) not null, `start_time` datetime not null, `duration` varchar(191) not null, `host_video` tinyint(1) not null, `participant_video` tinyint(1) not null, `agenda` text not null, `created_by` int unsigned not null, `meta` text null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';


alter table `zoom_meetings` add constraint `zoom_meetings_created_by_foreign` foreign key (`created_by`) references `users` (`id`) on delete cascade on update cascade;

create table `zoom_meeting_candidates` (`id` bigint unsigned not null auto_increment primary key, `user_id` int unsigned not null, `meeting_id` bigint unsigned not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `zoom_meeting_candidates` add constraint `zoom_meeting_candidates_user_id_foreign` foreign key (`user_id`) references `users` (`id`) on delete cascade on update cascade;

alter table `zoom_meeting_candidates` add constraint `zoom_meeting_candidates_meeting_id_foreign` foreign key (`meeting_id`) references `zoom_meetings` (`id`) on delete cascade on update cascade;

alter table `zoom_meetings` add `status` int not null default '1', add `meeting_id` varchar(191) not null;

alter table `zoom_meetings` add `time_zone` varchar(191) not null, add `password` varchar(191) not null;
