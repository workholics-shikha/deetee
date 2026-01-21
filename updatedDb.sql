CREATE INDEX idx_sot_status_end
ON sales_order_trackings (roll_status, end_date_time);

CREATE INDEX idx_sot_grouping
ON sales_order_trackings (so_id, operation_id, so_product_id, sub_product_id, pass_id, end_date_time);

CREATE INDEX idx_eso_soid_unitid
ON erp_sales_orders (so_id, so_unitid);

