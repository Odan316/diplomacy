<?php

class m131012_152113_module_activity extends CDbMigration
{
	public function up()
	{
        $this->addColumn('modules', 'active', "ENUM('yes', 'no') DEFAULT 'no'");
	}

	public function down()
	{
		echo "m131012_152113_module_activity does not support migration down.\n";
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