ALTER TABLE tag ADD COLUMN display_name TEXT;

UPDATE tag SET display_name = name;

ALTER TABLE tag ALTER COLUMN display_name SET NOT NULL;