<?php

class m130825_003709_tenant_app_version extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->addColumn('tenant_setting', 'app_version', 'text');
        $this->update('tenant_setting', array('app_version' => '3.0'), null);
    }

    public function safeDown() {
        
    }

}