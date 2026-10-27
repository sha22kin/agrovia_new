-- Agroviea: database updates of October 2026, for servers where `php artisan migrate` cannot be run.
-- Import ONCE in phpMyAdmin (select the site's database, then Import). It does the same as these migrations:
--   2026_10_24_000001_add_land_and_crops_to_business_profiles_table
--   2026_10_27_000001_add_post_id_to_messages_table
--   2026_10_27_000002_add_reply_pin_like_delete_to_messages
-- If `php artisan migrate` works on the server, use that instead and do not import this file.
-- Importing it a second time fails with "Duplicate column name": the changes are already there.

-- Seller's farm: total land and the main crops or products
ALTER TABLE `business_profiles` ADD `land_area` decimal(10, 2) NULL AFTER `areas`;
ALTER TABLE `business_profiles` ADD `land_unit` varchar(10) NULL COMMENT 'Bigha, Acre' AFTER `land_area`;
ALTER TABLE `business_profiles` ADD `main_crops` varchar(300) NULL COMMENT 'comma separated' AFTER `land_unit`;

-- Chat: the ad a message is about
ALTER TABLE `messages` ADD `post_id` bigint NULL COMMENT 'the ad the message is about' AFTER `sender_id`;
ALTER TABLE `messages` ADD INDEX `messages_post_id_index`(`post_id`);

-- Chat: reply, pin, delete for everyone, delete for me
ALTER TABLE `messages` ADD `reply_to_id` bigint NULL COMMENT 'the message this one answers' AFTER `post_id`;
ALTER TABLE `messages` ADD `pinned_at` timestamp NULL AFTER `read_at`;
ALTER TABLE `messages` ADD `deleted_at` timestamp NULL COMMENT 'deleted for everyone by the sender' AFTER `pinned_at`;
ALTER TABLE `messages` ADD `sender_hidden_at` timestamp NULL COMMENT 'deleted for me by the sender' AFTER `deleted_at`;
ALTER TABLE `messages` ADD `receiver_hidden_at` timestamp NULL COMMENT 'deleted for me by the receiver' AFTER `sender_hidden_at`;

-- Chat: likes
CREATE TABLE `message_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `message_id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `created_at` timestamp NULL,
  `updated_at` timestamp NULL
) DEFAULT CHARACTER SET utf8mb4 COLLATE 'utf8mb4_unicode_ci';
ALTER TABLE `message_likes` ADD UNIQUE `message_likes_message_id_user_id_unique`(`message_id`, `user_id`);
ALTER TABLE `message_likes` ADD INDEX `message_likes_message_id_index`(`message_id`);

-- Record the three migrations as done, so a later `php artisan migrate` skips them
INSERT INTO `migrations` (`migration`, `batch`)
SELECT m.migration, (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`)
FROM (
  SELECT '2026_10_24_000001_add_land_and_crops_to_business_profiles_table' AS migration
  UNION ALL SELECT '2026_10_27_000001_add_post_id_to_messages_table'
  UNION ALL SELECT '2026_10_27_000002_add_reply_pin_like_delete_to_messages'
) m
WHERE m.migration NOT IN (SELECT `migration` FROM `migrations`);
