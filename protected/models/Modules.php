<?php
/**
 * Class Modules
 *
 * @property integer $id
 * @property string $title Название
 * @property string $tag Тэг
 * @property integer $author_id Автор
 * @property string $system_name Системное имя модуля
 * @property string $active Активный
 *
 * @method Modules active() Условие для получения только активных модулей
 *
 * @method array getSelectList($has_empty_choice = false, $id_field = 'id', $title_field = 'title') Возвращает массив соответствий id => title дела запрос в модель по заранее указанным условиям
 */
class Modules extends CActiveRecord
{
    /**
     * @param string $className
     * @return Modules
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'modules';
    }

    public function attributeLabels()
    {
        return array(
            "id"            => '#',
            'title'         => 'Название',
            'tag'           => 'Тэг',
            'author_id'     => 'Автор',
            'system_name'   => 'Системное имя',
            'active'        => 'Активный',
        );
    }

    public function rules()
    {
        return array(
            array('title, tag, author_id, system_name', 'required'),
            array('', 'safe'),
            array('active', 'in', array('yes', 'no'))
        );
    }

    public function behaviors()
    {
        return array(
            'dtools'=>array(
                'class'=>'ext.dtools.DToolsBehavior'
            ),
        );
    }

    public function scopes()
    {
        return array(
            'active' => array(
                'condition' => "active = 'yes'"
            )
        );
    }
}
?>
