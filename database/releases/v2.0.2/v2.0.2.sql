ALTER TABLE conversations ADD reply_to int(10) UNSIGNED DEFAULT NULL;

ALTER TABLE `conversations`
    ADD CONSTRAINT `conversations_reply_to_foreign` FOREIGN KEY (`reply_to`) REFERENCES `conversations` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;