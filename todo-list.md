Update SQL queries after upgradation (Started date: 13/02/2026)
 
OperationMaster  
-- 1. Change 'id' from BIGINT to INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
ALTER TABLE operation_masters 
MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT;

-- 2. Increase 'operation_name' length to VARCHAR(150) NOT NULL
ALTER TABLE operation_masters 
MODIFY operation_name VARCHAR(100) NOT NULL;
  
-- 4. Change 'parameter_input' from TEXT to VARCHAR(20) NULL
ALTER TABLE operation_masters 
MODIFY parameter_input VARCHAR(20) NULL;

-- 5. Change 'matrix' from TEXT to VARCHAR(50) NULL
ALTER TABLE operation_masters 
MODIFY matrix VARCHAR(20) NULL;

-- 6. Drop unused 'parameters' column if confirmed unused
ALTER TABLE operation_masters 
DROP COLUMN operation_comment,
DROP COLUMN parameters,
DROP COLUMN parameter3,
DROP COLUMN parameter4;

-- 7. Change 'operation_qr_code' length to VARCHAR(100) NULL
ALTER TABLE operation_masters 
MODIFY operation_qr_code VARCHAR(100) NULL;

-- 8. Change 'parameter1' to 'parameter4' length to VARCHAR(100) NULL
ALTER TABLE operation_masters 
MODIFY parameter1 VARCHAR(100) NULL,
MODIFY parameter2 VARCHAR(100) NULL;

-- 9. Add index on 'operation_name' for search and ordering performance
ALTER TABLE operation_masters ADD INDEX idx_operation_name (operation_name);
ALTER TABLE operation_masters ADD INDEX idx_unit (unit);
ALTER TABLE operation_masters ADD INDEX idx_matrix (matrix);

ERP_SalesOrder

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

// === 10-Nov ==============
ALTER TABLE `sales_order_products` CHANGE `tube_size` `is_route_card_locked` TINYINT NULL DEFAULT '0';

