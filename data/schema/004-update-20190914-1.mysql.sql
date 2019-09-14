-- Add created_by column to pages table
ALTER TABLE pages ADD COLUMN created_by INT AFTER page_type;

UPDATE pages SET created_by = (
    SELECT user_id FROM page_revisions WHERE page_revisions.page_id = pages.page_id ORDER BY saved_at LIMIT 1
);

ALTER TABLE pages MODIFY COLUMN created_by INT NOT NULL;
