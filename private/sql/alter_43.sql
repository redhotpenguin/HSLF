-- contact table and orgs<=>contact relationship


CREATE table contact(
    id SERIAL PRIMARY KEY,
    tenant_id INTEGER REFERENCES tenant(id) NOT NULL,
    first_name TEXT NOT NULL,
    last_name TEXT,
    email VARCHAR(128),
    title VARCHAR(512),
    phone_number VARCHAR(512)
);

INSERT INTO contact (tenant_id, first_name) VALUES (1,'NA'); -- ouroregon
INSERT INTO contact (tenant_id, first_name) VALUES (2,'NA'); -- afscme


CREATE TABLE contact_organization(
    contact_id INTEGER REFERENCES contact(id),
    organization_id INTEGER REFERENCES organization(id),
    PRIMARY KEY (contact_id, organization_id)
);


ALTER TABLE organization ADD COLUMN primary_contact_id INTEGER;

UPDATE organization SET primary_contact_id = 1 WHERE tenant_id = 1;

UPDATE organization SET primary_contact_id = 2 WHERE tenant_id = 2;

--ALTER TABLE organization ALTER COLUMN primary_contact_id SET NOT NULL;

ALTER TABLE organization ADD FOREIGN KEY (primary_contact_id) REFERENCES contact (id) ON UPDATE CASCADE ON DELETE SET NULL;
