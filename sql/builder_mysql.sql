-- last alter file merged: alter_39.sql
-- mysql format


create table tenant(
	id int(11) PRIMARY KEY AUTO_INCREMENT,
	name varchar(32) NOT NULl,
	display_name varchar(256) NOT NULL,
	creation_date DATETIME NOT NULL,
	web_app_url VARCHAR(2048) NOT NULL,
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
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    abbr VARCHAR(3) NOT NULL,
    name VARCHAR(128)
);


CREATE TABLE district (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    state_id INTEGER NOT NULL,
    number VARCHAR(512),
    type VARCHAR(128) NOT NULL,
    display_name VARCHAR(512),
    locality VARCHAR(128),
    foreign key (state_id) references state (id) on delete cascade on update cascade
);


CREATE TABLE tag (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id integer NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(255) NOT NULL,
    display_name TEXT NOT NULL,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE alert_type (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    display_name VARCHAR(1024),
    tag_id integer NOT NULL,
    category VARCHAR(512),
    foreign key (tag_id) references tag (id) on delete cascade on update cascade
);


CREATE TABLE organization (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id integer NOT NULL,
    name VARCHAR(512) NOT NULL,
    description text,
    website VARCHAR(2048),
    image_url VARCHAR(2048),
    display_name text,
    slug VARCHAR(512) NOT NULL,
    facebook_url VARCHAR(2048),
    twitter_handle VARCHAR(2048),
    address TEXT NOT NULL,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE item (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    district_id integer NOT NULL,
    tenant_id integer NOT NULL,
    item text NOT NULL,
    item_type VARCHAR(128) NOT NULL,
    recommendation_id integer NOT NULL,
    next_election_date DATETIME,
    detail text,
    date_published DATETIME NOT NULL,
    published VARCHAR(16) NOT NULL,
    image_url VARCHAR(2048),
    slug VARCHAR(500) NOT NULL,
    website VARCHAR(2048),
    party_id integer NOT NULL,
    facebook_url VARCHAR(2048),
    twitter_handle VARCHAR(128),
    measure_number VARCHAR(24),
    friendly_name VARCHAR(1024),
    first_name VARCHAR(1024),
    last_name VARCHAR(1024),
    foreign key (district_id) references district (id) on delete cascade on update cascade,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE organization_item (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    organization_id integer NOT NULL,
    item_id integer NOT NULL,
    position VARCHAR(32),
    foreign key (organization_id) references organization (id) on delete cascade on update cascade,
    foreign key (item_id) references item (id) on delete cascade on update cascade
);


CREATE TABLE item_news (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    item_id integer NOT NULL,
    title text NOT NULL,
    content text,
    excerpt text,
    date_published DATETIME NOT NULL,
    slug VARCHAR(512) NOT NULL,
    foreign key (item_id) references item (id) on delete cascade on update cascade
);


CREATE TABLE recommendation (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    value VARCHAR(64) NOT NULL,
    type VARCHAR(64) NOT NULL
);


CREATE TABLE party (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(2048),
    abbr VARCHAR(128),
    initial VARCHAR(16)
);


CREATE TABLE vote (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id integer NOT NULL,
    name VARCHAR(64),
    icon text,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE office (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(256)
);


CREATE TABLE scorecard_item (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id integer NOT NULL,
    name VARCHAR(4096) NOT NULL,
    office_id integer NOT NULL,
    description text,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE scorecard (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    scorecard_item_id integer NOT NULL,
    vote_id integer NOT NULL,
    foreign key (scorecard_item_id) references scorecard_item (id) on delete cascade on update cascade,
    foreign key (vote_id) references vote (id) on delete cascade on update cascade
);


CREATE TABLE `option` (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id integer NOT NULL,
    name VARCHAR(256) NOT NULL,
    value text NOT NULL,
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


CREATE TABLE `user` (
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(128) NOT NULL,
    password character(40) NOT NULL,
    email VARCHAR(128) NOT NULL
);


CREATE TABLE payload(
	id int(11) PRIMARY KEY AUTO_INCREMENT,
        tenant_id INTEGER NOT NULL,
        type VARCHAR(16) NOT NULL,
        post_number INTEGER,
        url  VARCHAR(2048),
        title VARCHAR(512) NOT NULL,
        description TEXT,
        tweet VARCHAR(140),
        email VARCHAR(320),
        foreign key (tenant_id) references tenant (id) on delete cascade on update cascade
);


create table `AuthItem`
(
   `name`                 varchar(64) not null,
   `type`                 integer not null,
   `description`          text,
   `bizrule`              text,
   `data`                 text,
   primary key (`name`)
);


create table `AuthItemChild`
(
   `parent`               varchar(64) not null,
   `child`                varchar(64) not null,
   primary key (`parent`,`child`),
   foreign key (`parent`) references `AuthItem` (`name`) on delete cascade on update cascade,
   foreign key (`child`) references `AuthItem` (`name`) on delete cascade on update cascade
);


create table `AuthAssignment`
(
   `itemname`             varchar(64) not null,
   `userid`               varchar(64) not null,
   `bizrule`              text,
   `data`                 text,
   primary key (`itemname`,`userid`),
   foreign key (`itemname`) references `AuthItem` (`name`) on delete cascade on update cascade
);



CREATE TABLE tenant_user(
    tenant_id INTEGER REFERENCES tenant(id),
    user_id INTEGER REFERENCES `user`(id),
    PRIMARY KEY  (tenant_id, user_id),
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade,
    foreign key (user_id) references user (id) on delete cascade on update cascade
);


CREATE TABLE user_session
(
    id CHAR(32) PRIMARY KEY,
    expire INTEGER,
    data BLOB
);


CREATE TABLE tag_organization(
    tag_id INTEGER REFERENCES tag(id),
    organization_id INTEGER REFERENCES organization(id),
    PRIMARY KEY  (tag_id, organization_id),
    foreign key (tag_id) references tag (id) on delete cascade on update cascade,
    foreign key (organization_id) references organization (id) on delete cascade on update cascade
);


CREATE table push_message(
    id int(11) PRIMARY KEY AUTO_INCREMENT,
    tenant_id  INTEGER NOT NULL,
    payload_id INTEGER NOT NULL,
    creation_date DATETIME NOT NULL,
    alert VARCHAR(140),
    foreign key (tenant_id) references tenant (id) on delete cascade on update cascade,
    foreign key (payload_id) references payload (id) on delete cascade on update cascade
);



CREATE TABLE tag_push_message(
    tag_id INTEGER NOT NULL,
    push_message_id INTEGER NOT NULL,
    PRIMARY KEY (tag_id, push_message_id),
    foreign key (tag_id) references tag (id) on delete cascade on update cascade,
    foreign key (push_message_id) references push_message (id) on delete cascade on update cascade
);