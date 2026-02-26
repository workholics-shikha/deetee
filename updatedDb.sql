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

-- //=================== 25-02-2026

189
13709
2334  
-------------------
130
13675
2454
-------------------
130
13525
2266
-------------------
124
13602
2371, 2370, 2374
-------------------
112
13383
2419
-------------------
84
13558
2312
-------------------
126
13427
1959, 1941, 1942, 1961,  1958
Delete - 1952,


DELETE FROM `pass_sheets` WHERE `cpoitemid` = 60887 AND `so_id` = 13427;  
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 60890  AND `so_id` = 13427 AND `id` > 4427;
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 60892  AND `so_id` = 13427 AND `id` > 4553;
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 60886  AND `so_id` = 13427 AND `id` > 4312;
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 60889  AND `so_id` = 13427 ;
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 61976  AND `so_id` = 13525 AND `id` > 5313;
DELETE FROM `pass_sheets` WHERE `so_id` = 13427 AND `subproduct_pid` = 1941;

DELETE FROM `pass_sheets` WHERE `cpoitemid` = 61162 AND `so_id` = 13383 AND `id` > 7190;

DELETE FROM `pass_sheets` WHERE `cpoitemid` = 62292 AND `so_id` = 13558 AND `id` > 6106;
 
DELETE ps1
FROM pass_sheets ps1
JOIN pass_sheets ps2
  ON ps1.so_id = ps2.so_id
  AND ps1.subproduct_pid = ps2.subproduct_pid
  AND ps1.pass_no = ps2.pass_no
  AND ps1.sr_no = ps2.sr_no
  AND ps1.cpoitemid = ps2.cpoitemid
  AND ps1.id > ps2.id
WHERE ps1.so_id = 13602
AND ps1.subproduct_pid = 2370;

UPDATE `pass_sheets` SET `id` = '6360' WHERE `pass_sheets`.`id` = 6298;
UPDATE `pass_sheets` SET `id` = '6352' WHERE `pass_sheets`.`id` = 6290;
UPDATE `pass_sheets` SET `id` = '6379' WHERE `pass_sheets`.`id` = 6330;
UPDATE `pass_sheets` SET `id` = '6376' WHERE `pass_sheets`.`id` = 6324;
 
DELETE FROM `pass_sheets` WHERE `cpoitemid` = 62119 AND `so_id` = 13602 AND `id` > 6741;

DELETE FROM `pass_sheets` WHERE `cpoitemid` = 62116 AND `so_id` = 13602 AND `id` > 6446;

DELETE FROM `pass_sheets` WHERE `cpoitemid` = 62466 AND `so_id` = 13675 AND `id` > 7395 ;
