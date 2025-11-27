-- =====================================================
-- PSGC API Integration - Database Changes
-- =====================================================
-- This script updates the address table for PSGC API integration
-- Run these commands in order
-- =====================================================

-- =====================================================
-- STEP 1: Add barangay_psgc_code column if not exists
-- =====================================================
ALTER TABLE `address` 
ADD COLUMN IF NOT EXISTS `barangay_psgc_code` VARCHAR(20) NULL 
COMMENT 'Philippine Standard Geographic Code for the barangay' 
AFTER `barangay`;

-- =====================================================
-- STEP 2: Change barangay column from INT to VARCHAR
-- =====================================================
-- This allows storing barangay names instead of just numeric IDs
-- Existing numeric IDs (1-36) will be preserved and can be converted to names

ALTER TABLE `address` 
MODIFY COLUMN `barangay` VARCHAR(100) NULL 
COMMENT 'Barangay name (formerly numeric ID, now stores actual barangay name from PSGC API)';

-- =====================================================
-- STEP 3: Update region, province, municipality to store PSGC codes
-- =====================================================
-- These columns should store PSGC codes (9-digit format) instead of names

ALTER TABLE `address` 
MODIFY COLUMN `region` VARCHAR(20) NULL 
COMMENT 'PSGC Region Code (9 digits, format: ###000000)';

ALTER TABLE `address` 
MODIFY COLUMN `province` VARCHAR(20) NULL 
COMMENT 'PSGC Province Code (9 digits, format: #####0000)';

ALTER TABLE `address` 
MODIFY COLUMN `municipality` VARCHAR(20) NULL 
COMMENT 'PSGC Municipality Code (9 digits, format: #########)';

-- =====================================================
-- STEP 4: Convert existing legacy barangay IDs to names
-- =====================================================
-- This updates old numeric barangay IDs (1-36) to their actual names
-- Only run if you have existing data with numeric barangay values

