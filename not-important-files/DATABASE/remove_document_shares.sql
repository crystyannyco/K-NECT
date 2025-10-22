-- =====================================================
-- REMOVE DOCUMENT SHARES FEATURE FROM DATABASE
-- =====================================================
-- Date: 2025-10-16
-- Description: This script removes the document_shares table
--              and all associated data from the database.
-- 
-- IMPORTANT: Backup your database before running this!
-- =====================================================

-- Display current state
SELECT 'Checking document_shares table...' AS Status;
SELECT COUNT(*) AS total_shares FROM document_shares;

-- Drop the table
SELECT 'Dropping document_shares table...' AS Status;
DROP TABLE IF EXISTS `document_shares`;

-- Verify removal
SELECT 'Verifying removal...' AS Status;
SHOW TABLES LIKE 'document_shares';

SELECT 'Document shares feature successfully removed from database!' AS Status;
