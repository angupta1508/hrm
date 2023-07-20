-- // [13-3-2023]  companies add new column status by dipesh
ALTER TABLE `companies` ADD `status` VARCHAR(255) NOT NULL AFTER `name`;
ALTER TABLE `attendance_reasons` CHANGE `attend_type` `name` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

// [13-03-2023] add change column name in salary_types table by soumya
ALTER TABLE `salary_types` CHANGE `duty_type` `name` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;

[13-3-2032] correction by aman
ALTER TABLE `sequence_codes` CHANGE `is` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `users` ADD `admin_id` VARCHAR(200) NULL AFTER `package_valid_date`;
INSERT INTO `sequence_codes` (`id`, `sequence_code`, `sequence_number`, `created_at`, `updated_at`) VALUES
(1, 'SUP', '0001', NULL, '2022-09-17 02:09:25'),
(2, 'ADM', '0005', NULL, '2023-03-13 12:11:20'),
(3, 'SUP-STF', '0001', NULL, '2023-01-05 18:10:25'),
(4, 'ADM-STF', '0002', NULL, '2023-03-13 12:15:53');
INSERT INTO `sequence_codes` (`id`, `sequence_code`, `sequence_number`, `created_at`, `updated_at`) VALUES (NULL, 'EMP', '0001', NULL, '2023-03-13 17:45:53');
INSERT INTO `package_modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'attendence', 'attendence', NULL, NULL);
INSERT INTO `package_modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'leave', 'leave', NULL, NULL);
INSERT INTO `package_modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'payroll', 'payroll', NULL, NULL);
UPDATE `package_modules` SET `module_name` = 'administration', `description` = 'administration', `created_at` = NULL, `updated_at` = NULL WHERE `package_modules`.`id` = 4;
INSERT INTO `package_modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'performace', 'performace', NULL, NULL);

