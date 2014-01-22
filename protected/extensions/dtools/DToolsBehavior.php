<?php
/**
 * User: Sergey
 * Date: 04.01.14
 * Time: 22:23
 */

class DToolsBehavior extends CActiveRecordBehavior
{
    /**
     * Возвращает массив соответствий id => title дела запрос в модель по заранее указанным условиям
     * @param bool $has_empty_choice
     * @param string $id_field
     * @param string $title_field
     * @return array
     */
    public function getSelectList($has_empty_choice = false, $id_field = 'id', $title_field = 'title')
    {
        if(!$has_empty_choice){
            return CHtml::listData($this->owner->findAll(), $id_field, $title_field);
        } else {
            return CMap::mergeArray(
                array(0 => 'Нет'),
                CHtml::listData($this->owner->findAll(), $id_field, $title_field)
            );
        }
    }
}