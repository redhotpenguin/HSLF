ALTER TABLE push_message ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE push_message DROP CONSTRAINT push_message_payload_id_fkey1;

ALTER TABLE payload DROP CONSTRAINT share_payload_tenant_id_fkey CASCADE;

ALTER TABLE payload DROP CONSTRAINT share_payload_pkey CASCADE;

ALTER TABLE payload ADD PRIMARY KEY (id);


