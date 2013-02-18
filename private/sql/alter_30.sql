-- rbac

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


-- add roles
INSERT INTO "AuthItem" (name, type) VALUES('admin', 2);
INSERT INTO "AuthItem" (name, type) VALUES('publisher', 2);

-- copy current user.role to AuthAssignment

INSERT INTO "AuthAssignment" (itemname, userid) SELECT "role", id FROM "user";


ALTER TABLE "user" DROP COLUMN "role";