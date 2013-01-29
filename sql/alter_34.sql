
CREATE TABLE tenant_user(
    tenant_id INTEGER REFERENCES tenant(id),
    user_id INTEGER REFERENCES "user"(id),
    PRIMARY KEY (tenant_id, user_id)
);



ALTER TABLE "user" DROP COLUMN tenant_id;