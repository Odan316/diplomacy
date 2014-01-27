<?php

class m140127_090057_basic_install extends CDbMigration
{
    public function safeUp()
    {
        /* Создание таблиц пользователей */
        $this->createTable('users', array(
            'id' => 'pk',
            'login' => 'string',
            'password' => 'string',
            'salt' => 'string'
        ));
        $this->createTable('persons', array(
            'id' => 'pk',
            'user_id' => 'integer',
            'nickname' => 'string',
            'name' => 'string',
            'surname' => 'string',
            'patronymic' => 'string'
        ));

        /* Создание таблиц игр */
        $this->createTable('modules', array(
            'id' => 'pk',
            'title' => 'string',
            'tag' => 'string',
            'author_id' => 'integer',
            'system_name' => 'string',
            'active' => "ENUM('yes', 'no') DEFAULT 'no'"
        ));
        $this->createTable('games', array(
            'id' => 'pk',
            'title' => 'string',
            'tag' => 'string',
            'module_id' => 'integer',
            'status_id' => 'integer',
            'create_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'last_turn' => 'integer'
        ));
        $this->createTable('game_statuses', array(
            'id' => 'pk',
            'title' => 'string',
        ));
        $this->insert('game_statuses', array('id' => 1, 'title' => 'Открыта для записи'));
        $this->insert('game_statuses', array('id' => 2, 'title' => 'Запись окончена'));
        $this->insert('game_statuses', array('id' => 3, 'title' => 'Идет'));
        $this->insert('game_statuses', array('id' => 10, 'title' => 'Завершена'));
        $this->insert('game_statuses', array('id' => 11, 'title' => 'Отменена'));

        $this->createTable('game_roles', array(
            'id' => 'pk',
            'title' => 'string',
        ));
        $this->insert('game_roles', array('id' => 1, 'title' => 'Ведущий'));
        $this->insert('game_roles', array('id' => 2, 'title' => 'Заявка'));
        $this->insert('game_roles', array('id' => 3, 'title' => 'Игрок'));
        $this->insert('game_roles', array('id' => 10, 'title' => 'Забанен'));
        $this->createTable('users2games', array(
            'id' => 'pk',
            'user_id' => 'string',
            'game_id' => 'integer',
            'role_id' => 'integer',
        ));
    }

    public function safeDown()
    {
        $this->dropTable('users');
        $this->dropTable('persons');

        $this->dropTable('modules');
        $this->dropTable('games');
        $this->dropTable('game_statuses');
        $this->dropTable('game_roles');
        $this->dropTable('users2games');
    }
}