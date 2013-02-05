ALTER TABLE push_message ALTER share_payload_id SET NOT NULL;



ALTER TABLE share_payload RENAME to payload;

ALTER TABLE payload ADD COLUMN "type" varchar(16) NOT NULL;
ALTER TABLE payload ADD COLUMN post_number integer;
ALTER TABLE payload ALTER COLUMN url DROP  NOT NULL;
ALTER TABLE payload ALTER COLUMN description DROP  NOT NULL;
ALTER TABLE payload ALTER COLUMN tweet DROP  NOT NULL;
ALTER TABLE payload ALTER COLUMN email DROP  NOT NULL;

    


UPDATE  "AuthItem" SET name = 'createPayload' WHERE name = 'createSharePayload';

UPDATE  "AuthItem" SET name = 'readPayload' WHERE name = 'readSharePayload';

UPDATE  "AuthItem" SET name = 'updatePayload' WHERE name = 'updateSharePayload';

UPDATE  "AuthItem" SET name = 'deletePayload' WHERE name = 'deleteSharePayload';

UPDATE  "AuthItem" SET name = 'managePayloads' WHERE name = 'manageSharePayloads';


ALTER TABLE push_message RENAME COLUMN share_payload_id TO payload_id;

ALTER TABLE push_message ADD FOREIGN KEY (payload_id) REFERENCES payload (id) ON UPDATE CASCADE ON DELETE CASCADE;