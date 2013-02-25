-- last alter file merged: alter_42.sql

-- tables creation --

create table tenant(
	id SERIAL PRIMARY KEY,
	name varchar(32) NOT NULl,
	display_name varchar(256) NOT NULL,
	creation_date timestamp without time zone NOT NULL,
	web_app_url character varying(2048) NOT NULL,
	email TEXT NOT NULL,
	api_key TEXT NOT NULL,
	api_secret TEXT NOT NULL,
	ua_dashboard_link TEXT NOT NULL,
        ua_api_key TEXT NOT NULL,
        ua_api_secret TEXT NOT NULL,
	cicero_user TEXT NOT NULL,
	cicero_password TEXT NOT NULL
);

CREATE TABLE state (
    id SERIAL PRIMARY KEY,
    abbr character varying(3) NOT NULL,
    name character varying(128) NOT NULL
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
    display_name character varying(1024) NOT NULL,
    tag_id integer NOT NULL,
    category VARCHAR(512)
);

CREATE TABLE tag (
    id SERIAL PRIMARY KEY,
    tenant_id integer NOT NULL,
    name character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    display_name TEXT NOT NULL
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
    twitter_handle character varying(2048),
    address TEXT NOT NULL
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
    name character varying(2048) NOT NULL,
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
    tenant_id integer NOT NULL,
    name character varying(4096) NOT NULL,
    office_id integer NOT NULL,
    description text
);

CREATE TABLE office (
    id SERIAL PRIMARY KEY,
    name character varying(256) NOT NULL
);

CREATE TABLE vote (
    id SERIAL PRIMARY KEY,
    tenant_id integer NOT NULL,
    name character varying(64) NOT NULL,
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
    username character varying(128) NOT NULL,
    password character(40) NOT NULL,
    email character varying(128) NOT NULL
);

CREATE TABLE payload(
	id SERIAL PRIMARY KEY,
        tenant_id INTEGER NOT NULL,
        "type" VARCHAR(16) NOT NULL,
        post_number INTEGER,
        url  VARCHAR(2048),
        title VARCHAR(512) NOT NULL,
        description TEXT,
        tweet VARCHAR(140),
        email VARCHAR(320)
);

create table "AuthItem"
(
   "name"                 varchar(64) not null,
   "type"                 integer not null,
   "description"          text,
   "bizrule"              text,
   "data"                 text,
   primary key ("name")
);

create table "AuthItemChild"
(
   "parent"               varchar(64) not null,
   "child"                varchar(64) not null,
   primary key ("parent","child"),
   foreign key ("parent") references "AuthItem" ("name") on delete cascade on update cascade,
   foreign key ("child") references "AuthItem" ("name") on delete cascade on update cascade
);

create table "AuthAssignment"
(
   "itemname"             varchar(64) not null,
   "userid"               varchar(64) not null,
   "bizrule"              text,
   "data"                 text,
   primary key ("itemname","userid"),
   foreign key ("itemname") references "AuthItem" ("name") on delete cascade on update cascade
);


CREATE TABLE tenant_user(
    tenant_id INTEGER REFERENCES tenant(id) on delete cascade on update cascade,
    user_id INTEGER REFERENCES "user"(id) on delete cascade on update cascade,
    PRIMARY KEY (tenant_id, user_id)
);

CREATE TABLE user_session
(
    id CHAR(32) PRIMARY KEY,
    expire INTEGER,
    data TEXT
);

CREATE TABLE tag_organization(
    tag_id INTEGER REFERENCES tag(id) on delete cascade on update cascade,
    organization_id INTEGER REFERENCES organization(id) on delete cascade on update cascade,
    PRIMARY KEY (tag_id, organization_id)
);

CREATE table push_message(
    id SERIAL PRIMARY KEY,
    tenant_id INTEGER REFERENCES tenant(id)  on delete cascade on update cascade,
    payload_id INTEGER REFERENCES payload(id) on delete cascade on update cascade,
    creation_date timestamp without time zone NOT NULL,
    alert character varying(140)

);

CREATE TABLE tag_push_message(
    tag_id INTEGER REFERENCES tag(id) on delete cascade on update cascade ,
    push_message_id INTEGER REFERENCES push_message(id) on delete cascade on update cascade,
    PRIMARY KEY (tag_id, push_message_id)
);


-- unique constraints creation --
ALTER TABLE tenant ADD CONSTRAINT unique_name UNIQUE (name);

ALTER TABLE item ADD CONSTRAINT unique_tenant_slug UNIQUE(tenant_id,slug);

ALTER TABLE district ADD CONSTRAINT district_state_id_number_locality_type_key UNIQUE (state_id, number, type, locality);
ALTER TABLE option ADD CONSTRAINT option_name_tenant_id_key UNIQUE (name, tenant_id);

ALTER TABLE state ADD CONSTRAINT state_abbr_key UNIQUE (abbr);

ALTER TABLE tag ADD CONSTRAINT tag_name_type_tenant_id_key UNIQUE (name, type, tenant_id);
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

ALTER TABLE scorecard_item	ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE item	ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE organization ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE "option" ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE tag	 ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE vote  ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE payload ADD FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON UPDATE CASCADE ON DELETE CASCADE;
