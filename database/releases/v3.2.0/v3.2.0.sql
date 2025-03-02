alter table `users` add `deleted_at` timestamp null;

delete from `conversations` where `from_id` is null or `from_id` = '';

 delete from `conversations` where not exists (select * from `users` where `conversations`.`to_id` = `users`.`id` and `users`.`deleted_at` is null)

