-- ============================================================
-- Shishi Footsteps ERP - Database update for cPanel
-- Date: 2026-08-05
-- Upload to phpMyAdmin (select your database first, then Import).
-- 100% idempotent: safe to run even if the schema already has
-- some of these columns/tables. Nothing is dropped or reset.
-- ============================================================

SET NAMES utf8mb4;

-- ============================================================
-- 1. USERS - Manage 2FA columns
-- ============================================================
SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_secret'),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_secret TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_pending_secret'),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_pending_secret TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'two_factor_confirmed_at'),
    'SELECT 1',
    'ALTER TABLE users ADD COLUMN two_factor_confirmed_at DATETIME NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ============================================================
-- 2. QUOTATIONS - link quotations to Travel Requests + proposal
--    status fields used by the Travel Request workspace
-- ============================================================
SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'request_id'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN request_id BIGINT UNSIGNED NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'trip_theme'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN trip_theme VARCHAR(255) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'is_lms'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN is_lms TINYINT(1) NOT NULL DEFAULT 0'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'is_mobile_sale'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN is_mobile_sale TINYINT(1) NOT NULL DEFAULT 0'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'pre_confirmed_at'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN pre_confirmed_at DATETIME NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'pre_confirmed_by'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN pre_confirmed_by BIGINT UNSIGNED NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'confirmation_date'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN confirmation_date DATETIME NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'quotations' AND column_name = 'cancellation_date'),
    'SELECT 1',
    'ALTER TABLE quotations ADD COLUMN cancellation_date DATETIME NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Index on quotations.request_id (skip if it already exists)
SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'quotations' AND index_name = 'quotations_request_id_index'),
    'SELECT 1',
    'ALTER TABLE quotations ADD INDEX quotations_request_id_index (request_id)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- Link existing quotations to their Travel Requests (from converted_to_quote_id)
UPDATE quotations q INNER JOIN requests r ON r.converted_to_quote_id = q.id
SET q.request_id = r.id WHERE q.request_id IS NULL;

-- ============================================================
-- 3. PROPOSAL WORKFLOWS - proposal system tables (safe no-op if
--    already present). Needed by the Travel Request workspace.
-- ============================================================
CREATE TABLE IF NOT EXISTS proposal_workflows (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    seller_id BIGINT UNSIGNED NULL,
    country VARCHAR(255) NOT NULL DEFAULT 'Tanzania',
    proposal_type VARCHAR(255) NOT NULL DEFAULT 'Itinerary',
    client_token VARCHAR(64) NULL,
    client_link_enabled TINYINT(1) NOT NULL DEFAULT 1,
    client_link_expires_at TIMESTAMP NULL,
    quotation_checked_at TIMESTAMP NULL,
    leader_checked_at TIMESTAMP NULL,
    confirmation_sent_at TIMESTAMP NULL,
    itinerary_completed_at TIMESTAMP NULL,
    jeeps_planned_at TIMESTAMP NULL,
    daily_movements_checked_at TIMESTAMP NULL,
    pre_departure_checked_at TIMESTAMP NULL,
    planning_note TEXT NULL,
    whatsapp_status VARCHAR(255) NULL,
    customer_message TEXT NULL,
    arrival_time VARCHAR(10) NULL,
    arrival_location VARCHAR(255) NULL,
    arrival_flight VARCHAR(255) NULL,
    departure_time VARCHAR(10) NULL,
    departure_location VARCHAR(255) NULL,
    departure_flight VARCHAR(255) NULL,
    dietary_requests TEXT NULL,
    announcements TEXT NULL,
    customer_notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    UNIQUE KEY proposal_workflows_quotation_id_unique (quotation_id),
    UNIQUE KEY proposal_workflows_client_token_unique (client_token),
    CONSTRAINT proposal_workflows_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations (id) ON DELETE CASCADE,
    CONSTRAINT proposal_workflows_seller_id_foreign FOREIGN KEY (seller_id) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- proposal_workflows: add any columns that may be missing
SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'country'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN country VARCHAR(255) NOT NULL DEFAULT ''Tanzania'''
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'proposal_type'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN proposal_type VARCHAR(255) NOT NULL DEFAULT ''Itinerary'''
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'quotation_checked_at'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN quotation_checked_at TIMESTAMP NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'leader_checked_at'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN leader_checked_at TIMESTAMP NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'client_token'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN client_token VARCHAR(64) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND index_name = 'proposal_workflows_client_token_unique'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD UNIQUE KEY proposal_workflows_client_token_unique (client_token)'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'client_link_enabled'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN client_link_enabled TINYINT(1) NOT NULL DEFAULT 1'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'client_link_expires_at'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN client_link_expires_at TIMESTAMP NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'customer_message'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN customer_message TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'arrival_time'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN arrival_time VARCHAR(10) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'arrival_location'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN arrival_location VARCHAR(255) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'arrival_flight'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN arrival_flight VARCHAR(255) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'departure_time'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN departure_time VARCHAR(10) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'departure_location'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN departure_location VARCHAR(255) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'departure_flight'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN departure_flight VARCHAR(255) NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'dietary_requests'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN dietary_requests TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'announcements'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN announcements TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

SET @sql := IF(
    EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema = DATABASE() AND table_name = 'proposal_workflows' AND column_name = 'customer_notes'),
    'SELECT 1',
    'ALTER TABLE proposal_workflows ADD COLUMN customer_notes TEXT NULL'
);
PREPARE stmt FROM @sql; EXECUTE stmt; DEALLOCATE PREPARE stmt;

