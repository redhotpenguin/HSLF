CREATE TABLE tenant_user(
    tenant_id INTEGER REFERENCES tenant(id),
    user_id INTEGER REFERENCES "user"(id),
    PRIMARY KEY (tenant_id, user_id)
);

INSERT INTO tenant_user (tenant_id, user_id) SELECT tenant_id, id FROM "user";

ALTER TABLE "user" DROP COLUMN tenant_id;