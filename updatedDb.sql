CREATE INDEX idx_sot_status_end
ON sales_order_trackings (roll_status, end_date_time);

CREATE INDEX idx_sot_grouping
ON sales_order_trackings (so_id, operation_id, so_product_id, sub_product_id, pass_id, end_date_time);

CREATE INDEX idx_eso_soid_unitid
ON erp_sales_orders (so_id, so_unitid);

-- // 02-02-2026

ALTER TABLE `sales_order_products` CHANGE `so_id` `so_id` BIGINT(255) NOT NULL;

UPDATE `sales_order_product_operation_details` SET `processed_qty` = '[{"pass_sheet_id":61,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":62,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":63,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":64,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":65,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":66,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":67,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":68,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":69,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":70,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":71,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":72,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":73,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":74,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":75,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":76,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":77,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":78,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":79,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":80,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":81,"quantity":1,"status":"not-started","updated_by":0},{"pass_sheet_id":82,"quantity":1,"status":"not-started","updated_by":0}]'
WHERE `so_id` = 12416  AND `sales_order_product_id` = 300 


DELETE FROM `sales_order_product_operation_details` WHERE `so_id` IN (12625, 12723, 12795, 13361, 13312, 13309, 13304, 13250, 13235, 13219, 13177, );
DELETE FROM `pass_sheets` WHERE `so_id`  IN (12625, 12723, 12795, 13361, 13312, 13309, 13304, 13250, 13235, 13219, 13177, );
UPDATE `sales_order_products` SET `sub_product_id` = NULL WHERE `so_id`  IN (12625, 12723, 12795, 13361, 13312, 13309, 13304, 13250, 13235, 13219, 13177, );
DELETE FROM `sales_order_trackings` WHERE `so_id`  IN (12625, 12723, 12795, 13361, 13312, 13309, 13304, 13250, 13235, 13219, 13177, );


ALTER TABLE sales_order_product_operation_details
ADD processed_qty_copy TEXT AFTER processed_qty;
 
ALTER TABLE sales_order_trackings
ADD pass_id_copy INT AFTER pass_id;

UPDATE sales_order_product_operation_details
SET processed_qty_copy = processed_qty
WHERE processed_qty_copy IS NULL;

UPDATE sales_order_trackings
SET pass_id_copy = pass_id
WHERE pass_id IS NOT NULL;

UPDATE pass_sheets ps
JOIN sales_order_products sop
  ON sop.so_id = ps.so_id
 AND sop.cpoitemid = ps.cpoitemid
SET ps.subproduct_pid = sop.id
WHERE ps.subproduct_pid IS NULL;

-- //===================

INSERT INTO `subproduct_wise_operation` (`id`, `s_no`, `product_master_id`, `subproduct_id`, `operation_id`, `operation_name`, `sub_operations`, `unit`, `parameter1_label`, `parameter1_value`, `parameter2_label`, `parameter2_value`, `parameter1_value_set`, `parameter2_value_set`, `operation_type`, `fixed_ICT`, `created_at`, `updated_at`, `operation_availabilty`) VALUES (NULL, NULL, '2', '6', '22', 'Grinding-1', 'Bore Semi-final & Final Grinding of coupling <br> Both side journal final & Berral semi final grinding only of roll.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1');

INSERT INTO `subproduct_wise_operation` (`id`, `s_no`, `product_master_id`, `subproduct_id`, `operation_id`, `operation_name`, `sub_operations`, `unit`, `parameter1_label`, `parameter1_value`, `parameter2_label`, `parameter2_value`, `parameter1_value_set`, `parameter2_value_set`, `operation_type`, `fixed_ICT`, `created_at`, `updated_at`, `operation_availabilty`) VALUES (NULL, NULL, '2', '6', '23', 'Grinding-2', 'Berral grinding with coupling <br> Journal Dia & Facing grinding - Both Side.', 'RMR', 'Outer Diameter', 'size1', 'Total Length', 'size3', NULL, NULL, '', NULL, NULL, NULL, '1');