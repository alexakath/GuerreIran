-- Migration: add indexes to speed up article queries
-- Run with: mysql -u user -puserpass guerre_iran < 2026-03-30-add-indexes.sql

ALTER TABLE articles ADD INDEX idx_articles_created_at (created_at);

-- Add fulltext index for better search (MySQL 5.6+ InnoDB/fulltext supported)
ALTER TABLE articles ADD FULLTEXT INDEX idx_articles_fulltext (title, content);

-- Only add indexes if they don't already exist in your DB. Review before running on production.
