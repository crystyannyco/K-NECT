-- =====================================================
-- PSGC API Integration - Database Changes
-- =====================================================
-- This script adds the barangay_psgc_code column to the address table
-- Run this if the migration hasn't been executed yet
-- =====================================================

-- Add barangay_psgc_code column to address table
ALTER TABLE `address` 
ADD COLUMN `barangay_psgc_code` VARCHAR(20) NULL 
COMMENT 'Philippine Standard Geographic Code for the barangay' 
AFTER `barangay`;

-- =====================================================
-- Optional: Update existing records with PSGC codes
-- =====================================================
-- This maps the old barangay IDs (1-36) to PSGC codes
-- Only run if you have existing data with numeric barangay values

UPDATE `address` SET `barangay_psgc_code` = '051716001' WHERE `barangay` = '1' OR `barangay` = 'Antipolo';
UPDATE `address` SET `barangay_psgc_code` = '051716002' WHERE `barangay` = '2' OR `barangay` = 'Cristo Rey';
UPDATE `address` SET `barangay_psgc_code` = '051716003' WHERE `barangay` = '3' OR `barangay` = 'Del Rosario (Banao)';
UPDATE `address` SET `barangay_psgc_code` = '051716004' WHERE `barangay` = '4' OR `barangay` = 'Francia';
UPDATE `address` SET `barangay_psgc_code` = '051716005' WHERE `barangay` = '5' OR `barangay` = 'La Anunciacion';
UPDATE `address` SET `barangay_psgc_code` = '051716006' WHERE `barangay` = '6' OR `barangay` = 'La Medalla';
UPDATE `address` SET `barangay_psgc_code` = '051716007' WHERE `barangay` = '7' OR `barangay` = 'La Purisima';
UPDATE `address` SET `barangay_psgc_code` = '051716008' WHERE `barangay` = '8' OR `barangay` = 'La Trinidad';
UPDATE `address` SET `barangay_psgc_code` = '051716009' WHERE `barangay` = '9' OR `barangay` = 'Niño Jesus';
UPDATE `address` SET `barangay_psgc_code` = '051716010' WHERE `barangay` = '10' OR `barangay` = 'Perpetual Help';
UPDATE `address` SET `barangay_psgc_code` = '051716011' WHERE `barangay` = '11' OR `barangay` = 'Sagrada';
UPDATE `address` SET `barangay_psgc_code` = '051716012' WHERE `barangay` = '12' OR `barangay` = 'Salvacion';
UPDATE `address` SET `barangay_psgc_code` = '051716013' WHERE `barangay` = '13' OR `barangay` = 'San Agustin';
UPDATE `address` SET `barangay_psgc_code` = '051716014' WHERE `barangay` = '14' OR `barangay` = 'San Andres';
UPDATE `address` SET `barangay_psgc_code` = '051716015' WHERE `barangay` = '15' OR `barangay` = 'San Antonio';
UPDATE `address` SET `barangay_psgc_code` = '051716016' WHERE `barangay` = '16' OR `barangay` = 'San Francisco';
UPDATE `address` SET `barangay_psgc_code` = '051716017' WHERE `barangay` = '17' OR `barangay` = 'San Isidro';
UPDATE `address` SET `barangay_psgc_code` = '051716018' WHERE `barangay` = '18' OR `barangay` = 'San Jose';
UPDATE `address` SET `barangay_psgc_code` = '051716019' WHERE `barangay` = '19' OR `barangay` = 'San Juan';
UPDATE `address` SET `barangay_psgc_code` = '051716020' WHERE `barangay` = '20' OR `barangay` = 'San Miguel';
UPDATE `address` SET `barangay_psgc_code` = '051716021' WHERE `barangay` = '21' OR `barangay` = 'San Nicolas';
UPDATE `address` SET `barangay_psgc_code` = '051716022' WHERE `barangay` = '22' OR `barangay` = 'San Pedro';
UPDATE `address` SET `barangay_psgc_code` = '051716023' WHERE `barangay` = '23' OR `barangay` = 'San Rafael';
UPDATE `address` SET `barangay_psgc_code` = '051716024' WHERE `barangay` = '24' OR `barangay` = 'San Ramon';
UPDATE `address` SET `barangay_psgc_code` = '051716025' WHERE `barangay` = '25' OR `barangay` = 'San Roque';
UPDATE `address` SET `barangay_psgc_code` = '051716026' WHERE `barangay` = '26' OR `barangay` = 'Santiago';
UPDATE `address` SET `barangay_psgc_code` = '051716027' WHERE `barangay` = '27' OR `barangay` = 'San Vicente Norte';
UPDATE `address` SET `barangay_psgc_code` = '051716028' WHERE `barangay` = '28' OR `barangay` = 'San Vicente Sur';
UPDATE `address` SET `barangay_psgc_code` = '051716029' WHERE `barangay` = '29' OR `barangay` = 'Santa Cruz Norte';
UPDATE `address` SET `barangay_psgc_code` = '051716030' WHERE `barangay` = '30' OR `barangay` = 'Santa Cruz Sur';
UPDATE `address` SET `barangay_psgc_code` = '051716031' WHERE `barangay` = '31' OR `barangay` = 'Santa Elena';
UPDATE `address` SET `barangay_psgc_code` = '051716032' WHERE `barangay` = '32' OR `barangay` = 'Santa Isabel';
UPDATE `address` SET `barangay_psgc_code` = '051716033' WHERE `barangay` = '33' OR `barangay` = 'Santa Maria';
UPDATE `address` SET `barangay_psgc_code` = '051716034' WHERE `barangay` = '34' OR `barangay` = 'Santa Teresita';
UPDATE `address` SET `barangay_psgc_code` = '051716035' WHERE `barangay` = '35' OR `barangay` = 'Santo Domingo';
UPDATE `address` SET `barangay_psgc_code` = '051716036' WHERE `barangay` = '36' OR `barangay` = 'Santo Niño';

-- =====================================================
-- Verification Query
-- =====================================================
-- Run this to verify the column was added successfully
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_COMMENT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'address'
  AND COLUMN_NAME = 'barangay_psgc_code';

-- Check if any records have PSGC codes
SELECT COUNT(*) as total_records,
       COUNT(barangay_psgc_code) as records_with_psgc_code
FROM address;
