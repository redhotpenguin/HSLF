ALTER TABLE tenant_user DROP CONSTRAINT tenant_user_tenant_id_fkey;

ALTER TABLE tenant_user ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tenant_user DROP CONSTRAINT tenant_user_user_id_fkey;

ALTER TABLE tenant_user ADD FOREIGN KEY (user_id) REFERENCES "user" (id) ON UPDATE CASCADE ON DELETE CASCADE;
