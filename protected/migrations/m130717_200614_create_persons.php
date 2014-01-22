<?php

class m130717_200614_create_persons extends CDbMigration
{
	public function up()
	{
        $this->createTable(
            'persons',
            array(
                'id' => 'pk',
                'user_id' => 'integer',
                'nickname' => 'string',
                'name' => 'string',
                'surname' => 'string',
                'patronymic' => 'string'
            )
        );
	}

	public function down()
	{
		echo "m130717_200614_create_persons does not support migration down.\n";
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