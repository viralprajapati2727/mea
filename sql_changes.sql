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