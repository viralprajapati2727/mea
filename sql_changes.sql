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