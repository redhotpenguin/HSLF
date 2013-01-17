ALTER TABLE organization ADD COLUMN address TEXT;

UPDATE organization SET address = 'N/A';\

ALTER TABLE organization ALTER COLUMN address SET NOT NULL;
