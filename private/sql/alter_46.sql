ALTER TABLE alert_type DROP CONSTRAINT alert_type_tag_id_fkey; 

ALTER TABLE alert_type ADD FOREIGN KEY (tag_id) REFERENCES tag (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE item DROP CONSTRAINT ballot_item_district_id_fkey;

ALTER TABLE item ADD FOREIGN KEY (district_id) REFERENCES district (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE item DROP CONSTRAINT ballot_item_party_id_fkey;

ALTER TABLE item ADD FOREIGN KEY (party_id) REFERENCES party (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE item DROP CONSTRAINT ballot_item_recommendation_id_fkey;

ALTER TABLE item ADD FOREIGN KEY (recommendation_id) REFERENCES recommendation (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE district DROP CONSTRAINT district_state_id_fkey;

ALTER TABLE district ADD FOREIGN KEY (state_id) REFERENCES "state" (id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE scorecard_item DROP CONSTRAINT scorecard_item_office_id_fkey;

ALTER TABLE scorecard_item ADD FOREIGN KEY (office_id) REFERENCES office(id) ON UPDATE CASCADE ON DELETE RESTRICT;

ALTER TABLE contact DROP CONSTRAINT contact_tenant_id_fkey;

ALTER TABLE contact ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE; 

UPDATE contact SET first_name = 'N/A' WHERE first_name = 'NA';