-- last alter file merged: alter_24.sql

-- tables creation --

create table tenant(
	id SERIAL PRIMARY KEY,
	name varchar(32) NOT NULl,
	display_name varchar(256) NOT NULL,
	creation_date timestamp without time zone NOT NULL,
	site_url TEXT NOT NULL,
	web_app_url character varying(2048) NOT NULL,
	email TEXT NOT NULL,
	api_key TEXT NOT NULL,
	api_secret TEXT NOT NULL,
	ua_dashboard_link TEXT NOT NULL,
	cicero_user TEXT NOT NULL,
	cicero_password TEXT NOT NULL
);

CREATE TABLE state (
    id SERIAL PRIMARY KEY,
    abbr character varying(3) NOT NULL,
    name character varying(128)
);

CREATE TABLE district (
    id SERIAL PRIMARY KEY,
    state_id INTEGER NOT NULL,
    number character varying(512),
    type character varying(128) NOT NULL,
    display_name character varying(512),
    locality character varying(128)
);

CREATE TABLE alert_type (
    id SERIAL PRIMARY KEY,
    display_name character varying(1024),
    tag_id integer,
	category VARCHAR(512)
);

CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
	tenant_id integer NOT NULL,
    name character varying(255),
    type character varying(255)
);

CREATE TABLE organization_item (
    id SERIAL PRIMARY KEY,
    organization_id integer NOT NULL,
    item_id integer NOT NULL,
    "position" character varying(32)
);

CREATE TABLE organization (
    id SERIAL PRIMARY KEY,
    tenant_id integer NOT NULL,
    name character varying(512) NOT NULL,
    description text,
    website character varying(2048),
    image_url character varying(2048),
    display_name text,
    slug VARCHAR(512) NOT NULL,
    facebook_url character varying(2048),
    twitter_handle character varying(2048)
);

CREATE TABLE item_news (
    id SERIAL PRIMARY KEY,
    item_id integer NOT NULL,
    title text NOT NULL,
    content text,
    excerpt text,
    date_published timestamp without time zone NOT NULL,
    slug VARCHAR(512) NOT NULL
);

CREATE TABLE item (
    id SERIAL PRIMARY KEY,
    district_id integer NOT NULL,
    tenant_id integer NOT NULL,
    item text NOT NULL,
    item_type character varying(128) NOT NULL,
    recommendation_id integer NOT NULL,
    next_election_date timestamp without time zone,
    detail text,
    date_published timestamp without time zone NOT NULL,
    published character varying(16) NOT NULL,
    image_url character varying(2048),
    slug character varying(500) NOT NULL,
    website character varying(2048),
    party_id integer NOT NULL,
    facebook_url character varying(2048),
    twitter_handle character varying(128),
    measure_number character varying(24),
    friendly_name character varying(1024),
    first_name character varying(1024),
    last_name character varying(1024)
);

CREATE TABLE recommendation (
    id SERIAL PRIMARY KEY,
    value character varying(64) NOT NULL,
    type character varying(64) NOT NULL
);

CREATE TABLE party (
    id SERIAL PRIMARY KEY,
    name character varying(2048),
    abbr character varying(128),
    initial character varying(16)
);

CREATE TABLE scorecard (
    id SERIAL PRIMARY KEY,
    scorecard_item_id integer NOT NULL,
    vote_id integer NOT NULL
);

CREATE TABLE scorecard_item (
    id SERIAL PRIMARY KEY,
    name character varying(4096) NOT NULL,
    office_id integer NOT NULL,
    description text
);

CREATE TABLE office (
    id SERIAL PRIMARY KEY,
    name character varying(256)
);

CREATE TABLE vote (
    id SERIAL PRIMARY KEY,
	tenant_id integer NOT NULL,
    name character varying(64),
    icon text
);

CREATE TABLE option (
    id SERIAL PRIMARY KEY,
	tenant_id integer NOT NULL,
    name character varying(256) NOT NULL,
    value text NOT NULL
);

CREATE TABLE "user" (
    id SERIAL PRIMARY KEY,
	tenant_id integer NOT NULL,
    username character varying(128) NOT NULL,
    password character(40) NOT NULL,
    email character varying(128) NOT NULL,
    role character varying(128) NOT NULL
);

-- unique constraints creation --
ALTER TABLE tenant ADD CONSTRAINT unique_name UNIQUE (name);

ALTER TABLE item ADD CONSTRAINT unique_url UNIQUE(slug);


ALTER TABLE district ADD CONSTRAINT district_state_id_number_locality_type_key UNIQUE (state_id, number, type, locality);
ALTER TABLE option ADD CONSTRAINT option_name_key UNIQUE (name);
--ALTER TABLE scorecard ADD CONSTRAINT scorecard_item_id_scorecard_item_id_key UNIQUE (item_id, scorecard_item_id);

ALTER TABLE state ADD CONSTRAINT state_id_key UNIQUE (id);
ALTER TABLE state ADD CONSTRAINT state_abbr_key UNIQUE (abbr);

ALTER TABLE tag ADD CONSTRAINT tag_name_type_key UNIQUE (name, type);
ALTER TABLE "user" ADD CONSTRAINT user_email_key UNIQUE (email);
ALTER TABLE "user" ADD CONSTRAINT user_username_key UNIQUE (username);
	
-- relation constraints creation --
ALTER TABLE alert_type ADD CONSTRAINT alert_type_tag_id_fkey FOREIGN KEY (tag_id) REFERENCES tag(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE item ADD CONSTRAINT item_district_id_fkey FOREIGN KEY (district_id) REFERENCES district(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE item ADD CONSTRAINT item_party_id_fkey FOREIGN KEY (party_id) REFERENCES party(id) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE item ADD CONSTRAINT item_recommendation_id_fkey FOREIGN KEY (recommendation_id) REFERENCES recommendation(id) ON UPDATE CASCADE ON DELETE SET NULL;

ALTER TABLE item_news ADD CONSTRAINT item_news_item_id_fkey FOREIGN KEY (item_id) REFERENCES item(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE district ADD CONSTRAINT district_state_id_fkey FOREIGN KEY (state_id) REFERENCES state(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE organization_item ADD CONSTRAINT organization_item_item_id_fkey FOREIGN KEY (item_id) REFERENCES item(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE organization_item ADD CONSTRAINT organization_item_organization_id_fkey FOREIGN KEY (organization_id) REFERENCES organization(id) ON UPDATE CASCADE ON DELETE CASCADE;

--ALTER TABLE scorecard ADD CONSTRAINT scorecard_item_id_fkey FOREIGN KEY (item_id) REFERENCES item(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE scorecard ADD CONSTRAINT scorecard_scorecard_item_id_fkey FOREIGN KEY (scorecard_item_id) REFERENCES scorecard_item(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE scorecard ADD CONSTRAINT scorecard_vote_id_fkey FOREIGN KEY (vote_id) REFERENCES vote(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE scorecard_item ADD CONSTRAINT scorecard_item_office_id_fkey FOREIGN KEY (office_id) REFERENCES office(id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE item	ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE organization ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE "option" ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag	 ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE vote  ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE "user"  ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;