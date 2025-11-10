ALTER TABLE `sales_order_product_operation_details` ADD `process_status` ENUM('pending','in-process','completed') NOT NULL DEFAULT 'pending' AFTER `processed_qty`;

<!-- // 19 july 2025 -->
ALTER TABLE `machine_health_monitorings` CHANGE `start_date_time` `start_date_time` DATETIME NOT NULL, CHANGE `end_date_time` `end_date_time` DATETIME NULL DEFAULT NULL;

<!-- // 19 august 2025 -->
ALTER TABLE `erp_sales_orders` 
ADD COLUMN `soquantity` INT(11) AFTER `so_no`,
ADD COLUMN `so_date` DATE AFTER `soquantity`,
ADD COLUMN `so_deliverytimeline` TEXT COLLATE utf8mb4_unicode_ci AFTER `so_date`,
ADD COLUMN `so_unitid` TINYINT(4) AFTER `so_deliverytimeline`,
ADD COLUMN `so_unitname` TINYTEXT COLLATE utf8mb4_unicode_ci AFTER `so_unitid`,
ADD COLUMN `so_groupid` TINYINT(4) AFTER `so_unitname`,
ADD COLUMN `so_group` TINYTEXT COLLATE utf8mb4_unicode_ci AFTER `so_groupid`;

ALTER TABLE `erp_sales_orders` CHANGE `src_status` `scr_status` VARCHAR(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;
ALTER TABLE `erp_sales_orders` DROP `so_customerid`;
ALTER TABLE `erp_sales_orders` CHANGE `so_id` `so_id` BIGINT(255) NOT NULL;
ALTER TABLE `sales_order_trackings` ADD `ideal_time` INTNULL DEFAULT NULL AFTER `end_date_time`;

DROP TABLE `subproduct_wise_operation_old`;
DROP TABLE `material_with_cycle_time`;

// error ========== 25/08/25
ALTER TABLE `sales_order_product_operation_details` ADD `cycle_time_value2` DECIMAL(8,2) NULL DEFAULT NULL AFTER `cycle_time`;
///===========
 
UPDATE `sales_order_product_operation_details` SET `product_id` = 7 WHERE `so_id` = 12189
UPDATE `sales_order_product_operation_details` SET `product_id` = 7 WHERE `sub_product_id` = 14
DELETE FROM machine_master WHERE `machine_master`.`id` = 145 // duplicate machine

//======================
DROP TABLE `length_with_cycle_time`, `material_with_cycle_time`, `subproduct_wise_operation_old`, `subproduct_wise_operation_old1`, `tapping_with_cycle_time`, `thickness_with_cycle_time`, `width_with_cycle_time`;

//======================
ALTER TABLE `sales_order_trackings` ADD `time_taken`  INT(11) NULL DEFAULT NULL AFTER `ideal_cycle_time`;
//====== 10 sep 2025

DROP TABLE `bearing_seat_with_cycle_time`, `bore_with_cycle_time`, `corners_with_cycle_time`, `length_with_cycle_time`, `material_with_cycle_time`, `tapping_with_cycle_time`, `thickness_with_cycle_time`, `width_with_cycle_time`;
  
//============ 30-09-2025 ===============
ALTER TABLE `sales_order_product_operation_details` ADD `completed_qty` INT NOT NULL DEFAULT '0' AFTER `qty`;

/=== === update query to update count of completed qty === ===/

UPDATE sales_order_product_operation_details sod
LEFT JOIN (
    SELECT sod2.id AS row_id,
           SUM(
             CASE
               WHEN JSON_UNQUOTE(JSON_EXTRACT(sod2.processed_qty, CONCAT('$[', seq.n, '].status'))) = 'completed'
               THEN 1 ELSE 0
             END
           ) AS completed_cnt
    FROM sales_order_product_operation_details sod2
    JOIN (
      -- numbers 0..49 (adjust if you need larger arrays)
      SELECT 0 AS n UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
      UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
      UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
      UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
      UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
      UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
      UNION ALL SELECT 30 UNION ALL SELECT 31 UNION ALL SELECT 32 UNION ALL SELECT 33 UNION ALL SELECT 34
      UNION ALL SELECT 35 UNION ALL SELECT 36 UNION ALL SELECT 37 UNION ALL SELECT 38 UNION ALL SELECT 39
      UNION ALL SELECT 40 UNION ALL SELECT 41 UNION ALL SELECT 42 UNION ALL SELECT 43 UNION ALL SELECT 44
      UNION ALL SELECT 45 UNION ALL SELECT 46 UNION ALL SELECT 47 UNION ALL SELECT 48 UNION ALL SELECT 49
    ) seq ON JSON_LENGTH(sod2.processed_qty) > seq.n
    GROUP BY sod2.id
) t ON sod.id = t.row_id
SET sod.completed_qty = COALESCE(t.completed_cnt, 0);

TRUNCATE TABLE `deetee_prod_live`.`reports`;
 
// ===== 08-10-2025

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

// === 10-Nov ==============

ALTER TABLE `sales_order_products` CHANGE `tube_size` `is_route_card_locked` TINYINT NULL DEFAULT '0';