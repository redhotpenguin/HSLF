-- update FK constraints and add cascade constraints

ALTER TABLE push_message DROP CONSTRAINT push_message_tenant_id_fkey;

ALTER TABLE payload ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE push_message DROP CONSTRAINT push_message_share_payload_id_fkey;

ALTER TABLE push_message ADD FOREIGN KEY (payload_id) REFERENCES payload (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag_organization DROP CONSTRAINT tag_organization_organization_id_fkey;

ALTER TABLE tag_organization ADD FOREIGN KEY (organization_id) REFERENCES organization (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag_organization DROP CONSTRAINT tag_organization_tag_id_fkey;

ALTER TABLE tag_organization ADD FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag_push_message DROP CONSTRAINT tag_push_message_push_message_id_fkey;

ALTER TABLE tag_push_message ADD FOREIGN KEY (push_message_id) REFERENCES push_message (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag_push_message DROP CONSTRAINT tag_push_message_tag_id_fkey;

ALTER TABLE tag_push_message ADD FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE CASCADE ON DELETE CASCADE;