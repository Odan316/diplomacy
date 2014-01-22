<?php
/**
 * Class Game_statuses
 *
 * @property integer $id
 * @property integer $user_id Пользователь
 * @property integer $game_id Игра
 * @property integer $role_id Роль
 */
class Game_statuses extends CActiveRecord
{
    /**
     * константы статусов
     */
    const OPEN_GAME = 1;
    const REGISTR_CLOSED = 2;
    const ACTIVE = 3;
    const ENDED = 10;
    const CANCELLED = 11;

    /**
     * @param string $className
     * @return Game_statuses
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'users2games';
    }

    public function attributeLabels()
    {
        return array(
            "id"            => '#',
            'user_id'       => 'Пользователь',
            'game_id'       => 'Игра',
            'role_id'       => 'Роль',
        );
    }

    public function rules()
    {
        return array(
            array('user_id, game_id, role_id', 'safe')
        );
    }
}
?>
