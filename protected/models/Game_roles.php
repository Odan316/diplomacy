<?php
/**
 * Class Game_roles
 *
 * @property integer $id
 * @property string $title Название
 */
class Game_roles extends CActiveRecord
{
    /**
     * константы ролей
     */
    const GM_ROLE = 1;
    const CLAIM_ROLE = 2;
    const PLAYER_ROLE = 3;

    /**
     * @param string $className
     * @return Games
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'game_roles';
    }

    public function attributeLabels()
    {
        return array(
            "id"            => '#',
            'title'         => 'Название'
        );
    }

    public function rules()
    {
        return array(
            array('title', 'required')
        );
    }
}
?>
