CREATE table push_message(
    id SERIAL PRIMARY KEY,
    tenant_id INTEGER REFERENCES tenant(id) NOT NULL,
    share_payload_id INTEGER REFERENCES share_payload(id),
    creation_date timestamp without time zone NOT NULL,
    alert character varying(140)

);

CREATE TABLE tag_push_message(
    tag_id INTEGER REFERENCES tag(id),
    push_message_id INTEGER REFERENCES push_message(id),
    PRIMARY KEY (tag_id, push_message)
);