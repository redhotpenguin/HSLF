-- share payload feature

CREATE TABLE share_payload(
	id SERIAL PRIMARY KEY,
        tenant_id INTEGER NOT NULL,
        url  VARCHAR(2048) NOT NULL,
        title VARCHAR(512) NOT NULL,
        description TEXT NOT NULL,
        tweet VARCHAR(140) NOT NULL,
        email VARCHAR(320) NOT NULL
        
);

ALTER TABLE share_payload ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
