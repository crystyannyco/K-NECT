-- Migration: Remove approval feature from documents table
-- Date: 2025-10-17
-- Description: Drop all approval-related columns from documents table
-- The system now uses a visibility-based access control without approval workflow

-- Backup recommendation: Run this before executing
-- CREATE TABLE documents_backup AS SELECT * FROM documents;

-- Drop approval-related columns
ALTER TABLE `documents` 
    DROP COLUMN IF EXISTS `approval_status`,
    DROP COLUMN IF EXISTS `approver`,
    DROP COLUMN IF EXISTS `approval_at`,
    DROP COLUMN IF EXISTS `approval_comment`;

-- Verification query (run after migration)
-- SHOW COLUMNS FROM documents;

-- Note: This migration removes the approval workflow entirely
-- Documents are now controlled by the visibility system (pederasyon/sk/kk)