-- ============================================================
-- 4. Proposal workspace tables (safe no-op if already present)
-- ============================================================
CREATE TABLE IF NOT EXISTS proposal_travelers (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    salutation VARCHAR(20) NULL,
    first_name VARCHAR(255) NOT NULL,
    surname VARCHAR(255) NOT NULL,
    date_of_birth DATE NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT proposal_travelers_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS proposal_adjustments (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    type VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    calculation_type VARCHAR(255) NOT NULL DEFAULT 'fixed_price',
    unit_amount DECIMAL(14,2) NOT NULL DEFAULT 0,
    quantity DECIMAL(10,2) NOT NULL DEFAULT 1,
    currency VARCHAR(3) NOT NULL DEFAULT 'USD',
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT proposal_adjustments_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS proposal_documents (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    uploaded_by BIGINT UNSIGNED NULL,
    category VARCHAR(255) NOT NULL,
    file_name VARCHAR(255) NOT NULL,
    path VARCHAR(255) NOT NULL,
    mime_type VARCHAR(255) NULL,
    size BIGINT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT proposal_documents_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations (id) ON DELETE CASCADE,
    CONSTRAINT proposal_documents_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS proposal_snapshots (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    quotation_id BIGINT UNSIGNED NOT NULL,
    created_by BIGINT UNSIGNED NULL,
    status VARCHAR(255) NOT NULL,
    price DECIMAL(14,2) NOT NULL DEFAULT 0,
    exchange_rate DECIMAL(12,6) NOT NULL DEFAULT 1,
    label VARCHAR(255) NULL,
    snapshot_data LONGTEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT proposal_snapshots_quotation_id_foreign FOREIGN KEY (quotation_id) REFERENCES quotations (id) ON DELETE CASCADE,
    CONSTRAINT proposal_snapshots_created_by_foreign FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS reservation_emails (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reservation_id BIGINT UNSIGNED NOT NULL,
    sent_by BIGINT UNSIGNED NULL,
    recipient VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'sent',
    error TEXT NULL,
    sent_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    PRIMARY KEY (id),
    CONSTRAINT reservation_emails_reservation_id_foreign FOREIGN KEY (reservation_id) REFERENCES reservations (id) ON DELETE CASCADE,
    CONSTRAINT reservation_emails_sent_by_foreign FOREIGN KEY (sent_by) REFERENCES users (id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DONE. Verify with:
--   SELECT column_name FROM information_schema.columns
--   WHERE table_schema = DATABASE() AND table_name = 'quotations';
-- ============================================================

-- ============================================================
-- OPTIONAL - Admin login update (ONLY if you still log in with
-- the old admin@safariflow.com account and want the new one).
-- Change the user id (1) to match your admin user if needed.
-- Uncomment and run these two statements.
-- ============================================================
-- UPDATE users SET email = 'erp@biznapoa.com'
--   WHERE id = 1 AND email = 'admin@safariflow.com';
--
-- UPDATE users SET password =
--   '$2b$10$qI5dAmdprxEo.Hj9wn3Pm.2h5WJjuz.CLxUQSI8/6URW91t8RhRFC'
--   WHERE id = 1;
