-- Shishi Footsteps ERP cPanel update: 2026-08-05
-- Run this while the target database is selected in phpMyAdmin.
-- This script only adds the columns required by Manage 2FA.

SET @sql := IF(
    EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_secret'
    ),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_secret TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_pending_secret'
    ),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_pending_secret TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (
        SELECT 1 FROM information_schema.columns
        WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_confirmed_at'
    ),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_confirmed_at DATETIME NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;
