<?php

class m130715_004642_contact_organization_position extends CDbMigration
{

	
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
           $this->addColumn('contact_organization', 'position', 'integer');
	}

	public function safeDown()
	{
	}
	
}