[15/3/2023] change column name in performance_types table by soumya
ALTER TABLE `performance_types` CHANGE `performance_type` `name` VARCHAR(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `settings` CHANGE `defaulte_setting` `default_setting` INT(11) NOT NULL DEFAULT '0';
ALTER TABLE `users` CHANGE `admin_id` `admin_id` BIGINT NULL DEFAULT '0';

[15-3-2023] change table name by Ankit
RENAME TABLE `synihrm`.`attendance` TO `synihrm`.`attendances`;

-- [17-3-2023] update column name created_by & updated_by ny dipesh
ALTER TABLE `holidays` CHANGE `created_by` `created_at` DATETIME NULL DEFAULT NULL;
ALTER TABLE `holidays` CHANGE `updated_by` `updated_at` DATETIME NULL DEFAULT NULL;

[15-3-2023] created table moods by Soumya
INSERT INTO `package_modules` (`id`, `module_name`, `description`, `created_at`, `updated_at`) VALUES (NULL, 'moods', 'moods', NULL, NULL);
ALTER TABLE `performance_types` ADD `admin_id` INT NULL DEFAULT '0' AFTER `id`; 

[16-3-2023] change table name by Aman
ALTER TABLE `settings` CHANGE `user_id` `admin_id` INT(11) NULL DEFAULT 
NULL;
INSERT INTO `settings` (`id`, `user_id`, `setting_name`, `setting_label`, `setting_value`, `input_type`, `setting_type`, `defaulte_setting`, `status`, `updated_at`, `created_at`) VALUES (NULL, '0', 'maintenance_mode', 'MAINTENANCE MODE', '1', 'slider', 'company', '1', '1', '2023-03-11 16:08:06', '2022-07-18 08:31:46');
ALTER TABLE `settings` CHANGE `defaulte_setting` `default_setting` INT(11) NOT NULL DEFAULT '0';

[15-3-2023] change table name by mahendra
UPDATE `mood_types` SET `name` = 'Very Sad' WHERE `mood_types`.`id` = 1;
UPDATE `mood_types` SET `name` = 'Sad' WHERE `mood_types`.`id` = 2;
UPDATE `mood_types` SET `name` = 'Normal' WHERE `mood_types`.`id` = 3;
UPDATE `mood_types` SET `name` = 'Good' WHERE `mood_types`.`id` = 4;
UPDATE `mood_types` SET `name` = 'Excellent' WHERE `mood_types`.`id` = 5;
ALTER TABLE `performance_types` ADD `admin_id` INT NULL AFTER `id`;

[17-3-2023] change table name by Aman
CREATE TABLE `user_otps` ( `id` bigint(20) UNSIGNED NOT NULL, `singnature_id` varchar(200) NOT NULL, `phone` varchar(255) NOT NULL, `login_otp` varchar(255) NOT NULL, `expires_at` datetime DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `settings` (`id`, `admin_id`, `setting_name`, `setting_label`, `setting_value`, `input_type`, `setting_type`, `default_setting`, `status`, `updated_at`, `created_at`) VALUES (NULL, '1', 'sms_live_mode', 'sms_live_mode', '1', 'slider', 'sms', '1', '1', '2022-12-29 18:17:34', '2022-09-20 14:06:19');
INSERT INTO `email_templates` (`id`, `title`, `template_code`, `content`, `status`, `created_at`, `updated_at`) VALUES
('', 'Forget Password', 'forget-password', '<p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin-right: 0px; margin-bottom: 1.25em; margin-left: 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">Hello!</p><p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin: 1.25em 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">We have received your request to reset your password. Please use the following OTP code to reset your password:</p><p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin: 1.25em 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">*OTP_CODE*</p><p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin: 1.25em 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">If you did not make this request, please ignore this email.</p><p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin: 1.25em 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">Thank you for using our service!</p><p style=\"border: 0px solid rgb(217, 217, 227); --tw-border-spacing-x:0; --tw-border-spacing-y:0; --tw-translate-x:0; --tw-translate-y:0; --tw-rotate:0; --tw-skew-x:0; --tw-skew-y:0; --tw-scale-x:1; --tw-scale-y:1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness:proximity; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width:0px; --tw-ring-offset-color:#fff; --tw-ring-color:rgba(59,130,246,0.5); --tw-ring-offset-shadow:0 0 transparent; --tw-ring-shadow:0 0 transparent; --tw-shadow:0 0 transparent; --tw-shadow-colored:0 0 transparent; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; margin: 1.25em 0px 0px; color: rgb(55, 65, 81); font-family: Söhne, ui-sans-serif, system-ui, -apple-system, &quot;Segoe UI&quot;, Roboto, Ubuntu, Cantarell, &quot;Noto Sans&quot;, sans-serif, &quot;Helvetica Neue&quot;, Arial, &quot;Apple Color Emoji&quot;, &quot;Segoe UI Emoji&quot;, &quot;Segoe UI Symbol&quot;, &quot;Noto Color Emoji&quot;; font-size: 16px; white-space: pre-wrap; background-color: rgb(247, 247, 248);\">Best regards,\r\n*COMPANY_NAME*</p>', 1, '2023-03-17 10:23:17', '2023-03-17 10:23:17');
ALTER TABLE `user_otps` CHANGE `id` `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT, add PRIMARY KEY (`id`);


[18-3-2023] change table name by Aman
ALTER TABLE `attendances` CHANGE `attendance_date` `attendance_date` DATE NULL DEFAULT NULL;

[20-3-2023] change table name by Aman
ALTER TABLE `employees` ADD `is_manager` VARCHAR(200) NULL AFTER `machine_code`;

[21-3-2023] change type in  is_manual_attendance field  by Ankit
ALTER TABLE `attendances` CHANGE `is_manual_attendance` `is_manual_attendance` TINYINT(1) NOT NULL DEFAULT '1';
ALTER TABLE `users` CHANGE `dob` `dob` DATE NULL DEFAULT NULL;
ALTER TABLE `users` CHANGE `religion` `religion` VARCHAR(200) NULL DEFAULT NULL;
ALTER TABLE `users` ADD `device_id` VARCHAR(200) NULL AFTER `longitude`;
ALTER TABLE `employees` CHANGE `is_manager` `is_manager` INT NULL DEFAULT '0';
ALTER TABLE `employees` CHANGE `contract_type` `contract_type` TINYINT(2) NULL DEFAULT NULL COMMENT '0=>Temporary,1=>Permanent';

[22-3-2023] change table name by Aman
ALTER TABLE `users` CHANGE `marital_status` `marital_status` VARCHAR(200) NULL DEFAULT NULL;

[23-3-2023] change table name by Aman
ALTER TABLE `attendances` ADD `shift_id` INT NULL AFTER `user_id`;
ALTER TABLE `shifts` ADD `shift_type` VARCHAR(200) NULL AFTER `shift_name`;

[24-3-2023] change table name by Soumya
ALTER TABLE `shifts` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `shifts` CHANGE `shift_type` `shift_type` ENUM('Day', 'Night') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Day';

[29-3-2023] add table  by Aman
CREATE TABLE `location_users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `location_id` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `location_users`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `location_users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
  ALTER TABLE `users` DROP `parent_id`;
  ALTER TABLE `employees` DROP `location_id`;

[30-3-2023] add table  by Aman
ALTER TABLE `users` ADD `company_code` VARCHAR(200) NULL AFTER `admin_id`;

[31-3-2023] add table  by Aman
ALTER TABLE `synihrm`.`users` ADD UNIQUE (`company_code`);
ALTER TABLE `packages` ADD `label` VARCHAR(200) NULL DEFAULT NULL AFTER `duration`;
ALTER TABLE `packages` CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;
INSERT INTO `settings` (`id`, `admin_id`, `setting_name`, `setting_label`, `setting_value`, `input_type`, `setting_type`, `default_setting`, `status`, `updated_at`, `created_at`) VALUES (NULL, '1', 'footer', 'Footer', 'Synilogic Tech Private Limited', 'text', 'company', '0', '1', '2023-03-21 15:55:24', '2022-07-18 07:21:51');

[1-4-2023] add table  by Aman
ALTER TABLE `users` CHANGE `gender` `gender` VARCHAR(200) NULL DEFAULT NULL COMMENT '0=>Male,1=>Female,2=>Others';
UPDATE `users` SET `gender`='Male' WHERE gender = 0;
UPDATE `users` SET `gender`='Female' WHERE gender = 1;
UPDATE `users` SET `gender`='Other' WHERE gender = 2;
ALTER TABLE `employees` DROP INDEX `admin_id`;
ALTER TABLE `employees` DROP INDEX `admin_id_2`;
ALTER TABLE `employees` DROP INDEX `users_username_unique`;
ALTER TABLE `employees` DROP INDEX `shift_id`;
ALTER TABLE `employees` DROP INDEX `shift_id_2`;
ALTER TABLE `employees` DROP INDEX `employee_code_2`;

ALTER TABLE `employees` DROP INDEX `user_id_2`;

ALTER TABLE `synihrm`.`employees` DROP INDEX `user_id`, ADD INDEX `user_id` (`user_id`) USING BTREE;
ALTER TABLE `synihrm`.`employees` DROP INDEX `joined_date`, ADD INDEX `joined_date` (`joined_date`) USING BTREE;
ALTER TABLE `synihrm`.`employees` DROP INDEX `company_id`, ADD INDEX `company_id` (`company_id`) USING BTREE;
ALTER TABLE `employees` DROP INDEX `company_id_2`;
ALTER TABLE `employees` DROP INDEX `company_id_3`;
ALTER TABLE `employees` DROP INDEX `company_id_4`;
ALTER TABLE `employees` DROP INDEX `hire_date_2`;
ALTER TABLE `employees` DROP INDEX `joined_date_2`;

[4-4-2023] add table  by Aman

ALTER TABLE `packages` ADD `trial_duration` BIGINT(20) NULL AFTER `price`;
ALTER TABLE `users` ADD `istrial` INT NULL DEFAULT '0' AFTER `admin_id`;
INSERT INTO `sequence_codes` (`id`, `sequence_code`, `sequence_number`, `created_at`, `updated_at`) VALUES (NULL, 'REC', '0001', NULL, '2023-04-03 11:33:33');

[6-4-2023] change table name by Soumya
RENAME TABLE notice_board TO notices;
ALTER TABLE `notices` CHANGE `descriptiion` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

[5-4-2023] add table  by Aman
ALTER TABLE `locations` ADD `ip` VARCHAR(200) NULL AFTER `longitude`;
ALTER TABLE `attendance_logs` CHANGE `punch_type` `punch_type` ENUM('Geo Fencing','Finger Print','Face Recognition','Web') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'Geo Fencing';
ALTER TABLE `users` ADD `process_status` INT NULL DEFAULT '0' AFTER `longitude`;

[8-4-2023] add table  by Aman
CREATE TABLE `notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `notifications` ADD `admin_id` INT NULL AFTER `role_id`;
ALTER TABLE `notifications` ADD `image` INT NULL AFTER `title`;

[7-4-2023] change type   by Ankit
ALTER TABLE `attendances` CHANGE `from_time` `from_time` DATETIME NULL DEFAULT NULL;
ALTER TABLE `attendances` CHANGE `to_time` `to_time` DATETIME NULL DEFAULT NULL;

[7-4-2023] by Mahendra
ALTER TABLE `attendances` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT, CHANGE `from_time` `from_time` DATETIME NULL DEFAULT '0', CHANGE `to_time` `to_time` DATETIME NULL DEFAULT '0', CHANGE `working_hours` `working_hours` TIME NULL DEFAULT '0', CHANGE `overtime` `overtime` TIME NULL DEFAULT '0', CHANGE `early_in` `early_in` TIME NULL DEFAULT '0', CHANGE `late_out` `late_out` TIME NULL DEFAULT '0', CHANGE `late_in` `late_in` TIME NULL DEFAULT '0', CHANGE `early_out` `early_out` TIME NULL DEFAULT '0', CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0';

[10-4-2023] add table  by Aman
ALTER TABLE `employees` ADD `

` VARCHAR(200) NULL DEFAULT 'Sunday' AFTER `shift_id`;
ALTER TABLE `attendances` CHANGE `attendance_type` `attendance_type` ENUM('Auto','Manual','None') CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
CREATE TABLE `user_policies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `policy_name` varchar(200) DEFAULT NULL,
  `fullday_relaxation` int(11) DEFAULT 0,
  `halfday_relaxation` int(11) DEFAULT 0,
  `eneble_late_coming` tinyint(1) DEFAULT 0,
  `late_coming_relaxation` int(11) DEFAULT 0,
  `late_coming_deduction_repeate` int(11) DEFAULT 0,
  `eneble_early_going` tinyint(1) DEFAULT 0,
  `early_going_relaxation` int(11) DEFAULT 0,
  `early_going_deduction_repeate` int(11) DEFAULT 0,
  `eneble_overtime_non_working_day` tinyint(1) DEFAULT 0,
  `eneble_overtime_working_day` tinyint(1) DEFAULT 0,
  `eneble_sandwich` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `user_policies`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `user_policies`
MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;  
ALTER TABLE `employees` ADD `policy_id` INT NULL AFTER `shift_id`;

[11-4-2023] add table  by Aman
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','AL','UL','WO','HD (Late Come)') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','AL','UL','WO','HD (Late Come)','Early Go') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';
ALTER TABLE `leave_types` ADD `is_paidleave` INT NULL DEFAULT '0' AFTER `leave_days`;
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','AL','UL','WO','HD (Late Come)','HD (Early Go)') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';


[13-4-2023] add by aman
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','AL','UL','WO','HD (Late Come)','HD (Early Go)','HO') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','AL','UL','WO','HD (Late Come)','HD (Early Go)','HO','HD (Incomplete Hours)','A (Incomplete Hours)') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';

[17-4-2023] add by aman
ALTER TABLE `user_policies` ADD `cl` INT NULL DEFAULT '0' AFTER `eneble_sandwich`, ADD `pl` INT NULL DEFAULT '0' AFTER `cl`, ADD `medical_leave` INT NULL DEFAULT '0' AFTER `pl`, ADD `paternity_leave` INT NULL DEFAULT '0' AFTER `medical_leave`, ADD `maternity_leave` INT NULL DEFAULT '0' AFTER `paternity_leave`, ADD `every_month_paid_leave` INT NULL DEFAULT '0' AFTER `maternity_leave`, ADD `carry_forward` INT NULL DEFAULT '0' AFTER `every_month_paid_leave`, ADD `carry_forward_paid_leave` INT NULL DEFAULT '0' AFTER `carry_forward`;


[08-5-2023] add by aman
ALTER TABLE `employees` ADD `shift_rooster` INT NULL DEFAULT '0' AFTER `user_id`;

[09-5-2023] add by aman         
ALTER TABLE `user_policies` ADD `eneble_holiday_working_hours` INT NULL DEFAULT '0' AFTER `early_going_deduction_repeate`, ADD `holiday_working_hours` INT NULL DEFAULT '0' AFTER `eneble_holiday_working_hours`, ADD `eneble_weekoff_working_hours` INT NULL DEFAULT '0' AFTER `holiday_working_hours`, ADD `weekoff_working_hours` INT NULL DEFAULT '0' AFTER `eneble_weekoff_working_hours`;


[10-5-2023] table create by ankit
  CREATE TABLE `manual_attendances` (
    `id` int(11) NOT NULL,
    `admin_id` int(11) NOT NULL,
    `user_id` bigint(20) NOT NULL,
    `shift_id` int(11) DEFAULT NULL,
    `authorised_person_id` int(11) DEFAULT NULL,
    `attendance_reason_id` int(2) DEFAULT NULL,
    `attendance_type` enum('Auto','Manual','None') DEFAULT NULL,
    `from_time` datetime DEFAULT NULL,
    `to_time` datetime DEFAULT NULL,
    `late_out` time DEFAULT NULL,
    `early_out` time DEFAULT NULL,
    `request_remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `request_hard_copy` text DEFAULT NULL,
    `attendance_date` date DEFAULT NULL,
    `approve_remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
    `approve_date` date DEFAULT NULL,
    `approved_by` bigint(30) DEFAULT NULL,
    `attendance_status` enum('A','P','HD','MP','AL','UL','WO','HD (Late Come)','HD (Early Go)','HO','HD (Incomplete Hours)','A (Incomplete Hours)') NOT NULL DEFAULT 'A' COMMENT '''A'' => ''Absent'', ''P'' => ''present'', ''HD'' => ''Half Day'', ''MP'' => ''Miss Punch'', ''AL'' => ''Approved Leave'', ''UL'' => ''Un-Approved Leave'', ''WO'' => ''Week Off''',
    `is_manual_attendance` tinyint(1) NOT NULL DEFAULT 0,
    `status` tinyint(4) NOT NULL DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  ALTER TABLE `manual_attendances`
    ADD PRIMARY KEY (`id`),
    ADD KEY `manual_attendance` (`admin_id`,`user_id`,`attendance_reason_id`,`authorised_person_id`,`approved_by`,`attendance_status`,`is_manual_attendance`,`status`,`attendance_date`,`approve_date`) USING BTREE,
    ADD KEY `admin_id` (`admin_id`,`status`) USING BTREE,
    ADD KEY `authorised_person_id` (`authorised_person_id`),
    ADD KEY `attendance_reason_id` (`attendance_reason_id`),
    ADD KEY `attendance_type` (`attendance_type`),
    ADD KEY `attendance_date` (`attendance_date`),
    ADD KEY `status` (`status`),
    ADD KEY `approve_date` (`approve_date`),
    ADD KEY `approved_by` (`approved_by`),
    ADD KEY `is_manual_attendance` (`is_manual_attendance`),
    ADD KEY `admin_id_2` (`admin_id`),
    ADD KEY `admin_id_3` (`admin_id`,`user_id`),
    ADD KEY `admin_id_4` (`admin_id`,`user_id`,`from_time`,`to_time`,`late_out`),
    ADD KEY `from_time` (`from_time`),
    ADD KEY `to_time` (`to_time`),
    ADD KEY `attendance_status` (`attendance_status`),
    ADD KEY `status_2` (`status`,`created_at`);

  ALTER TABLE `manual_attendances`              
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;                                    
    ALTER TABLE `attendances` ADD `overday` INT NULL DEFAULT '0' AFTER `attendance_status`;

[11-5-2023] add by aman      
ALTER TABLE `attendances` ADD `previous_state` JSON NULL DEFAULT NULL AFTER `overday`;  

[13-5-2023] add by aman      
ALTER TABLE `leave_types`
  DROP `admin_id`,
  DROP `leave_days`,
  DROP `is_paidleave`;

  ALTER TABLE `user_policies` CHANGE `carry_forward` `carry_forward_month` INT NULL DEFAULT '0', CHANGE `carry_forward_paid_leave` `carry_forward_year` INT NULL DEFAULT '0';
  ALTER TABLE `user_policies` ADD `carry_forward_till_month` INT NULL AFTER `carry_forward_year`;
  ALTER TABLE `attendances` ADD `leave_id` INT NULL AFTER `shift_id`;
ALTER TABLE `attendances` ADD `second_status` ENUM('HL') NULL AFTER `attendance_status`;
ALTER TABLE `leave_applications` ADD `remove_days` FLOAT NULL AFTER `approved_by`;

[16-5-2023] add by aman      
ALTER TABLE `attendances` CHANGE `second_status` `description` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL;
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','L','WO','HO') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'AL\' => \'Approved Leave\', \'UL\' => \'Un-Approved Leave\', \'WO\' => \'Week Off\'';
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','L','WO','HO','HL') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'L\' => \'Leave\', \'HL\' => \'Haif Leave\', \'WO\' => \'Week Off\',\'HO\' => \'Hoilday\'';
ALTER TABLE `leave_applications` ADD `shift_id` INT NULL AFTER `user_id`;

[19-5-2023] add by dipesh  
ALTER TABLE `notifications` CHANGE `image` `image` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;

[23-5-2023] add by aman      
ALTER TABLE `user_policies` ADD `eneble_weekday_for_weekend` INT NULL DEFAULT '0' AFTER `weekoff_working_hours`, ADD `weekday_for_weekend` INT NULL DEFAULT NULL AFTER `eneble_weekday_for_weekend`;
ALTER TABLE `user_policies` CHANGE `weekday_for_weekend` `weekday_for_weekend` INT(11) NULL DEFAULT '5';
ALTER TABLE `user_policies` ADD `autual_month_day` INT NULL DEFAULT '0' AFTER `eneble_overtime_working_day`;
ALTER TABLE `salary_setups` ADD `salary_based_on` INT NOT NULL DEFAULT '0' AFTER `salary_type_id`;
ALTER TABLE `salaries` ADD `salary_based_on` INT NULL DEFAULT '0' AFTER `salary_setup_id`;
ALTER TABLE `salary_setups` ADD `per_hour_overtime_amount` BIGINT(20) NULL DEFAULT '0' AFTER `basic_salary`;
ALTER TABLE `salary_setups` CHANGE `per_hour_overtime_amount` `per_hour_overtime_amount` DECIMAL(10,2) NULL DEFAULT '0';
ALTER TABLE `salaries` ADD `total_addition` DECIMAL(10,2) NULL AFTER `esi_amount`, ADD `total_deduction` DECIMAL(10,2) NULL AFTER `total_addition`;

ALTER TABLE `salaries` ADD `remove_week_off` INT NULL DEFAULT '0' AFTER `total_holidays`;

[29-5-2023] add by aman      
ALTER TABLE `user_notifications` ADD `role_id` INT NULL AFTER `admin_id`;

[30-5-2023] add by ankit    
ALTER TABLE `user_notifications` ADD `title` VARCHAR(255) NOT NULL AFTER `user_id`;
ALTER TABLE `user_notifications` ADD `image` VARCHAR(255) NOT NULL AFTER `msg`;
ALTER TABLE `user_notifications` CHANGE `msg` `description` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;


[31-5-2023] add by aman    
ALTER TABLE `salaries` ADD `auto_leave` DECIMAL(10,2) NULL AFTER `apply_cl`;
ALTER TABLE `salaries`  DROP `total_cl`,  DROP `total_pl`;
ALTER TABLE `salaries` CHANGE `totalpresent` `present` FLOAT NOT NULL;
ALTER TABLE `salaries` ADD `absent` FLOAT NULL AFTER `present`, ADD `paydays` FLOAT NULL AFTER `absent`;
ALTER TABLE `salaries` DROP `absent`;
ALTER TABLE `salaries` CHANGE `total_working_minutes` `total_working_minutes` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL;


[02-6-2023] add by aman    
ALTER TABLE `user_policies` DROP `eneble_overtime_non_working_day`;
ALTER TABLE `user_policies` ADD `overtime_apply_time` INT NULL DEFAULT '30' AFTER `eneble_overtime_working_day`;
ALTER TABLE `user_policies` ADD `eneble_working_hours_relaxation` INT NULL DEFAULT '0' AFTER `policy_name`;
ALTER TABLE `packages` ADD `user_limit` VARCHAR(200) NULL AFTER `duration`;


[09-06-2023] change by dipesh
ALTER TABLE `location_users` CHANGE `admin_id` `admin_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `location_users` CHANGE `user_id` `user_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `location_users` CHANGE `location_id` `location_id` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;
ALTER TABLE `user_location_tracks` CHANGE `latitude` `latitude` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `user_location_tracks` CHANGE `longitude` `longitude` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `user_location_tracks` CHANGE `location` `location` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `user_location_tracks` CHANGE `area` `area` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;

[09-6-2023] add by aman  
ALTER TABLE `employees` ADD `is_tracking_on` INT NULL DEFAULT '0' AFTER `shift_rooster`;

[14-6-2023] add by aman  
ALTER TABLE `attendances` CHANGE `attendance_status` `attendance_status` ENUM('A','P','HD','MP','L','WO','HO','HL','HD-HL') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'A' COMMENT '\'A\' => \'Absent\', \'P\' => \'present\', \'HD\' => \'Half Day\', \'MP\' => \'Miss Punch\', \'L\' => \'Leave\', \'HL\' => \'Haif Leave\', \'WO\' => \'Week Off\',\'HO\' => \'Hoilday\'';

[21-6-2023] add by aman  
ALTER TABLE `salaries` ADD `additional_salary_settlement_amount` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `total_deduction`, ADD `deduction_salary_settlement_amount` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `additional_salary_settlement_amount`;

CREATE TABLE `salary_settlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `settlement_month` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `amount` float(10,2) NOT NULL,
  `description` text NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `salary_settlements`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `salary_settlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

[24-6-2023] add by aman  
ALTER TABLE `users` ADD `gateway_id` VARCHAR(200) NULL AFTER `company_code`;
INSERT INTO `settings` (`id`, `admin_id`, `setting_name`, `setting_label`, `setting_value`, `input_type`, `setting_type`, `default_setting`, `status`, `updated_at`, `created_at`) VALUES (NULL, '1', 'razorpay_account', 'Razorpay Account', '2323230054854457', 'text', 'payment', '1', '1', '2023-06-10 17:15:52', '2022-09-20 14:06:19');
[24-6-2023] add by dipesh  
ALTER TABLE `user_bankers` ADD `gateway_fund_id` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `user_id`;

[24-6-2023] add by aman 
ALTER TABLE `salaries` ADD `gateway_payment_id` VARCHAR(200) NULL AFTER `working_period`;


