<?php
/**
 * Class P13Map
 *
 * Класс для работы с экземпляром карты Проекта13
 */

class P13Map {

    /**
     * @var P13Map Переменная для хранения статического экземпляра класса для работы со статическими функциями
     */
    private static $instance;

    /**
     * @var int ИД игры
     */
    private $_game_id;

    /**
     * @var int ИД игры
     */
    private $_turn;

    /**
     * @var string Путь к папке данных игры
     */
    private $_common_path;

    /**
     * @var string Путь к папке карт игры
     */
    private $_map_path;

    /**
     * @var string Имя файла карты
     */
    private $_map_file;

    /**
     * @var array Ячейки карты
     */
    private $_cells = false;

    /**
     * Создает пустой экземпляр карты для доступа к статическим методам, не требующим загруженной карты
     *
     * @return P13Map
     */
    public static function stat()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self(false, false);
        }
        return self::$instance;
    }

    /**
     * Конструктор экземпляра карты
     *
     * @param $game_id
     * @param $turn
     */
    public function __construct($game_id, $turn)
    {
        if($game_id && $turn !== false){
            $this->_game_id = $game_id;
            $this->_turn = $turn;
            $this->_map_path = Yii::app()->params['rootPath']."/protected/modules/project13/data/games/".$game_id."/maps/";
            $this->_map_file = "turn_".$turn.".json";

            $this->loadMap();
            //CVarDumper::dump($this->_cells, 5, 1);
        }

        $this->_common_path = Yii::app()->params['rootPath']."/protected/modules/project13/data/common";
    }

    /**
     * Загрузка карты из файла в свойство класса
     */
    private function loadMap()
    {
        if(!is_dir($this->_map_path)) {
            mkdir($this->_map_path, 0777, 1);
        }

        if(file_exists($this->_map_path.$this->_map_file)){
            $map_file = fopen($this->_map_path.$this->_map_file, "r");
            if(filesize($this->_map_path.$this->_map_file)) {
                $map_string = fread($map_file, filesize($this->_map_path.$this->_map_file));
            } else {
                $map_string = "[]";
            }

            fclose($map_file);

            $this->_cells = json_decode($map_string, true);
        }
    }

    /**
     * Проверка на то, существует ли файл карты для сочетания игра-ход
     *
     * @return bool
     */
    public function exists()
    {
        return ($this->_cells !== false);
    }

    /**
     * Создание чистой карты с заданными размерами
     *
     * @param $width
     * @param $height
     */
    public function createBlankMap($width, $height)
    {
        $this->_cells = array();
        $map_array = array();
        for($y = 1; $y <= $height; $y++) {
            for($x = 1; $x <= $width; $x++) {
                $cell = array(
                    'x' => $x,
                    'y' => $y,
                    'objects' => array(array(
                        'object_type' =>  1,
                        'object_gfx' => 1
                    ))
                );
                $map_array[] = $cell;
                $this->_cells[$y][$x] = $cell;
            }
        }
        $map_json = json_encode($map_array);

        if(!is_dir($this->_map_path)) {
            mkdir($this->_map_path, 0777, 1);
        }
        $map_file = fopen($this->_map_path.$this->_map_file, "w");

        fwrite($map_file, $map_json);
        fclose($map_file);
    }

    public function createDefaultMap()
    {
        $map_json = "[]";

        $default_map_path = Yii::app()->params['rootPath']."/protected/modules/project13/data/common/default_map.json";
        if(file_exists($default_map_path)){
            $map_file = fopen($default_map_path, "r");
            if(filesize($default_map_path)) {
                $map_json = fread($map_file, filesize($default_map_path));
            }
            fclose($map_file);
        }

        if(!is_dir($this->_map_path)){
            mkdir($this->_map_path, 0777, 1);
        }

        $map_file = fopen($this->_map_path.$this->_map_file, "w");
        $res = fwrite($map_file, $map_json);
        fclose($map_file);

        echo (boolean)$res;
    }
    /**
     * Возвращает массив с информацией о карте
     *
     * @return array
     */
    public function getMapInfo()
    {
        if(is_array($this->_cells)){
            return array('width' => count($this->_cells[1]), 'height' => count($this->_cells));
        } else {
            return array();
        }
    }

    /**
     * Возвращает массив с полной информацией о всей карте в формате для отсылки на фронт
     *
     * @return array
     */
    public function getFullMapArray()
    {
        return $this->buildMapArray($this->_cells);
    }

    /**
     * Возвращает массив с полной информацией об участке карты в формате для отсылки на фронт
     *
     * @param $width
     * @param $height
     * @param $center_x
     * @param $center_y
     *
     * @return array
     */
    public function getMapArray($width, $height, $center_x, $center_y)
    {
        //TODO: Сделать функцию
    }

    /**
     * Формирует массив с полной информацией о выбранных клетках в формате для отсылки на фронт
     *
     * @param $cell_data array()
     *
     * @return array()
     */
    private function buildMapArray($cell_data)
    {
        $map_array = array();
        $map_objects = self::getObjectTypesList();
        foreach($cell_data as $row){
            foreach($row as $cell){
                $map_array[$cell['y']][$cell['x']]['objects'] = array();
                $map_array[$cell['y']][$cell['x']]['landtype'] = array();
                foreach($cell['objects'] as $object) {
                    $object_type = $map_objects[$object['object_type']];
                    if($object_type['category'] == 'landtype'){
                        $map_array[$cell['y']][$cell['x']]['landtype'] = array(
                            'name' => $object_type['name_rus'],
                            'type' => $object['object_type'],
                            'obj_gfx' => $object['object_gfx'],
                            'gfx' => $object_type['gfx'][$object['object_gfx']]
                        );
                    } else {
                        $map_array[$cell['y']][$cell['x']]['objects'][$object['object_type']] = array(
                            'name' => $object_type['name_rus'],
                            'category' => $object_type['category'],
                            'type' => $object['object_type'],
                            'obj_gfx' => $object['object_gfx'],
                            'gfx' => Yii::app()->controller->module->assetsBase.'/images/map_icons/'.$object_type['gfx'][$object['object_gfx']].'.png',
                        );
                    }
                }
            }
        }

        return $map_array;
    }

    /**
     * Сохраняет карту в файл
     *
     * @param $map_data Массив данных о ячейках карты
     *
     * @return bool
     */
    public function saveMap($map_data)
    {
        $map_array = array();
        foreach($map_data as $y => $row){
            foreach($row as $x => $cell){
                $objects = array();
                if(isset($cell->landtype->type)){
                    $objects[] = array(
                        'object_type' =>  $cell->landtype->type,
                        'object_gfx' => $cell->landtype->obj_gfx,
                    );
                }
                if(!empty($cell->objects)){
                    foreach($cell->objects as $map_object){
                        if(!empty($map_object)){
                            $objects[] = array(
                                'object_type' => $map_object->type,
                                'object_gfx' => $map_object->obj_gfx
                            );
                        }
                    }
                }
                $map_array[$y][$x] = array(
                    'x' => $x,
                    'y' => $y,
                    'objects' => $objects
                );
            }
        }
        //CVarDumper::dump($map_array, 5, 1);
        $map_json = json_encode($map_array);

        if(!is_dir($this->_map_path)){
            mkdir($this->_map_path, 0777, 1);
        }

        $map_file = fopen($this->_map_path.$this->_map_file, "w");
        $res = fwrite($map_file, $map_json);
        fclose($map_file);

        echo (boolean)$res;
    }

    /**
     * Возвращает массив со списком типов объектов карты
     *
     * @return array
     */
    public static function getObjectTypesList()
    {
        return self::get_common("land_obj")[0];
    }

    /**
     * Возвращает информацию о типе объекта
     *
     * @param $object_type_id ИД типа объекта
     *
     * @return array()
     *
     * TODO: Переписать всю цепочку так, что бы передавать полную инфу а не только соответствие ИД - ссылка на графику
     */
    public function getObjectTypeInfo($object_type_id)
    {
        $object_type = self::get_common("land_obj")[0][$object_type_id];
        $return_list = array();
        foreach($object_type['gfx'] as $gfx_id => $gfx){
            if($object_type['category'] == 'landtype'){
                $return_list[$gfx_id] = $gfx;
            } elseif($object_type['category'] == 'landobj'){
                $return_list[$gfx_id] = Yii::app()->controller->module->assetsBase.'/images/map_icons/'.$gfx.'.png';
            }
        }

        return $return_list;
    }

    /**
     * Возвращает массив с загруженным в него файлом общих параметров
     *
     * @param $filename
     *
     * @return mixed
     */
    private static function get_common($filename)
    {
        $file_path = self::stat()->_common_path."/".$filename.".json";
        $file = fopen($file_path, "r");
        $json_string = fread($file, filesize($file_path));
        fclose($file);

        return json_decode($json_string, true);
    }



} 