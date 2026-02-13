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

