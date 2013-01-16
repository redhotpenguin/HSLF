-- share payload feature

CREATE TABLE share_payload(
	id SERIAL PRIMARY KEY,
        tenant_id INTEGER NOT NULL
);

ALTER TABLE share_payload ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
