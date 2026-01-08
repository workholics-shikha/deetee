
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

-- // ==== 06012026

INSERT INTO subproduct_wise_operation (
    subproduct_id,
    product_master_id,
    s_no,
    operation_id,
    operation_name,
    sub_operations,unit,parameter1_label,parameter1_value,parameter2_label,parameter2_value,parameter1_value_set,parameter2_value_set,operation_type,fixed_ICT,
    created_at,
    updated_at
)
SELECT
    m.new_subproduct_id,
    m.new_product_master_id,
    s.s_no,
    s.operation_id,
    s.operation_name,
    s.sub_operations,s.unit,s.parameter1_label,s.parameter1_value,s.parameter2_label,s.parameter2_value,s.parameter1_value_set,s.parameter2_value_set,s.operation_type,s.fixed_ICT,
    NOW(),
    NOW()
FROM subproduct_wise_operation s
JOIN (
    SELECT 35 AS new_subproduct_id, 22 AS new_product_master_id UNION ALL
    SELECT 36, 23 UNION ALL
    SELECT 37, 24 UNION ALL
    SELECT 38, 25 UNION ALL
    SELECT 39, 26 UNION ALL
    SELECT 40, 27 UNION ALL
    SELECT 41, 28 UNION ALL
    SELECT 42, 29
) m
WHERE s.subproduct_id = 34;

-- // ===================================

INSERT INTO ict_thickness (
    machine_id,    thickness,    cycle_time,    operation
)
SELECT
    ic.machine_id,    ic.thickness,    ic.cycle_time,    120 AS operation
    FROM ict_thickness ic
WHERE ic.operation = 61;


 INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 122 AS operation
 FROM ict_cnc_blanking ic 
 WHERE ic.operation = 63;

  INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 123 AS operation
 FROM ict_cnc_blanking ic 
 WHERE ic.operation = 65;

  INSERT INTO ict_bearing_seat 
 ( machine_id, seat_size, seat_depth, cycle_time, operation) 
 SELECT ic.machine_id, ic.seat_size, ic.seat_depth, ic.cycle_time, 119 AS operation
 FROM ict_bearing_seat ic 
 WHERE ic.operation = 68;

   INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 129 AS operation
 FROM ict_cnc_blanking ic
 WHERE ic.operation = 70; 
 
 
 INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 132 AS operation
 FROM ict_cnc_blanking ic
 WHERE ic.operation = 72;

INSERT INTO ict_thickness (
    machine_id, thickness, cycle_time, operation
)
SELECT
    ic.machine_id, ic.thickness, ic.cycle_time, 133 AS operation
    FROM ict_thickness ic
WHERE ic.operation = 73;

-- //=========================

INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) 
VALUES 
(NULL, NULL, '136', NULL, '0-50', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '136', NULL, '51-100', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '136', NULL, '101-200', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '136', NULL, '201-300', '105', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '136', NULL, '301-400', '140', '2018-04-25 11:17:00', '2018-04-25 11:17:00');

INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) 
VALUES 
(NULL, NULL, '137', NULL, '0-50', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '137', NULL, '51-100', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '137', NULL, '101-200', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '137', NULL, '201-300', '105', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '137', NULL, '301-400', '140', '2018-04-25 11:17:00', '2018-04-25 11:17:00');

INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) 
VALUES 
(NULL, NULL, '138', NULL, '0-50', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '138', NULL, '51-100', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '138', NULL, '101-200', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '138', NULL, '201-300', '105', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '138', NULL, '301-400', '140', '2018-04-25 11:17:00', '2018-04-25 11:17:00');
 
INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) 
VALUES 
(NULL, NULL, '139', NULL, '0-50', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '139', NULL, '51-100', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '139', NULL, '101-200', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '139', NULL, '201-300', '105', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
(NULL, NULL, '139', NULL, '301-400', '140', '2018-04-25 11:17:00', '2018-04-25 11:17:00');

-- ============================

INSERT INTO `ict_bearing_seat` (`id`, `machine_id`, `operation`, `seat_size`, `seat_depth`, `cycle_time`, `created_at`, `updated_at`) VALUES 
(NULL, '0', '140', '0-40', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '41-50', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '51-60', '0-20', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '61-70', '21-25', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '71-80', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '81-90', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '91-100', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '101-110', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '111-120', '26-30', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '121-130', '26-30', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '131-140', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '141-150', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '151-175', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '176-200', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '201-225', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '140', '226-250', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'),

(NULL, '0', '141', '0-40', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '41-50', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '51-60', '0-20', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '61-70', '21-25', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '71-80', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '81-90', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '91-100', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '101-110', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '111-120', '26-30', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '121-130', '26-30', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '131-140', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '141-150', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '151-175', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '176-200', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '201-225', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '141', '226-250', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00');


 INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 128 AS operation
 FROM ict_cnc_blanking ic
 WHERE ic.operation = 127;

 INSERT INTO ict_cnc_blanking 
 ( machine_id, thickness_min, thickness_max, vertical_min, vertical_max, cycle_time, operation) 
 SELECT ic.machine_id, ic.thickness_min, ic.thickness_max, ic.vertical_min, ic.vertical_max, ic.cycle_time, 131 AS operation
 FROM ict_cnc_blanking ic
 WHERE ic.operation = 130;

--  /=============

 
 Bearing Seat + Thickness Turning - 1/ Bearing Seat + Thickness Turning Single Side-1 (125)
 Bearing Seat + Thickness Turning - 2/ Bearing Seat + Thickness Turning Single Side-2 (126)
 
-- // for part 1 
 INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) VALUES 
 (NULL, NULL, '125', NULL, '0-50', '9', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '125', NULL, '51-100', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '125', NULL, '101-200', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '125', NULL, '201-300', '53', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '125', NULL, '301-400', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'),

 (NULL, NULL, '126', NULL, '0-50', '9', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '126', NULL, '51-100', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '126', NULL, '101-200', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '126', NULL, '201-300', '53', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '126', NULL, '301-400', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00');

-- // for part 2 
INSERT INTO `ict_bearing_seat` (`id`, `machine_id`, `operation`, `seat_size`, `seat_depth`, `cycle_time`, `created_at`, `updated_at`) VALUES 
(NULL, '0', '125', '0-40', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '41-50', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '51-60', '0-20', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '61-70', '21-25', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '71-80', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '81-90', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '91-100', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '101-110', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '111-120', '26-30', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '121-130', '26-30', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '131-140', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '141-150', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '151-175', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '176-200', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '201-225', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '125', '226-250', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'),

(NULL, '0', '126', '0-40', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '41-50', '0-20', '45', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '51-60', '0-20', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '61-70', '21-25', '50', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '71-80', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '81-90', '21-25', '60', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '91-100', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '101-110', '21-25', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '111-120', '26-30', '75', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '121-130', '26-30', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '131-140', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '141-150', '31-35', '90', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '151-175', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '176-200', '36-40', '120', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '201-225', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00'), 
(NULL, '0', '126', '226-250', '41-50', '150', '2018-04-25 05:47:00', '2018-04-25 05:47:00');


Plain Bore Turning + Thickness Turning Single Side-1
Plain Bore Turning + Thickness Turning Single Side-2
 
-- // for part 1 
 INSERT INTO `ict_thickness` (`id`, `sub_product_id`, `operation`, `machine_id`, `thickness`, `cycle_time`, `created_at`, `updated_at`) VALUES 
 (NULL, NULL, '127', NULL, '0-50', '9', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '127', NULL, '51-100', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '127', NULL, '101-200', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '127', NULL, '201-300', '53', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '127', NULL, '301-400', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00'),

 (NULL, NULL, '128', NULL, '0-50', '9', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '128', NULL, '51-100', '18', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '128', NULL, '101-200', '35', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '128', NULL, '201-300', '53', '2018-04-25 11:17:00', '2018-04-25 11:17:00'), 
 (NULL, NULL, '128', NULL, '301-400', '70', '2018-04-25 11:17:00', '2018-04-25 11:17:00');


--  // Plain Bore Turning + Thickness Turning Single Side-1
  
INSERT INTO `ict_cnc_blanking` ( `operation`, `machine_id`, `thickness_min`, `thickness_max`, `vertical_min`, `vertical_max`, `cycle_time`)
SELECT 127 as `operation`, NULL as `machine_id`, 
251 as `vertical_min`, 300 as `vertical_max`, thickness_min AS `thickness_min`, thickness_max AS `thickness_max`, cycle AS `cycle_time` 
FROM
(
SELECT 0 AS 'thickness_min',40 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 41 AS 'thickness_min',45 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 46 AS 'thickness_min',50 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 51 AS 'thickness_min',55 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 56 AS 'thickness_min',60 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 61 AS 'thickness_min',65 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 66 AS 'thickness_min',70 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 71 AS 'thickness_min',75 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 76 AS 'thickness_min',80 AS 'thickness_max',80 AS 'cycle' UNION
SELECT 81 AS 'thickness_min',85 AS 'thickness_max',85 AS 'cycle' UNION
SELECT 86 AS 'thickness_min',90 AS 'thickness_max',85 AS 'cycle' UNION
SELECT 91 AS 'thickness_min',95 AS 'thickness_max',90 AS 'cycle' UNION
SELECT 96 AS 'thickness_min',100 AS 'thickness_max',95 AS 'cycle' UNION
SELECT 101 AS 'thickness_min',105 AS 'thickness_max',100 AS 'cycle' UNION
SELECT 106 AS 'thickness_min',110 AS 'thickness_max',105 AS 'cycle' UNION
SELECT 111 AS 'thickness_min',115 AS 'thickness_max',110 AS 'cycle' UNION
SELECT 116 AS 'thickness_min',120 AS 'thickness_max',115 AS 'cycle' UNION
SELECT 121 AS 'thickness_min',125 AS 'thickness_max',120 AS 'cycle' UNION
SELECT 126 AS 'thickness_min',130 AS 'thickness_max',125 AS 'cycle' UNION
SELECT 131 AS 'thickness_min',135 AS 'thickness_max',130 AS 'cycle' UNION
SELECT 136 AS 'thickness_min',140 AS 'thickness_max',135 AS 'cycle' UNION
SELECT 141 AS 'thickness_min',145 AS 'thickness_max',140 AS 'cycle' UNION
SELECT 146 AS 'thickness_min',150 AS 'thickness_max',145 AS 'cycle' UNION
SELECT 151 AS 'thickness_min',155 AS 'thickness_max',150 AS 'cycle' UNION
SELECT 156 AS 'thickness_min',160 AS 'thickness_max',155 AS 'cycle' UNION
SELECT 161 AS 'thickness_min',165 AS 'thickness_max',160 AS 'cycle' UNION
SELECT 166 AS 'thickness_min',170 AS 'thickness_max',165 AS 'cycle' UNION
SELECT 171 AS 'thickness_min',175 AS 'thickness_max',165 AS 'cycle' UNION
SELECT 176 AS 'thickness_min',180 AS 'thickness_max',170 AS 'cycle' UNION
SELECT 181 AS 'thickness_min',185 AS 'thickness_max',175 AS 'cycle' UNION
SELECT 186 AS 'thickness_min',190 AS 'thickness_max',180 AS 'cycle' UNION
SELECT 191 AS 'thickness_min',195 AS 'thickness_max',185 AS 'cycle' UNION
SELECT 196 AS 'thickness_min',200 AS 'thickness_max',190 AS 'cycle'  
) AS T;

-- // Plain Bore Turning with Under Cut + Thickness Turning Single Side-1


 
INSERT INTO `ict_cnc_blanking` ( `operation`, `machine_id`, `thickness_min`, `thickness_max`, `vertical_min`, `vertical_max`, `cycle_time`)
SELECT 130 as `operation`, NULL as `machine_id`, 
251 as `vertical_min`, 300 as `vertical_max`, thickness_min AS `thickness_min`, thickness_max AS `thickness_max`, cycle AS `cycle_time` 
FROM
(
SELECT 0 AS 'thickness_min',40 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 41 AS 'thickness_min',45 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 46 AS 'thickness_min',50 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 51 AS 'thickness_min',55 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 56 AS 'thickness_min',60 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 61 AS 'thickness_min',65 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 66 AS 'thickness_min',70 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 71 AS 'thickness_min',75 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 76 AS 'thickness_min',80 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 81 AS 'thickness_min',85 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 86 AS 'thickness_min',90 AS 'thickness_max',30 AS 'cycle' UNION
SELECT 91 AS 'thickness_min',95 AS 'thickness_max',35 AS 'cycle' UNION
SELECT 96 AS 'thickness_min',100 AS 'thickness_max',35 AS 'cycle' UNION
SELECT 101 AS 'thickness_min',105 AS 'thickness_max',40 AS 'cycle' UNION
SELECT 106 AS 'thickness_min',110 AS 'thickness_max',40 AS 'cycle' UNION
SELECT 111 AS 'thickness_min',115 AS 'thickness_max',40 AS 'cycle' UNION
SELECT 116 AS 'thickness_min',120 AS 'thickness_max',45 AS 'cycle' UNION
SELECT 121 AS 'thickness_min',125 AS 'thickness_max',45 AS 'cycle' UNION
SELECT 126 AS 'thickness_min',130 AS 'thickness_max',50 AS 'cycle' UNION
SELECT 131 AS 'thickness_min',135 AS 'thickness_max',50 AS 'cycle' UNION
SELECT 136 AS 'thickness_min',140 AS 'thickness_max',50 AS 'cycle' UNION
SELECT 141 AS 'thickness_min',145 AS 'thickness_max',55 AS 'cycle' UNION
SELECT 146 AS 'thickness_min',150 AS 'thickness_max',55 AS 'cycle' UNION
SELECT 151 AS 'thickness_min',155 AS 'thickness_max',60 AS 'cycle' UNION
SELECT 156 AS 'thickness_min',160 AS 'thickness_max',60 AS 'cycle' UNION
SELECT 161 AS 'thickness_min',165 AS 'thickness_max',60 AS 'cycle' UNION
SELECT 166 AS 'thickness_min',170 AS 'thickness_max',65 AS 'cycle' UNION
SELECT 171 AS 'thickness_min',175 AS 'thickness_max',65 AS 'cycle' UNION
SELECT 176 AS 'thickness_min',180 AS 'thickness_max',65 AS 'cycle' UNION
SELECT 181 AS 'thickness_min',185 AS 'thickness_max',70 AS 'cycle' UNION
SELECT 186 AS 'thickness_min',190 AS 'thickness_max',70 AS 'cycle' UNION
SELECT 191 AS 'thickness_min',195 AS 'thickness_max',70 AS 'cycle' UNION
SELECT 196 AS 'thickness_min',200 AS 'thickness_max',75 AS 'cycle'  
) AS T;
 