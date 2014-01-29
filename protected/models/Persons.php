<?php
/**
 * Class Persons
 *
 * @property integer $user_id Аккаунт
 * @property string $nickname Никнейм
 * @property string $name Имя
 * @property string $surname Фамилия
 * @property string $patronymic Отчество
 */
class Persons extends CActiveRecord
{
    /**
     * @param string $className
     * @return Persons
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return 'persons';
    }

    public function attributeLabels()
    {
        return array(
            "id"                => '#',
            'user_id'           => 'Аккаунт',
            'nickname'          => 'Никнейм',
            'name'              => 'Имя',
            'surname'           => 'Фамилия',
            'patronymic'        => 'Отчество'
        );
    }

    public function rules()
    {
        return array(
            array('user_id, nickname', 'required'),
            array('name, surname, patronymic', 'safe')
        );
    }

    /**
     * Условие для поиска моделей по заданным ID
     * @param $ids
     * @return Users
     */
    public function id_in($ids)
    {
        $criteria = $this->getDbCriteria();
        $criteria->addInCondition('t.id', $ids);

        return $this;
    }
}
?>
