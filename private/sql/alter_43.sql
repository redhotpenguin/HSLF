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