<?php

class m131012_152135_p13_base extends CDbMigration
{
	public function up()
	{
        // Game Tabs

        $this->createTable('p13_requests', array(
            'id' => 'pk',
            'game_id' => 'integer',
            'turn' => 'integer',
            'tribe_id' => 'integer',
            'request_json' => 'text'
        ));
        $this->createTable('p13_map', array(
            'id' => 'pk',
            'x' => 'integer',
            'y' => 'integer',
            'object_type' => 'integer',
            'object_id' => 'integer'
        ));
        $this->createTable('p13_tribes_tech', array(
            'id' => 'pk',
            'tribe_id' => 'integer',
            'tech_id' => 'integer'
        ));
        $this->createTable('p13_tribes_decisions', array(
            'id' => 'pk',
            'tribe_id' => 'integer',
            'decision_id' => 'integer',
            'option_id' => 'integer'
        ));
        $this->createTable('p13_tribes_diplomacy', array(
            'id' => 'pk',
            'tribe_id' => 'integer',
            'neighbour_id' => 'integer',
            'status_id' => 'integer',
        ));
        // Config Tables
        $this->createTable('p13_strata_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string'
        ));


        $this->createTable('p13_tech_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string',
        ));
        $this->createTable('p13_tech_conditions_base', array(
            'id' => 'pk',
            'tech_id' => 'integer',
            'condition_type' => 'integer',
            'condition_value' => 'float',
        ));
        $this->createTable('p13_tech_effects_base', array(
            'id' => 'pk',
            'tech_id' => 'integer',
            'effect_type' => 'integer',
            'effect_value' => 'float',
        ));

        $this->createTable('p13_decisions_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string',
        ));
        $this->createTable('p13_decisions_conditions_base', array(
            'id' => 'pk',
            'decision_id' => 'integer',
            'condition_type' => 'integer',
            'condition_value' => 'float',
        ));
        $this->createTable('p13_decisions_options_base', array(
            'id' => 'pk',
            'decision_id' => 'integer',
            'tag' => 'string',
            'name_rus' => 'string',
        ));
        $this->createTable('p13_decisions_options_effects_base', array(
            'id' => 'pk',
            'decision_id' => 'integer',
            'options_id' => 'integer',
            'effect_type' => 'integer',
            'effect_value' => 'float',
        ));

        $this->createTable('p13_conditions_types_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string'
        ));
        $this->createTable('p13_effects_types_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string'
        ));

        $this->createTable('p13_diplomacy_statuses_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string'
        ));


        $this->insert('p13_strata_base', array('tag' => 'aristocracy', 'name_rus' => 'Аристократы'));
        $this->insert('p13_strata_base', array('tag' => 'hunters', 'name_rus' => 'Охотники'));
        $this->insert('p13_strata_base', array('tag' => 'gatherers', 'name_rus' => 'Собиратели'));
        $this->insert('p13_strata_base', array('tag' => 'warriors', 'name_rus' => 'Воины'));
        $this->insert('p13_strata_base', array('tag' => 'herdmans', 'name_rus' => 'Скотоводы'));
        $this->insert('p13_strata_base', array('tag' => 'farmers', 'name_rus' => 'Земледельцы'));
        $this->insert('p13_strata_base', array('tag' => 'shamans', 'name_rus' => 'Шаманы'));
        $this->insert('p13_strata_base', array('tag' => 'prests', 'name_rus' => 'Священники'));
        $this->insert('p13_strata_base', array('tag' => 'oldmen', 'name_rus' => 'Старики'));
        $this->insert('p13_strata_base', array('tag' => 'kids', 'name_rus' => 'Дети'));
	}

	public function down()
	{
        $this->dropTable('p13_requests');
        $this->dropTable('p13_map');
        $this->dropTable('p13_tribes_tech');
        $this->dropTable('p13_tribes_decisions');
        $this->dropTable('p13_tribes_diplomacy');

        $this->dropTable('p13_strata_base');

        $this->dropTable('p13_tech_base');
        $this->dropTable('p13_tech_conditions_base');
        $this->dropTable('p13_tech_effects_base');

        $this->dropTable('p13_decisions_base');
        $this->dropTable('p13_decisions_conditions_base');
        $this->dropTable('p13_decisions_options_base');
        $this->dropTable('p13_decisions_options_effects_base');

        $this->dropTable('p13_conditions_types_base');
        $this->dropTable('p13_effects_types_base');
        $this->dropTable('p13_diplomacy_statuses_base');
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