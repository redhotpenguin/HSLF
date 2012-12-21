-- item updates
ALTER TABLE item DROP election_result_id;
ALTER TABLE item DROP hold_office;
ALTER TABLE item DROP keywords;
ALTER TABLE item DROP office_id;
ALTER TABLE item DROP score;
ALTER TABLE item DROP twitter_share;
ALTER TABLE item DROP facebook_share;
ALTER TABLE scorecard DROP item_id;
ALTER TABLE item ADD COLUMN first_name VARCHAR(1024);
ALTER TABLE item ADD COLUMN last_name VARCHAR(1024);
ALTER TABLE item RENAME COLUMN url TO slug;
ALTER TABLE item RENAME COLUMN personal_url TO website;

-- endorser updates
ALTER TABLE endorser RENAME TO organization;
ALTER TABLE endorser_item RENAME TO organization_item;
ALTER TABLE organization_item RENAME COLUMN endorser_id TO organization_id;
ALTER TABLE organization RENAME COLUMN twitter_share TO twitter_handle;
ALTER TABLE organization RENAME COLUMN facebook_share TO facebook_url;
ALTER TABLE organization DROP COLUMN list_name;


ALTER TABLE scorecard_item ADD COLUMN tenant_id INTEGER;
ALTER TABLE scorecard_item ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
UPDATE scorecard_item  SET tenant_id = 1;
ALTER TABLE scorecard_item   ALTER COLUMN tenant_id SET NOT NULL;