// === 11-Nov ==============
CREATE TABLE `deetee_prod`.`rc_review` (`id` INT NOT NULL AUTO_INCREMENT, `so_track_id` INT NOT NULL, `review` INT NOT NULL, `review_by` INT NOT NULL , `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB;

// 13-11-2025
wget -O /dev/null https://weblaunchpad.in/deetee_industries/so-list

// 14-11-2025
ALTER TABLE roles MODIFY permissions JSON NULL;

// 17-11-2025
ALTER TABLE `generic_qrcodes` CHANGE `qr_use_for` `qr_use_for` VARCHAR(130) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

// indexing in DB --------- (21-11-2025)

ALTER TABLE machine_health_monitorings ADD INDEX idx_master_id (master_id);
ALTER TABLE machine_health_monitorings ADD INDEX idx_start_date (start_date_time);
ALTER TABLE machine_health_monitorings ADD INDEX idx_end_date (end_date_time);
ALTER TABLE machine_health_monitorings ADD INDEX idx_monitor_for (monitor_for);
ALTER TABLE machine_health_monitorings ADD INDEX idx_created_at (created_at);

ALTER TABLE `erp_sales_orders` ADD INDEX idx_so_id (so_id);
ALTER TABLE `erp_sales_orders` ADD INDEX idx_so_no (so_no);

ALTER TABLE `machine_wise_operations` ADD INDEX idx_machine_id (machine_id);
ALTER TABLE `machine_wise_operations` ADD INDEX idx_operation_id (operation_id);

ALTER TABLE `notifications` ADD INDEX idx_machine_id (machine_id);

ALTER TABLE `operator_attendances` ADD INDEX idx_operator_id (operator_id);

ALTER TABLE `pass_sheets` ADD INDEX idx_cpoitemid (cpoitemid);

ALTER TABLE `sales_order_products` ADD INDEX idx_so_id (so_id);
ALTER TABLE `sales_order_products` ADD INDEX idx_product_id (product_id);
ALTER TABLE `sales_order_products` ADD INDEX idx_cpoitemid (cpoitemid);

ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_so_id (so_id);
ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_so_no (so_no);
ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_sales_order_product_id (sales_order_product_id);
ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_product_id (product_id);
ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_operation_id (operation_id);
ALTER TABLE `sales_order_product_operation_details` ADD INDEX idx_sub_product_id (sub_product_id);

ALTER TABLE `sales_order_trackings` ADD INDEX idx_so_id (so_id);
ALTER TABLE `sales_order_trackings` ADD INDEX idx_so_pid_primary (so_pid_primary); 
ALTER TABLE `sales_order_trackings` ADD INDEX idx_operation_id (operation_id);
ALTER TABLE `sales_order_trackings` ADD INDEX idx_sub_product_id (sub_product_id);
ALTER TABLE `sales_order_trackings` ADD INDEX idx_machine_id (machine_id);
ALTER TABLE `sales_order_trackings` ADD INDEX idx_operator_id (operator_id);

ALTER TABLE `subproduct_wise_operation` ADD INDEX idx_operation_id (operation_id);
ALTER TABLE `subproduct_wise_operation` ADD INDEX idx_sub_product_id (subproduct_id);
ALTER TABLE `subproduct_wise_operation` ADD INDEX idx_product_master_id (product_master_id);

ALTER TABLE `users` ADD INDEX idx_role (role);
ALTER TABLE `users` ADD INDEX idx_username (username);

// ============ ============ ============ 24-11-2025 ============ ============ ============ 

UPDATE `product_masters` SET `cycle_flow` = 'Available' WHERE `product_masters`.`id` = 16;
UPDATE `sales_order_products` SET `product_status` = 'Available' WHERE `product_id` = 16;

INSERT INTO `product_masters` (`id`, `unit_number`, `unit`, `group`, `erp_product`, `erp_nomenclature`, `product_modified_name`, `product_qr_code`, `status`, `product_flow`, `cycle_flow`, `created_at`, `updated_at`) VALUES (NULL, '2', 'RMR', 'I', 'Shafts', 'SHF', 'Shafts', '', 'Available', 'Available', 'Available', '2025-04-23 00:36:19', '2025-04-23 00:36:19');

INSERT INTO `sub_product` (`id`, `product_master_id`, `sub_product_name`, `product_flows`, `cycle_flow`, `erp_item_id`, `created_at`, `updated_at`) VALUES (NULL, '66', 'SHAFTS', 'Available', 'Available', NULL, '2025-04-23 06:06:23', '2025-04-23 06:06:23');
  
/**  update table = ict_rmr_2matrix 
AND table = subproduct_wise_operation    
AND table = ict_keyway_operations    

 on live  **/
 
INSERT INTO ict_rmr_2matrix 
(
    sub_product_id,
    operation,
    machine_id,
    length_min,
    length_max,
    od_min,
    od_max,
    cycle_time_per_pc,
    loading_unloading_time,
    total_cycle_time,
    table_parts,
    created_at,
    updated_at
)
SELECT 
    sub_product_id,
    14 AS operation,     
    machine_id,
    length_min,
    length_max,
    od_min,
    od_max,
    cycle_time_per_pc,
    loading_unloading_time,
    total_cycle_time,
    table_parts,
    NOW(),
    NOW()
FROM ict_rmr_2matrix
WHERE  `sub_product_id` = 29 AND `operation` = 13 ;
 

// == Grinding-1

INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
0 as `od_min`, 75 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',60 AS 'cycle',10 AS 'time',70 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',60 AS 'cycle',10 AS 'time',70 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',60 AS 'cycle',10 AS 'time',70 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',60 AS 'cycle',10 AS 'time',70 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',60 AS 'cycle',10 AS 'time',70 AS 'total'      
) AS T;
 

 INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
75.1 as `od_min`, 100 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',65 AS 'cycle',10 AS 'time',75 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',65 AS 'cycle',10 AS 'time',75 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',65 AS 'cycle',10 AS 'time',75 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',65 AS 'cycle',10 AS 'time',75 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',65 AS 'cycle',10 AS 'time',75 AS 'total'       
) AS T;


 INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
100.1 as `od_min`, 150 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',70 AS 'cycle',10 AS 'time',80 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',70 AS 'cycle',10 AS 'time',80 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',70 AS 'cycle',10 AS 'time',80 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',70 AS 'cycle',10 AS 'time',80 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',70 AS 'cycle',10 AS 'time',80 AS 'total'        
) AS T;


 INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
150.1 as `od_min`, 200 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',75 AS 'cycle',10 AS 'time',85 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',75 AS 'cycle',10 AS 'time',85 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',75 AS 'cycle',10 AS 'time',85 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',75 AS 'cycle',10 AS 'time',85 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',75 AS 'cycle',10 AS 'time',85 AS 'total'  
) AS T;


 INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
200.1 as `od_min`, 250 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',80 AS 'cycle',10 AS 'time',90 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',80 AS 'cycle',10 AS 'time',90 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',80 AS 'cycle',10 AS 'time',90 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',80 AS 'cycle',10 AS 'time',90 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',80 AS 'cycle',10 AS 'time',90 AS 'total'  
) AS T;


 INSERT INTO `ict_rmr_2matrix` (`sub_product_id`, `table_parts`, `operation`, `machine_id`, `od_min`, `od_max`,
`length_min`, `length_max`, `cycle_time_per_pc`, `loading_unloading_time`, `total_cycle_time`, `created_at`, `updated_at`)
SELECT 29 as `sub_product_id`, 2 as `table_parts`, 22 as `operation`, NULL as `machine_id`, 
250.1 as `od_min`, 300 as `od_max`, lmin AS `length_min`, lmax AS `length_max`, cycle AS `cycle_time_per_pc`, time AS `loading_unloading_time`, total AS `total_cycle_time`, NOW(),NOW()
FROM
(
SELECT 400 AS 'lmin',1100 AS 'lmax',85 AS 'cycle',10 AS 'time',95 AS 'total' UNION
SELECT 1100 AS 'lmin',1700 AS 'lmax',85 AS 'cycle',10 AS 'time',95 AS 'total' UNION
SELECT 1700 AS 'lmin',2000 AS 'lmax',85 AS 'cycle',10 AS 'time',95 AS 'total' UNION
SELECT 2000 AS 'lmin',2500 AS 'lmax',85 AS 'cycle',10 AS 'time',95 AS 'total' UNION
SELECT 2500 AS 'lmin',3200 AS 'lmax',85 AS 'cycle',10 AS 'time',95 AS 'total'  
) AS T;

//===15/12/25
CREATE INDEX idx_erp_sales_orders_created_at ON erp_sales_orders (created_at);

//------------------------------------------------------------------------------------------------
https://whatsappshayari.com/deetee/admin/machine-details/2?from_date=2025-11-01&to_date=2025-12-27

// ============ 26-12-2025 ============ 

ALTER TABLE `pass_sheets` ADD `so_id` BIGINT NULL DEFAULT NULL AFTER `id`;
ALTER TABLE `pass_sheets` ADD `subproduct_id` BIGINT NULL DEFAULT NULL AFTER `so_id`;

UPDATE `machine_master` SET `machine` = 'Premeto – I' WHERE `machine_master`.`id` = 23;
INSERT INTO `machine_master` (`id`, `unit_number`, `unit_name`, `machine`, `machine_image`, `machine_type`, `section`, `sub_section`, `machine_qr_code`, `machine_status`, `operator_id`, `tracking_id`, `created_at`, `updated_at`) VALUES (NULL, '2', 'RMR', 'Premeto – II', NULL, 'Cylindrical Grinding', 'Production', 'Grinding', 'premeto-1763386655.png', 'active', '296', '4937', '2025-04-17 11:24:10', '2025-12-19 19:29:08');

INSERT INTO `machine_wise_operations` (`id`, `machine_id`, `operation_id`, `created_at`, `updated_at`) VALUES (NULL, '152', '21', NULL, NULL), (NULL, '152', '22', NULL, NULL), (NULL, '152', '152', NULL, NULL), (NULL, '152', '24', NULL, NULL), (NULL, '152', '25', NULL, NULL), (NULL, '152', '26', NULL, NULL), (NULL, '152', '27', NULL, NULL), (NULL, '152', '28', NULL, NULL);

 // ===================== 31-12-2025 // =====================

DELETE FROM subproduct_wise_operation WHERE `subproduct_wise_operation`.`id` = 640;
DELETE FROM subproduct_wise_operation WHERE `subproduct_wise_operation`.`id` = 632;

INSERT INTO `subproduct_wise_operation` (`id`, `product_master_id`, `subproduct_id`, `operation_id`, `operation_name`, `sub_operations`, `unit`, `parameter1_label`, `parameter1_value`, `parameter2_label`, `parameter2_value`, `parameter1_value_set`, `parameter2_value_set`, `operation_type`, `fixed_ICT`, `created_at`, `updated_at`, `operation_availabilty`) VALUES (NULL, '2', '5', '22', 'Grinding-1', 'Bore Semi-final & Final Grinding of coupling <br> Both side journal final & Berral semi final grinding only of roll.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1'), (NULL, '2', '5', '23', 'Grinding-2', 'Berral grinding with coupling <br> Journal Dia & Facing grinding - Both Side.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1');
 
INSERT INTO `subproduct_wise_operation` (`id`, `product_master_id`, `subproduct_id`, `operation_id`, `operation_name`, `sub_operations`, `unit`, `parameter1_label`, `parameter1_value`, `parameter2_label`, `parameter2_value`, `parameter1_value_set`, `parameter2_value_set`, `operation_type`, `fixed_ICT`, `created_at`, `updated_at`, `operation_availabilty`) VALUES (NULL, '2', '6', '22', 'Grinding-1', 'Bore Semi-final & Final Grinding of coupling <br> Both side journal final & Berral semi final grinding only of roll.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1'), (NULL, '2', '6', '23', 'Grinding-2', 'Berral grinding with coupling <br> Journal Dia & Facing grinding - Both Side.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1');

<!-- // used this query on 15-01-2026 -->

SELECT * FROM `sales_order_product_operation_details` WHERE `so_id` IN (11337) ORDER BY `sales_order_product_operation_details`.`sales_order_product_id` ASC


<!-- // do not touch in-progess data  -->
 
SELECT * FROM sales_order_product_operation_details WHERE (processed_qty LIKE '%in-progress%' OR processed_qty LIKE '%partial%') AND qty IS NOT NULL AND (qty1 IS NULL OR qty1 <> qty) ORDER BY id ASC
