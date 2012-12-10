---- new tenant table
create table tenant(
	id SERIAL PRIMARY KEY,
	name varchar(32) NOT NULl,
	display_name varchar(256) NOT NULL,
	creation_date timestamp without time zone NOT NULL,
	site_url TEXT NOT NULL,
	web_app_url TEXT NOT NULL,
	email TEXT NOT NULL,
	api_key TEXT NOT NULL,
	api_secret TEXT NOT NULL,
	ua_dashboard_link TEXT NOT NULL,
	cicero_user TEXT NOT NULL,
	cicero_password TEXT NOT NULL
);
ALTER TABLE tenant ADD CONSTRAINT unique_name UNIQUE (name);

INSERT INTO tenant (name, display_name, creation_date, site_url, web_app_url, email, api_key, api_secret, ua_dashboard_link, cicero_user, cicero_password) VALUES (
	'ouroregon', 
	'Our Oregon',
	NOW(),
	'http://www.winningmarkmobile.com',
	'http://vote.ouroregon.org',
	'mobile@winningmark.com',
	'52356',
	'PqiW_IDKL3mFi_OirCqOe-u',
	'http://urbanairship.com/',
	'winningmark',
	'3TUuAv5DwNsB'
);

ALTER TABLE ballot_item	ADD COLUMN tenant_id INTEGER;
ALTER TABLE endorser		ADD COLUMN tenant_id INTEGER;
ALTER TABLE "option" ADD COLUMN tenant_id INTEGER;
ALTER TABLE tag	ADD COLUMN tenant_id INTEGER;
ALTER TABLE vote  ADD COLUMN tenant_id INTEGER;
ALTER TABLE "user"  ADD COLUMN tenant_id INTEGER;

ALTER TABLE ballot_item	ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE endorser ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "option" ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE tag	 ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE vote  ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE "user"  ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

-- data update
UPDATE ballot_item	SET tenant_id = 1;
UPDATE endorser SET tenant_id = 1;
UPDATE "option" SET tenant_id = 1;
UPDATE tag	 SET tenant_id = 1;
UPDATE vote  SET tenant_id = 1;
UPDATE "user"   SET tenant_id = 1;

ALTER TABLE ballot_item	ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE endorser ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE "option" ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE tag	 ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE vote  ALTER COLUMN tenant_id SET NOT NULL;
ALTER TABLE "user"  ALTER COLUMN tenant_id SET NOT NULL;


-- remove unused tables:
DROP TABLE app_user_meta;
DROP TABLE app_user_tag;
DROP TABLE app_user;



-- update state table: make state.id the primary key instead of state.abbr
ALTER TABLE district DROP CONSTRAINT district_state_abbr_fkey ;

ALTER TABLE state DROP CONSTRAINT state_pkey;

ALTER TABLE state ADD PRIMARY KEY (id); 

ALTER TABLE district DROP COLUMN state_abbr;

ALTER TABLE district ADD COLUMN state_id INTEGER;

ALTER TABLE district ADD FOREIGN KEY (state_id) REFERENCES state (id) ON UPDATE CASCADE ON DELETE CASCADE;

UPDATE district   SET state_id = 1;

ALTER TABLE district ALTER COLUMN state_id SET NOT NULL;


--ALTER TABLE ballot_item_news DROP CONSTRAINT IF EXISTS ballot_item_news_ballot_item_id_fkey1;


-- option table: update unique constraint
ALTER TABLE option DROP CONSTRAINT option_name_key;

ALTER TABLE option ADD UNIQUE  (name, tenant_id);