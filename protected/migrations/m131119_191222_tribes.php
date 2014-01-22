<?php

class m131119_191222_tribes extends CDbMigration
{
	public function up()
	{
        $this->createTable('p13_tribes', array(
            'id' => 'pk',
            'game_id' => 'integer',
            'turn' => 'integer',
            'color' => 'string',
            'name' => 'string',
            'player_id' => 'integer',
            'migration' => 'boolean'
        ));
        $this->createTable('p13_clans', array(
            'id' => 'pk',
            'game_id' => 'integer',
            'turn' => 'integer',
            'name' => 'string',
            'tribe_id' => 'integer',
            'is_main' => 'boolean'
        ));
        $this->createTable('p13_strata', array(
            'id' => 'pk',
            'game_id' => 'integer',
            'turn' => 'integer',
            'clan_id' => 'integer',
            'strata_type' => 'integer',
            'quantity' => 'integer'
        ));
	}

	public function down()
	{
        $this->dropTable('p13_tribes');
        $this->dropTable('p13_clans');
        $this->dropTable('p13_strata');
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