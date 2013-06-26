<?php

/*
 * Created with:   php html/protected/yiic.php  migrate create organization_alter  html/protected/commands/config/main.php
 * Applied with:  php html/protected/yiic.php  migrate
 */

class m130626_180858_organization_alter extends CDbMigration {

    public function safeUp() {
        $this->dropColumn('organization', 'slug');
        $this->dropColumn('organization', 'primary_contact_id');
    }

    public function safeDown() {
        echo "m130626_180858_organization_alter does not support migration down.\n";
        return false;
    }

}