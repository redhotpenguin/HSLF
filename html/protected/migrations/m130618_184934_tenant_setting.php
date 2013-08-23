<?php

/**
 * Tenant table tweaks
 * Created with: php html/protected/yiic.php  migrate create tenant_setting  html/protected/commands/config/main.php
 * Applied with: php html/protected/yiic.php  migrate
 */
class m130618_184934_tenant_setting extends CDbMigration {

    public function safeUp() {

        $columns = array(
            'id' => 'pk',
            'analytics_link' => 'varchar (2048)',
            'ios_link' => 'varchar (2048)',
            'android_link' => 'varchar (2048)',
        );

        $this->createTable('tenant_setting', $columns);

        $this->addColumn('tenant', 'tenant_setting_id', 'integer');

        $this->dropColumn('tenant', 'ua_dashboard_link');


        $this->insert('tenant_setting', array(
            'analytics_link' => 'http://www.google.com/analytics/',
            'ios_link' => 'https://itunesconnect.apple.com/',
            'android_link' => 'https://play.google.com/apps/publish/'
        ));

        $this->insert('tenant_setting', array(
            'analytics_link' => 'http://www.google.com/analytics/',
            'ios_link' => 'https://itunesconnect.apple.com/',
            'android_link' => 'https://play.google.com/apps/publish/'
        ));

        $this->addForeignKey('tenant_tenant_setting_id_fkey', 'tenant', 'tenant_setting_id', 'tenant_setting', 'id');

        $this->update('tenant', array('tenant_setting_id' => 1), 'id=1');
        $this->update('tenant', array('tenant_setting_id' => 2), 'id=2');

        $this->execute("ALTER TABLE tenant ALTER COLUMN tenant_setting_id SET NOT NULL;");
    }

    public function safeDown() {
        echo "m130618_184934_tenant_setting does not support migration down.\n";
        return false;
    }

}