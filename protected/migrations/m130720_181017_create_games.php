<?php

class m130720_181017_create_games extends CDbMigration
{
	public function up()
	{
        $this->createTable(
            'games',
            array(
                'id' => 'pk',
                'title' => 'string',
                'tag' => 'string',
                'module_id' => 'integer',
                'status_id' => 'integer',
                'create_date' => 'date',
                'start_date' => 'date',
                'end_date' => 'date'
            )
        );
        $this->createTable(
            'modules',
            array(
                'id' => 'pk',
                'title' => 'string',
                'tag' => 'string',
                'author_id' => 'integer',
                'system_name' => 'string',
            )
        );
        $this->createTable(
            'users2games',
            array(
                'id' => 'pk',
                'user_id' => 'string',
                'game_id' => 'integer',
                'role_id' => 'integer',
            )
        );

        $this->createTable(
            'game_statuses',
            array(
                'id' => 'pk',
                'title' => 'string',
            )
        );
        $this->insert('game_statuses', array('id' => 1, 'title' => 'Открыта для записи'));
        $this->insert('game_statuses', array('id' => 2, 'title' => 'Запись окончена'));
        $this->insert('game_statuses', array('id' => 3, 'title' => 'Идет'));
        $this->insert('game_statuses', array('id' => 10, 'title' => 'Завершена'));
        $this->insert('game_statuses', array('id' => 11, 'title' => 'Отменена'));

        $this->createTable(
            'game_roles',
            array(
                'id' => 'pk',
                'title' => 'string',
            )
        );
        $this->insert('game_roles', array('id' => 1, 'title' => 'Ведущий'));
        $this->insert('game_roles', array('id' => 2, 'title' => 'Заявка'));
        $this->insert('game_roles', array('id' => 3, 'title' => 'Игрок'));
        $this->insert('game_roles', array('id' => 10, 'title' => 'Забанен'));
	}

	public function down()
	{
		echo "m130720_181017_create_games does not support migration down.\n";
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