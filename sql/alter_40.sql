ALTER TABLE tag DROP CONSTRAINT tag_name_type_key;

ALTER TABLE tag ADD CONSTRAINT tag_name_type_tenant_id_key UNIQUE (name,type,tenant_id);


ALTER TABLE item DROP CONSTRAINT unique_url;

ALTER TABLE item ADD CONSTRAINT slug_tenant_id_key UNIQUE (slug, tenant_id);

