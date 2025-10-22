-- Migration: Remove document_shares feature
-- Date: 2025-10-16
-- Description: Drop the document_shares table and related indexes

-- Drop the document_shares table
DROP TABLE IF EXISTS `document_shares`;

-- Note: This will permanently remove all document sharing data
-- Make sure to backup your database before running this migration if needed
