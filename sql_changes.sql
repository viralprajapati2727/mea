ALTER TABLE `user_profiles` ADD `cover` VARCHAR(255) NULL DEFAULT NULL AFTER `is_experience`, ADD `resume` VARCHAR(255) NULL DEFAULT NULL AFTER `cover`;

ALTER TABLE `post_jobs` ADD `other_job_title` VARCHAR(255) NULL AFTER `job_title_id`;
ALTER TABLE `post_jobs` CHANGE `job_type_id` `job_type_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `post_jobs` CHANGE `currency_id` `currency_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `post_jobs` ADD `job_type` TINYINT NOT NULL DEFAULT '1' COMMENT '1 = post job, 2 = post request' AFTER `user_id`;
ALTER TABLE `post_jobs` ADD `is_paid` TINYINT NOT NULL DEFAULT '0' AFTER `currency_id`;
ALTER TABLE `post_jobs` ADD `is_find_team_member` TINYINT NOT NULL DEFAULT '0' AFTER `key_skills`, ADD `find_team_member_text` VARCHAR(255) NULL DEFAULT NULL AFTER `is_find_team_member`;
ALTER TABLE `post_jobs` ADD `salary_type_id` TINYINT NULL DEFAULT NULL AFTER `is_paid`;
ALTER TABLE `post_jobs` ADD `time_zone` VARCHAR(255) NULL DEFAULT NULL AFTER `job_end_time`;
ALTER TABLE `post_jobs` CHANGE `job_title_id` `job_title_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `post_jobs` ADD `business_category_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `job_shifts` CHANGE `key_skill_id` `shift_id` INT(11) NOT NULL;
ALTER TABLE `job_shifts` ADD `shift_val` INT(11) NULL DEFAULT NULL AFTER `shift_id`;



ALTER TABLE `raise_funds` ADD `user_id` INT(11) NOT NULL AFTER `id`;
ALTER TABLE `raise_funds` CHANGE `currency` `currency` VARCHAR(200) NOT NULL;
ALTER TABLE `raise_funds` CHANGE `received_amount` `received_amount` DECIMAL(10,2) NULL DEFAULT NULL;
ALTER TABLE `raise_funds` CHANGE `commission` `commission` DECIMAL(10,2) NULL DEFAULT NULL, CHANGE `commission_rate` `commission_rate` DECIMAL(5,2) NULL DEFAULT NULL COMMENT 'percentage';



topic TABLE
ALTER TABLE `resources` ADD `document` TEXT NULL DEFAULT NULL AFTER `src`, ADD `ext` VARCHAR(255) NULL DEFAULT NULL AFTER `document`;
ALTER TABLE `resources` ADD `is_url` TINYINT NOT NULL DEFAULT '0' AFTER `src`;
ALTER TABLE `resources` CHANGE `short_description` `short_description` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;

-- larest sql changes by Nikunj.😎
ALTER TABLE `user_profiles` ADD `linkedin_link` VARCHAR(255) NULL DEFAULT NULL AFTER `tw_link`, ADD `github_link` VARCHAR(255) NULL DEFAULT NULL AFTER `linkedin_link`;
ALTER TABLE `user_profiles` ADD `is_resume_public` TINYINT NOT NULL DEFAULT '0' COMMENT '0: no, 1:yes' AFTER `resume`;
ALTER TABLE `appointments` ADD `appointment_time` VARCHAR(50) NULL AFTER `appointment_date`;
ALTER TABLE `user_education_details` ADD `major` VARCHAR(255) NULL AFTER `year`, ADD `minor` VARCHAR(255) NULL AFTER `major`;
ALTER TABLE `user_work_experiences` ADD `responsibilities` VARCHAR(255) NULL AFTER `designation`;
ALTER TABLE `resources` ADD `topic_id` INT NOT NULL AFTER `id`;
ALTER TABLE `job_shifts` ADD `day_shift_val` TINYINT NULL DEFAULT NULL AFTER `job_id`, ADD `night_shift_val` TINYINT NULL DEFAULT NULL AFTER `day_shift_val`;
ALTER TABLE `job_shifts` CHANGE `day_shift_val` `day_shift_val` TINYINT(4) NULL DEFAULT '0', CHANGE `night_shift_val` `night_shift_val` TINYINT(4) NULL DEFAULT '0';
ALTER TABLE `communities` ADD `other_category` VARCHAR(255) NULL DEFAULT NULL AFTER `question_category_id`;
ALTER TABLE `communities` CHANGE `question_category_id` `question_category_id` INT(11) NULL DEFAULT NULL;
ALTER TABLE `users` ADD `google_id` VARCHAR(255) NULL DEFAULT NULL AFTER `remember_token`;
ALTER TABLE `users` ADD `facebook_id` VARCHAR(255) NULL DEFAULT NULL AFTER `google_id`;
ALTER TABLE `blogs` ADD `published_at` VARCHAR(50) NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `blogs` ADD `author_by` VARCHAR(255) NULL DEFAULT NULL AFTER `status`;

CREATE TABLE `lv_mea`.`subscription_emails` ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,  `email` VARCHAR(255) NOT NULL ,    PRIMARY KEY  (`id`), UNIQUE  (`email`)) ENGINE = InnoDB;

-- CREATE TABLE `lv_mea_0904`.`subscription_emails` ( `id` BIGINT(20) NOT NULL AUTO_INCREMENT ,  `email` VARCHAR(255) NOT NULL ,    PRIMARY KEY  (`id`),    UNIQUE  (`email`)) ENGINE = InnoDB;

DELETE FROM `users` WHERE `users`.`id` = 16
UPDATE `users` SET `email` = 'infomuslimstartups@gmail.com' WHERE `users`.`id` = 1;

UPDATE `users` SET `name` = 'MEA Admin', `slug` = 'mea-admin', `email_verified_at` = NULL, `deleted_at` = NULL WHERE `users`.`id` = 1;

ALTER TABLE `resources` ADD `resource_order` INT(10) NULL DEFAULT NULL AFTER `topic_id`;

ALTER TABLE `user_profiles` ADD `is_email_public` TINYINT NOT NULL DEFAULT '0' COMMENT '0:No, 1: Yes' AFTER `is_resume_public`;

ALTER TABLE `user_profiles` ADD `is_education` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0:No, 1: Yes' AFTER `is_experience`;

ALTER TABLE `topics` ADD `topic_order` INT(10) NULL DEFAULT NULL AFTER `status`;





ALTER TABLE `appointments` ADD `receiver_id` INT(11) NULL DEFAULT NULL AFTER `user_id`;

ALTER TABLE `user_profiles` ADD `is_phone_public` TINYINT NOT NULL DEFAULT '0' COMMENT '0:No, 1: Yes' AFTER `is_email_public`

CREATE TABLE `payment_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `stripe_acc_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `raise_fund_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_object` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `stripe_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `stripe_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details_submitted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `account_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `routing_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last4` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_object` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `stripe_accounts` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);
ALTER TABLE `payment_logs` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);