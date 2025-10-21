-- Migration: Add barangay_id and visibility_scope columns to documents table
-- Date: 2025-10-17
-- Description: Add columns to support barangay-specific document visibility

ALTER TABLE `documents` 
    ADD COLUMN `barangay_id` INT NULL DEFAULT NULL COMMENT 'Barangay restriction (NULL = city-wide)' AFTER `visibility`,
    ADD COLUMN `visibility_scope` ENUM('all', 'specific_barangay') DEFAULT 'all' COMMENT 'Visibility scope: all barangays or specific barangay' AFTER `barangay_id`;

-- Add foreign key constraint (optional - only if barangay table has barangay_id column)
-- ALTER TABLE `documents` ADD CONSTRAINT `fk_documents_barangay` 
--     FOREIGN KEY (`barangay_id`) REFERENCES `barangay`(`barangay_id`) ON DELETE SET NULL ON UPDATE CASCADE;

-- Verification query
-- SHOW COLUMNS FROM documents WHERE Field IN ('barangay_id', 'visibility_scope');
