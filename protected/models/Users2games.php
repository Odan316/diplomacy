<?php
/**
 * Class Users2games
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $user_id
 * @property integer $role_id
 *
 * @property Games $games
 */
class Users2games extends CActiveRecord
{
    /**
     * @param string $className
     * @return Users2games
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
            'game_id'       => 'Игра',
            'user_id'       => 'Пользователь',
            'role_id'       => 'Роль'
        );
    }

    public function rules()
    {
        return array(
            array('game_id, user_id, role_id', 'required')
        );
    }

    public function relations()
    {
        return array(
            'games' => array(self::BELONGS_TO, 'Games', 'game_id'),
        );
    }
}
?>