UPDATE `address` SET `barangay` = 'Antipolo', `barangay_psgc_code` = '051716001' WHERE `barangay` = '1';
UPDATE `address` SET `barangay` = 'Cristo Rey', `barangay_psgc_code` = '051716002' WHERE `barangay` = '2';
UPDATE `address` SET `barangay` = 'Del Rosario (Banao)', `barangay_psgc_code` = '051716003' WHERE `barangay` = '3';
UPDATE `address` SET `barangay` = 'Francia', `barangay_psgc_code` = '051716004' WHERE `barangay` = '4';
UPDATE `address` SET `barangay` = 'La Anunciacion', `barangay_psgc_code` = '051716005' WHERE `barangay` = '5';
UPDATE `address` SET `barangay` = 'La Medalla', `barangay_psgc_code` = '051716006' WHERE `barangay` = '6';
UPDATE `address` SET `barangay` = 'La Purisima', `barangay_psgc_code` = '051716007' WHERE `barangay` = '7';
UPDATE `address` SET `barangay` = 'La Trinidad', `barangay_psgc_code` = '051716008' WHERE `barangay` = '8';
UPDATE `address` SET `barangay` = 'Niño Jesus', `barangay_psgc_code` = '051716009' WHERE `barangay` = '9';
UPDATE `address` SET `barangay` = 'Perpetual Help', `barangay_psgc_code` = '051716010' WHERE `barangay` = '10';
UPDATE `address` SET `barangay` = 'Sagrada', `barangay_psgc_code` = '051716011' WHERE `barangay` = '11';
UPDATE `address` SET `barangay` = 'Salvacion', `barangay_psgc_code` = '051716012' WHERE `barangay` = '12';
UPDATE `address` SET `barangay` = 'San Agustin', `barangay_psgc_code` = '051716013' WHERE `barangay` = '13';
UPDATE `address` SET `barangay` = 'San Andres', `barangay_psgc_code` = '051716014' WHERE `barangay` = '14';
UPDATE `address` SET `barangay` = 'San Antonio', `barangay_psgc_code` = '051716015' WHERE `barangay` = '15';
UPDATE `address` SET `barangay` = 'San Francisco', `barangay_psgc_code` = '051716016' WHERE `barangay` = '16';
UPDATE `address` SET `barangay` = 'San Isidro', `barangay_psgc_code` = '051716017' WHERE `barangay` = '17';
UPDATE `address` SET `barangay` = 'San Jose', `barangay_psgc_code` = '051716018' WHERE `barangay` = '18';
UPDATE `address` SET `barangay` = 'San Juan', `barangay_psgc_code` = '051716019' WHERE `barangay` = '19';
UPDATE `address` SET `barangay` = 'San Miguel', `barangay_psgc_code` = '051716020' WHERE `barangay` = '20';
UPDATE `address` SET `barangay` = 'San Nicolas', `barangay_psgc_code` = '051716021' WHERE `barangay` = '21';
UPDATE `address` SET `barangay` = 'San Pedro', `barangay_psgc_code` = '051716022' WHERE `barangay` = '22';
UPDATE `address` SET `barangay` = 'San Rafael', `barangay_psgc_code` = '051716023' WHERE `barangay` = '23';
UPDATE `address` SET `barangay` = 'San Ramon', `barangay_psgc_code` = '051716024' WHERE `barangay` = '24';
UPDATE `address` SET `barangay` = 'San Roque', `barangay_psgc_code` = '051716025' WHERE `barangay` = '25';
UPDATE `address` SET `barangay` = 'Santiago', `barangay_psgc_code` = '051716026' WHERE `barangay` = '26';
UPDATE `address` SET `barangay` = 'San Vicente Norte', `barangay_psgc_code` = '051716027' WHERE `barangay` = '27';
UPDATE `address` SET `barangay` = 'San Vicente Sur', `barangay_psgc_code` = '051716028' WHERE `barangay` = '28';
UPDATE `address` SET `barangay` = 'Santa Cruz Norte', `barangay_psgc_code` = '051716029' WHERE `barangay` = '29';
UPDATE `address` SET `barangay` = 'Santa Cruz Sur', `barangay_psgc_code` = '051716030' WHERE `barangay` = '30';
UPDATE `address` SET `barangay` = 'Santa Elena', `barangay_psgc_code` = '051716031' WHERE `barangay` = '31';
UPDATE `address` SET `barangay` = 'Santa Isabel', `barangay_psgc_code` = '051716032' WHERE `barangay` = '32';
UPDATE `address` SET `barangay` = 'Santa Maria', `barangay_psgc_code` = '051716033' WHERE `barangay` = '33';
UPDATE `address` SET `barangay` = 'Santa Teresita', `barangay_psgc_code` = '051716034' WHERE `barangay` = '34';
UPDATE `address` SET `barangay` = 'Santo Domingo', `barangay_psgc_code` = '051716035' WHERE `barangay` = '35';
UPDATE `address` SET `barangay` = 'Santo Niño', `barangay_psgc_code` = '051716036' WHERE `barangay` = '36';

-- =====================================================
-- STEP 5: Verification Queries
-- =====================================================
-- Run these to verify the changes were applied successfully

-- Check column structure
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    IS_NULLABLE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'address'
  AND COLUMN_NAME IN ('barangay', 'barangay_psgc_code', 'region', 'province', 'municipality')
ORDER BY ORDINAL_POSITION;

-- Check data conversion
SELECT 
    COUNT(*) as total_records,
    COUNT(barangay_psgc_code) as records_with_psgc_code,
    COUNT(CASE WHEN barangay REGEXP '^[0-9]+$' THEN 1 END) as records_with_numeric_barangay,
    COUNT(CASE WHEN barangay NOT REGEXP '^[0-9]+$' AND barangay IS NOT NULL THEN 1 END) as records_with_name_barangay
FROM address;

-- Sample records to verify conversion
SELECT 
    user_id,
    barangay,
    barangay_psgc_code,
    region,
    province,
    municipality
FROM address
LIMIT 10;
