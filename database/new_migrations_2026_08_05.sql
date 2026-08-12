-- ============================================================
-- NEW MIGRATION - 2026-08-05  -  phpMyAdmin only
--   2026_08_05_000004_link_quotations_to_requests.php
-- (2FA columns already exist / not needed)
-- Plain statements, run once. Paste in phpMyAdmin -> SQL -> Go.
-- ============================================================

ALTER TABLE quotations ADD COLUMN request_id BIGINT UNSIGNED NULL;
ALTER TABLE quotations ADD COLUMN trip_theme VARCHAR(255) NULL;
ALTER TABLE quotations ADD COLUMN is_lms TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE quotations ADD COLUMN is_mobile_sale TINYINT(1) NOT NULL DEFAULT 0;
ALTER TABLE quotations ADD COLUMN pre_confirmed_at DATETIME NULL;
ALTER TABLE quotations ADD COLUMN pre_confirmed_by BIGINT UNSIGNED NULL;
ALTER TABLE quotations ADD COLUMN confirmation_date DATETIME NULL;
ALTER TABLE quotations ADD COLUMN cancellation_date DATETIME NULL;

-- Link existing quotations to their travel requests
UPDATE quotations q INNER JOIN requests r ON r.converted_to_quote_id = q.id
SET q.request_id = r.id WHERE q.request_id IS NULL;
