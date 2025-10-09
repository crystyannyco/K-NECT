-- ============================================================================
-- Document Management Module - Visibility System Migration
-- Date: October 8, 2025
-- Purpose: Remove approval workflow and implement new visibility system
-- ============================================================================

-- Step 0: Ensure barangay table has primary key on barangay_id
ALTER TABLE `barangay`
ADD PRIMARY KEY (`barangay_id`);

-- Step 1: Add barangay_id column for barangay-specific document filtering
ALTER TABLE `documents` 
ADD COLUMN `barangay_id` INT(11) UNSIGNED NULL AFTER `visibility`,
ADD COLUMN `visibility_scope` ENUM('all', 'specific_barangay') DEFAULT 'all' AFTER `barangay_id`;

-- Step 2: Add foreign key constraint for barangay_id
ALTER TABLE `documents`
ADD CONSTRAINT `fk_documents_barangay`
FOREIGN KEY (`barangay_id`) REFERENCES `barangay`(`barangay_id`)
ON DELETE SET NULL
ON UPDATE CASCADE;

-- Step 3: Modify visibility enum to support new options
-- Note: This requires recreating the column
ALTER TABLE `documents` 
MODIFY COLUMN `visibility` ENUM('pederasyon', 'sk', 'kk') DEFAULT 'pederasyon';

-- Step 4: Update existing data - map old visibility to new system
-- Old 'SK' documents → keep as 'sk'
-- Old 'KK' documents → keep as 'kk'
-- Documents uploaded by super_admin/pederasyon → set to 'pederasyon'
UPDATE `documents` d
INNER JOIN `user` u ON LOWER(TRIM(d.uploaded_by)) = LOWER(TRIM(u.username))
SET d.visibility = 'pederasyon'
WHERE u.user_type = 3; -- Pederasyon users

-- Step 5: Set barangay_id for SK and KK documents based on uploader's barangay
UPDATE `documents` d
INNER JOIN `user` u ON LOWER(TRIM(d.uploaded_by)) = LOWER(TRIM(u.username))
INNER JOIN `address` a ON u.id = a.user_id
SET d.barangay_id = a.barangay_id
WHERE u.user_type IN (1, 2) -- KK and SK users
AND d.visibility IN ('sk', 'kk');

-- Step 6: Set visibility_scope to 'specific_barangay' for documents with barangay_id
UPDATE `documents`
SET visibility_scope = 'specific_barangay'
WHERE barangay_id IS NOT NULL;

-- Step 7: Remove approval-related columns
ALTER TABLE `documents`
DROP COLUMN `approval_status`,
DROP COLUMN `approver`,
DROP COLUMN `approval_at`,
DROP COLUMN `approval_comment`;

-- Step 8: Update title column to be NOT NULL with default
ALTER TABLE `documents`
MODIFY COLUMN `title` VARCHAR(255) NULL;

-- Step 9: Add index for better query performance
CREATE INDEX `idx_documents_visibility` ON `documents`(`visibility`);
CREATE INDEX `idx_documents_barangay` ON `documents`(`barangay_id`);
CREATE INDEX `idx_documents_visibility_barangay` ON `documents`(`visibility`, `barangay_id`);

-- ============================================================================
-- Rollback Script (in case you need to revert)
-- ============================================================================
/*
-- Rollback: Restore approval columns
ALTER TABLE `documents`
ADD COLUMN `approval_status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending' AFTER `mimetype`,
ADD COLUMN `approver` VARCHAR(100) NULL AFTER `approval_status`,
ADD COLUMN `approval_at` DATETIME NULL AFTER `approver`,
ADD COLUMN `approval_comment` TEXT NULL AFTER `approval_at`;

-- Rollback: Restore old visibility enum
ALTER TABLE `documents`
MODIFY COLUMN `visibility` ENUM('SK', 'KK') DEFAULT 'SK';

-- Rollback: Remove new columns
ALTER TABLE `documents`
DROP FOREIGN KEY `fk_documents_barangay`,
DROP COLUMN `visibility_scope`,
DROP COLUMN `barangay_id`;

-- Rollback: Remove indexes
DROP INDEX `idx_documents_visibility` ON `documents`;
DROP INDEX `idx_documents_barangay` ON `documents`;
DROP INDEX `idx_documents_visibility_barangay` ON `documents`;
*/

-- ============================================================================
-- Verification Queries
-- ============================================================================
/*
-- Check document distribution by visibility
SELECT visibility, visibility_scope, COUNT(*) as count
FROM documents
GROUP BY visibility, visibility_scope;

-- Check documents with barangay assignment
SELECT 
    d.id, 
    d.filename, 
    d.visibility, 
    d.visibility_scope,
    d.barangay_id,
    b.name as barangay_name,
    d.uploaded_by
FROM documents d
LEFT JOIN barangay b ON d.barangay_id = b.barangay_id
ORDER BY d.id DESC
LIMIT 20;

-- Verify no orphaned records
SELECT COUNT(*) as orphaned_count
FROM documents
WHERE barangay_id IS NOT NULL 
AND barangay_id NOT IN (SELECT barangay_id FROM barangay);
*/
