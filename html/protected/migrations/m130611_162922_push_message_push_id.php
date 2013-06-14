<?php

/*
 * Add a new field "push_identifier" to the push_message table to track urban airship push status
 * Created with: php html/protected/yiic.php migrate create push_message_push_id html/protected/commands/config/main.php
 * Apply with: php html/protected/yiic.php  migrate
 */

class m130611_162922_push_message_push_id extends CDbMigration {

    public function safeUp() {
        $this->addColumn('push_message', 'push_identifier', 'text');
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