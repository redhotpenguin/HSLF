<?php

class m130612_184552_push_message_recipient_type extends CDbMigration {

    public function safeUp() {
        $this->addColumn('push_message', 'recipient_type', 'varchar(16)');
        $this->update('push_message', array('recipient_type' => "broadcast"));
        $this->execute("ALTER TABLE push_message ALTER COLUMN recipient_type SET NOT NULL;");

        $this->refreshTableSchema('push_message');
    }

    public function saveDown() {
        echo "m130611_162922_push_message_push_id does not support migration down.\n";
        return false;
    }

    /*
      // Use safeUp/safeDown to do migration with transaction
      public function safeUp()
      {
      }

      public function safeDown()
      {
      }
     */
}