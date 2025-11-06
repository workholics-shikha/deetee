
ALTER TABLE `sales_order_products` CHANGE `size4` `kw_size1` DECIMAL NULL DEFAULT NULL;
ALTER TABLE `sales_order_products` CHANGE `kw_size1` `kw_size1` DECIMAL(10,2) NULL DEFAULT NULL;

INSERT INTO `machine_wise_operations` (`id`, `machine_id`, `operation_id`, `created_at`, `updated_at`) VALUES (NULL, '134', '99', NULL, NULL), (NULL, '135', '99', NULL, NULL), (NULL, '136', '99', NULL, NULL);

ALTER TABLE `sales_order_trackings` CHANGE `ideal_cycle_time` `ideal_cycle_time` VARCHAR(110) NULL DEFAULT NULL;

ALTER TABLE `operation_masters`
  DROP `parameters`,
  DROP `parameter1`,
  DROP `parameter2`,
  DROP `parameter3`,
  DROP `parameter4`,
  DROP `operation_comment`;

  ALTER TABLE `machine_master`
  DROP `operator_id`,
  DROP `tracking_id`;

ALTER TABLE `operator_attendances`
  DROP `machine_id`,
  DROP `so_id`,
  DROP `product_id`;

ALTER TABLE `subproduct_wise_operation` Add `operation_availabilty` TINYINT(4) NOT NULL DEFAULT '1';
  
// ========== 10-10-2025

DROP TABLE `bearing_seat_with_cycle_time`, `bore_with_cycle_time`, `corners_with_cycle_time`, `length_with_cycle_time`, `material_with_cycle_time`, `tapping_with_cycle_time`, `thickness_with_cycle_time`, `width_with_cycle_time`;

TRUNCATE TABLE  `personal_access_tokens`;
TRUNCATE TABLE  `password_resets`;

// ===================== 30-Oct-25 ===================

ALTER TABLE `sales_order_products` ADD `kw_depth` INT NOT NULL DEFAULT '0' AFTER `kw_size1` ;

ALTER TABLE `sales_order_products` ADD `bs1_blankdia` INT NOT NULL DEFAULT '0' AFTER `kw_depth`, ADD `bs1_depthdia` INT NOT NULL DEFAULT '0' AFTER `bs1_blankdia`;

ALTER TABLE `machine_master` ADD `operator_id` INT NULL DEFAULT NULL AFTER `machine_status`, ADD `tracking_id` INT NULL DEFAULT NULL AFTER `operator_id`;

//========================================

CREATE TABLE `deetee_prod`.`ict_bearing_seat` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` int(11) NOT NULL,
  `operation` int(11) NOT NULL,
  `seat_size` varchar(255) NOT NULL,
  `seat_depth` varchar(255) NOT NULL,
  `cycle_time` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `deetee_prod`.`ict_bearing_seat` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_bearing_seat` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_bearing_seat`(`id`, `machine_id`, `operation`, `seat_size`, `seat_depth`, `cycle_time`, `created_at`, `updated_at`) SELECT `id`, `machine_id`, `operation`, `seat_size`, `seat_depth`, `cycle_time`, `created_at`, `updated_at` FROM `deetee`.`ict_bearing_seat`;
CREATE TABLE `deetee_prod`.`ict_cnc_blanking` (
  `id` int(11) NOT NULL,
  `sub_product_id` int(11) DEFAULT NULL,
  `operation` int(11) DEFAULT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `thickness_min` int(11) NOT NULL,
  `thickness_max` int(11) NOT NULL,
  `vertical_min` int(11) NOT NULL,
  `vertical_max` int(11) NOT NULL,
  `cycle_time` decimal(11,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `deetee_prod`.`ict_cnc_blanking` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_cnc_blanking` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4055 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_cnc_blanking`(`id`, `sub_product_id`, `operation`, `machine_id`, `thickness_min`, `thickness_max`, `vertical_min`, `vertical_max`, `cycle_time`, `created_at`, `updated_at`) SELECT `id`, `sub_product_id`, `operation`, `machine_id`, `thickness_min`, `thickness_max`, `vertical_min`, `vertical_max`, `cycle_time`, `created_at`, `updated_at` FROM `deetee`.`ict_cnc_blanking`;
CREATE TABLE `deetee_prod`.`ict_keyway_operations` (
  `id` int(11) NOT NULL,
  `sub_product_id` int(11) DEFAULT NULL,
  `operation` varchar(50) NOT NULL,
  `machine_ids` varchar(100) NOT NULL,
  `keyway_width_min` decimal(10,2) DEFAULT NULL,
  `keyway_width_max` decimal(10,2) DEFAULT NULL,
  `thickness_min` decimal(10,2) DEFAULT NULL,
  `thickness_max` decimal(10,2) DEFAULT NULL,
  `stack_length_min` decimal(10,2) DEFAULT NULL,
  `stack_length_max` decimal(10,2) DEFAULT NULL,
  `cycle_time_minutes` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `deetee_prod`.`ict_keyway_operations` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_keyway_operations` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=231 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_keyway_operations`(`id`, `sub_product_id`, `operation`, `machine_ids`, `keyway_width_min`, `keyway_width_max`, `thickness_min`, `thickness_max`, `stack_length_min`, `stack_length_max`, `cycle_time_minutes`) SELECT `id`, `sub_product_id`, `operation`, `machine_ids`, `keyway_width_min`, `keyway_width_max`, `thickness_min`, `thickness_max`, `stack_length_min`, `stack_length_max`, `cycle_time_minutes` FROM `deetee`.`ict_keyway_operations`;
CREATE TABLE `deetee_prod`.`ict_material` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ict_id` int(11) DEFAULT NULL,
  `diameter_start_range` int(11) NOT NULL,
  `diameter_end_range` int(11) NOT NULL,
  `d2_h13` int(11) NOT NULL,
  `d3` int(11) NOT NULL,
  `en31` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `deetee_prod`.`ict_material` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_material` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=370 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_material`(`id`, `ict_id`, `diameter_start_range`, `diameter_end_range`, `d2_h13`, `d3`, `en31`, `created_at`, `updated_at`) SELECT `id`, `ict_id`, `diameter_start_range`, `diameter_end_range`, `d2_h13`, `d3`, `en31`, `created_at`, `updated_at` FROM `deetee`.`ict_material`;
