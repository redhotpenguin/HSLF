ALTER TABLE tag ADD COLUMN display_name TEXT;

UPDATE tag SET display_name = name;

ALTER TABLE tag ALTER COLUMN display_name SET NOT NULL;

CREATE TABLE tag_organization(
    tag_id INTEGER REFERENCES tag(id),
    organization_id INTEGER REFERENCES organization(id),
    PRIMARY KEY (tag_id, organization_id)
);

UPDATE tag SET type = 'alert' where type = 'alerts';