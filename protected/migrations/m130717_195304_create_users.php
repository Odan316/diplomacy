<?php

class m130717_195304_create_users extends CDbMigration
{
	public function up()
	{
        $this->createTable(
            'users',
            array(
                'id' => 'pk',
                'login' => 'string',
                'password' => 'string',
                'salt' => 'string'
            )
        );
	}

	public function down()
	{
		echo "m130717_195304_create_users does not support migration down.\n";
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