CREATE TABLE `deetee_prod`.`ict_rmr_2matrix` (
  `id` int(11) NOT NULL,
  `sub_product_id` int(11) DEFAULT NULL,
  `operation` int(11) DEFAULT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `length_min` int(11) DEFAULT NULL,
  `length_max` int(11) DEFAULT NULL,
  `od_min` int(11) NOT NULL,
  `od_max` int(11) NOT NULL,
  `cycle_time_per_pc` int(11) DEFAULT NULL,
  `loading_unloading_time` int(11) DEFAULT NULL,
  `total_cycle_time` int(11) DEFAULT NULL,
  `table_parts` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `deetee_prod`.`ict_rmr_2matrix` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_rmr_2matrix` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11885 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_rmr_2matrix`(`id`, `sub_product_id`, `operation`, `machine_id`, `length_min`, `length_max`, `od_min`, `od_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `table_parts`, `created_at`, `updated_at`) SELECT `id`, `sub_product_id`, `operation`, `machine_id`, `length_min`, `length_max`, `od_min`, `od_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `table_parts`, `created_at`, `updated_at` FROM `deetee`.`ict_rmr_2matrix`;
CREATE TABLE `deetee_prod`.`ict_tapping` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `operation` int(11) NOT NULL,
  `size` varchar(255) NOT NULL,
  `length` varchar(255) DEFAULT NULL,
  `cycle_time` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `deetee_prod`.`ict_tapping` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_tapping` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_tapping`(`id`, `machine_id`, `operation`, `size`, `length`, `cycle_time`, `created_at`, `updated_at`) SELECT `id`, `machine_id`, `operation`, `size`, `length`, `cycle_time`, `created_at`, `updated_at` FROM `deetee`.`ict_tapping`;
CREATE TABLE `deetee_prod`.`ict_thickness` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_product_id` int(11) DEFAULT NULL,
  `operation` int(11) NOT NULL,
  `machine_id` int(11) DEFAULT NULL,
  `thickness` varchar(255) NOT NULL,
  `cycle_time` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
ALTER TABLE `deetee_prod`.`ict_thickness` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ict_thickness` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=393 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ict_thickness`(`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) SELECT `id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at` FROM `deetee`.`ict_thickness`;
CREATE TABLE `deetee_prod`.`ideal_cycle_time_parent` (
  `id` int(11) NOT NULL,
  `sub_product_id` int(11) DEFAULT NULL,
  `operation_id` int(11) NOT NULL,
  `machine_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE `deetee_prod`.`ideal_cycle_time_parent` ADD PRIMARY KEY (`id`);
ALTER TABLE `deetee_prod`.`ideal_cycle_time_parent` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60 ;

SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

INSERT INTO `deetee_prod`.`ideal_cycle_time_parent`(`id`, `sub_product_id`, `operation_id`, `machine_id`) SELECT `id`, `sub_product_id`, `operation_id`, `machine_id` FROM `deetee`.`ideal_cycle_time_parent`;