<?php
/**
 * Class P13MapDB Класс для работы с БД карты Проекта 13
 */
class P13MapDB{
    /** @var CDbConnection */
    private $db;

    public function __construct()
    {
        $this->db = Yii::app()->db;
    }

    /**
     * Проверяет наличие таблицы
     * @param $game_id integer ИД игры
     *
     * @return bool
     */
    public function BD_exists($game_id)
    {
        /** @var $query CDbCommand */
        $query = $this->db->createCommand("SHOW TABLES LIKE 'p13_game_map_".$game_id."'");
        return (bool)$query->query()->rowCount;
    }

    /**
     * Создает таблицу с пустой картой для игры
     * @param $width integer ширина карты в клетках
     * @param $height integer высота карты в клетках
     * @param $game_id integer ИД игры
     */
    public function createBlankMap($width, $height, $game_id)
    {
        $this->db->createCommand()->createTable("p13_game_map_".$game_id, array(
            'id' => 'pk',
            'x' => 'integer',
            'y' => 'integer',
            'object_type' => 'integer',
            'object_gfx' => 'integer',
            'object_id' => 'integer'
        ));
        $insert_array = array();
        for($y = 1; $y <= $height; $y++){
            for($x = 1; $x <= $width; $x++){
                $insert_array[] = array(
                    'x' => $x,
                    'y' => $y,
                    'object_type' => 1,
                    'object_gfx' => 1,
                    'object_id' => NULL
                );
            }
        }
        /** @var $builder CDbCommandBuilder */
        $builder=Yii::app()->db->schema->commandBuilder;
        $command = $builder->createMultipleInsertCommand("p13_game_map_".$game_id, $insert_array);
        $command->execute();
    }

    /**
     * Возвращает массив с краткой информацией о карте
     * @param $game_id ИД игры
     *
     * @return array()
     */
    public function getMapInfo($game_id)
    {
        /** @var $width CDbDataReader */
        $width = $this->db->createCommand()
            ->select("id")
            ->from("p13_game_map_".$game_id)
            ->group('x')
            ->query();
        $height = $this->db->createCommand()
            ->select("id")
            ->from("p13_game_map_".$game_id)
            ->group('y')
            ->query();
        return array('width' => $width->getRowCount(), 'height' => $height->getRowCount());
    }

    /**
     * Возвращает массив с информацией о заданном участке карты
     * @param $game_id ИД игры
     * @param $width ширина требуемого участка
     * @param $height высота требуемого участка
     * @param $center_x координата по Х центральной клетки (если ширина четная - сдвигается влево)
     * @param $center_y координата по Y центральной клетки (если высота четная - сдвигается вверх)
     *
     * @return array()
     */
    public function getMapArray($game_id, $width, $height, $center_x, $center_y)
    {
        $map_object = $this->db->createCommand()
            ->select('m.x, m.y, m.object_type, m.object_gfx, m.object_id, types.category, types.name_rus AS name, gfx.gfx')
            ->from("p13_game_map_".$game_id." AS m")
            ->join('p13_land_objects_types_base AS types', 'types.id = m.object_type')
            ->join('p13_land_objects_gfx_base AS gfx', 'gfx.id = m.object_gfx')
            ->where('x > :x_min', array(':x_min' => $center_x - floor($width/2)))
            ->andWhere('x < :x_max', array(':x_max' => $center_x + ceil($width/2) + 1))
            ->andWhere('y > :y_min', array(':y_min' => $center_y - floor($height/2)))
            ->andWhere('y < :y_max', array(':y_max' => $center_y + ceil($height/2) + 1))
            ->order(array('y', 'x asc'))
            ->query();

        return $this->buildMapArray($map_object);
    }

    /**
     * Возвращает массив с информацией обо всех ячейках карты
     * @param $game_id ИД игры
     *
     * @return array()
     */
    public function getFullMapArray($game_id)
    {
        $map_object = $this->db->createCommand()
            ->select('m.x, m.y, m.object_type, m.object_gfx, m.object_id, types.category, types.name_rus AS name, gfx.gfx')
            ->from("p13_game_map_".$game_id." AS m")
            ->join('p13_land_objects_types_base AS types', 'types.id = m.object_type')
            ->join('p13_land_objects_gfx_base AS gfx', 'gfx.id = m.object_gfx')
            ->order(array('y', 'x asc'))
            ->query();

        return $this->buildMapArray($map_object);
    }

    /**
     * Возвращает объект со списком типов объектов карты
     *
     * @return CDbDataReader
     */
    public function getObjectTypesList()
    {
        return $types_list = $this->db->createCommand()
            ->select('types.id, types.category, types.name_rus, gfx.gfx')
            ->from('p13_land_objects_types_base AS types')
            ->join('p13_land_objects_gfx_base AS gfx', 'types.id = gfx.type_id')
            ->group('types.id')
            ->order('types.id asc')
            ->query();
    }

