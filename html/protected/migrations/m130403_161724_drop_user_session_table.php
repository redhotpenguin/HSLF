<?php


/**
 * migration class
 * Created with php html/protected/yiic.php  migrate create drop_user_session_table html/protected/commands/config/main.php
 * Applied with: php html/protected/yiic.php  migrate

 */

class m130403_161724_drop_user_session_table extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->dropTable('user_session');
    }

    public function safeDown() {
        echo "m130403_161724_drop_user_session_table does not support migration down.\n";
        return false;
    }

}