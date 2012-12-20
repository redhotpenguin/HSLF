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


-- endorser updates
ALTER TABLE endorser RENAME TO organization;
ALTER TABLE endorser_item RENAME TO organization_item;
ALTER TABLE organization_item RENAME COLUMN endorser_id TO organization_id;
