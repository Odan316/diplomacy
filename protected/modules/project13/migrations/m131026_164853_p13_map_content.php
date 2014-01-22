<?php

class m131026_164853_p13_map_content extends CDbMigration
{
	public function up()
	{
        $this->createTable('p13_map_base', array(
            'id' => 'pk',
            'x' => 'integer',
            'y' => 'integer',
            'object_type' => 'integer',
            'object_gfx' => 'integer',
            'object_id' => 'integer'
        ));
        $this->createTable('p13_land_objects_types_base', array(
            'id' => 'pk',
            'tag' => 'string',
            'name_rus' => 'string',
            'category' => 'string'
        ));
        $this->createTable('p13_land_objects_effects_base', array(
            'id' => 'pk',
            'type_id' => 'string',
            'effect_id' => 'string',
            'effect_value' => 'string',
        ));
        $this->createTable('p13_land_objects_gfx_base', array(
            'id' => 'pk',
            'type_id' => 'string',
            'gfx' => 'string',
        ));

        $this->insert('p13_land_objects_types_base',
            array('tag' => 'sea', 'name_rus' => 'Море', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'plain', 'name_rus' => 'Равнина', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'hills', 'name_rus' => 'Холмы', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'low_mountains', 'name_rus' => 'Низкие горы', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'high_mountains', 'name_rus' => 'Высокие горы', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'taiga', 'name_rus' => 'Тайга', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'steppe', 'name_rus' => 'Степь', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'desert', 'name_rus' => 'Пустыня', 'category' => 'landtype'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'river', 'name_rus' => 'Река', 'category' => 'landobj'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'forest', 'name_rus' => 'Лес', 'category' => 'landobj'));
        $this->insert('p13_land_objects_types_base',
            array('tag' => 'coast', 'name_rus' => 'Побережье', 'category' => 'landobj'));

        $this->insert('p13_land_objects_gfx_base', array('type_id' => '1', 'gfx' => '#1E90FF'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '2', 'gfx' => '#00FF00'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '3', 'gfx' => '#ADFF2F'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '4', 'gfx' => '#808000'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '5', 'gfx' => '#8B4513'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '6', 'gfx' => '#C0C0C0'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '7', 'gfx' => '#F0E68C'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '8', 'gfx' => '#FFFF00'));
        // rivers
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_d'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_d_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_d'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_d_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_d_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_d_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '9', 'gfx' => 'river_r_d_u'));

        $this->insert('p13_land_objects_gfx_base', array('type_id' => '10', 'gfx' => 'forest_1'));

        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_d'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_d'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_d_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_d_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_d_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_l_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_d_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_l'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_d_u'));
        $this->insert('p13_land_objects_gfx_base', array('type_id' => '11', 'gfx' => 'coast_r_d_l_u'));
    }

	public function down()
	{
        $this->dropTable('p13_map_base');
        $this->dropTable('p13_land_objects_types_base');
        $this->dropTable('p13_land_objects_effects_base');
        $this->dropTable('p13_land_objects_gfx_base');
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