    /**
     * Возвращает информацию о типе объекта
     * @param $object_type_id ИД типа объекта
     *
     * @return array()
     */
    public function getObjectTypeInfo($object_type_id)
    {
        /** @var $object_type CDbDataReader */
        $object_type = $types_list = $this->db->createCommand()
            ->select('*')
            ->from('p13_land_objects_types_base')
            ->where('id = :id', array(':id' => $object_type_id))
            ->query();
        return $object_type->read();
    }
    /**
     * Возвращает объект с набором вариантов для типа объекта
     * @param $object_type_id ИД типа объекта
     *
     * @return CDbDataReader
     */
    public function getObjectGFXs($object_type_id)
    {
        return $types_list = $this->db->createCommand()
            ->select('*')
            ->from('p13_land_objects_gfx_base AS obj')
            ->where('type_id = :type_id', array(':type_id' => $object_type_id))
            ->order('obj.id asc')
            ->query();
    }

    /**
     * Сохраняет данные из массива в БД
     *
     * @param $game_id ИД игры
     * @param $map_data Массив данных о ячейках карты
     *
     * @return boolean Результат записи в БД
     */
    public function saveMap($game_id, $map_data)
    {
        /** @var $query CDbCommand */
        $query = $this->db->createCommand("SHOW TABLES LIKE 'p13_game_map_".$game_id."'");
        if($query->query()->rowCount){
            $this->db->createCommand()->truncateTable("p13_game_map_".$game_id);
            $insert_array = array();
            foreach($map_data as $y => $row){
                foreach($row as $x => $cell){
                    $insert_array[] = array(
                        'x' => $x,
                        'y' => $y,
                        'object_type' => $cell->landtype->type,
                        'object_gfx' => $cell->landtype->obj_gfx,
                        'object_id' => NULL
                    );
                    foreach($cell->objects as $map_object){
                        //CVarDumper::dump($map_object, 5, 1);
                        if(!empty($map_object)){
                            $insert_array[] = array(
                                'x' => $x,
                                'y' => $y,
                                'object_type' => $map_object->type,
                                'object_gfx' => $map_object->obj_gfx,
                                'object_id' => NULL
                            );
                        }
                    }
                }
            }
            /** @var $builder CDbCommandBuilder */
            $builder=Yii::app()->db->schema->commandBuilder;
            $command = $builder->createMultipleInsertCommand("p13_game_map_".$game_id, $insert_array);
            $command->execute();
            return true;
        } else{
            return false;
        }
    }

    /**
     * Преобразует объект с реультатами запроса к базе в массив
     * @param $map_object CDbDataReader
     *
     * @return array()
     */
    private function buildMapArray($map_object)
    {
        $map_array = array();
        foreach($map_object as $object){
            if($object['category'] == 'landtype'){
                $map_array[$object['y']][$object['x']]['objects'] = array();
                $map_array[$object['y']][$object['x']]['landtype'] = array(
                    'name' => $object['name'],
                    'type' => $object['object_type'],
                    'obj_gfx' => $object['object_gfx'],
                    'gfx' => $object['gfx']
                );
            } else {
                $map_array[$object['y']][$object['x']]['objects'][$object['object_type']] = array(
                    'name' => $object['name'],
                    'category' => $object['category'],
                    'type' => $object['object_type'],
                    'obj_gfx' => $object['object_gfx'],
                    'gfx' => Yii::app()->controller->module->assetsBase.'/images/map_icons/'.$object['gfx'].'.png',
                    'obj_id' => $object['object_id']
                );
            }
        }

        return $map_array;
    }


    /**
     * Экспортирует карту из БД в файл
     *
     * @param $game_id ИД игры
     *
     * @return bool
     */
    public function exportMap($game_id)
    {
        $map_data = $this->getFullMapArray($game_id);
        $map_array = array();
        foreach($map_data as $y => $row){
            foreach($row as $x => $cell){
                $objects = array();
                $objects[] = array(
                    'object_type' => $cell['landtype']['type'],
                    'object_gfx' => $cell['landtype']['obj_gfx'],
                );
                foreach($cell['objects'] as $map_object){
                    if(!empty($map_object)){
                        $objects[] = array(
                            'object_type' => $map_object['type'],
                            'object_gfx' => $map_object['obj_gfx']
                        );
                    }
                }
                $map_array[] = array(
                    'x' => $x,
                    'y' => $y,
                    'objects' => $objects
                );
            }
        }

        $map_json = json_encode($map_array);

        $maps_path = Yii::app()->params['rootPath']."/protected/modules/project13/data/common/";
        if(!is_dir($maps_path)){
            mkdir($maps_path, 0777, 1);
        }

        $map_filename = "default_map.json";

        $map_file = fopen($maps_path.$map_filename, "w");
        fwrite($map_file, $map_json);
        fclose($map_file);

        echo true;
    }
}