<?php

/**
 * Class JSONModel
 *
 * Класс для работы с файлами игры в виде JSON
 */
abstract class JSONModel implements JsonSerializable
{
    /**
     * @var string Путь к папке данных модуля
     */
    protected $modelPath;

    /**
     * @var string Имя файла игры
     */
    protected $modelFile;

    /**
     * @var [] Сырые данные модели
     */
    protected $rawData;

    /**
     * Конструктор
     * Задает артибуты, если они переданы (формат: массив ключ => значение)
     *
     * @param [] $data
     */
    public function __construct( $data = [ ] )
    {
        if ( ! empty( $data )) {
            $this->setAttributes( $data );
        }
    }

    /**
     * Функция, задающая атрибуты (формат: массив ключ => значение)
     *
     * @param [] $data
     */
    public function setAttributes( $data )
    {
        foreach ($data as $param => $value) {
            if($this->$param != $value && method_exists($this, "onAttributeChange"))
                $this->onAttributeChange($param, $this->$param, $value);
            $this->$param = $value;

        }
    }

    /**
     * Задание пути к файлу (в том случае, если это модель, хранящаяся в отдельном файле)
     * Реализуется в конкретной модели
     */
    protected function setPaths()
    {
    }

    /**
     * Загрузка файла в свойство класса $rawData
     */
    protected function loadFromFile()
    {
        if (is_dir( $this->modelPath )) {
            $modelFilepath = $this->modelPath . $this->modelFile;
            if (file_exists( $modelFilepath )) {
                $file = fopen( $modelFilepath, "r" );
                if (filesize( $modelFilepath )) {
                    $dataString = fread( $file, filesize( $modelFilepath ) );
                } else {
                    $dataString = "[]";
                }

                fclose( $file );

                $this->rawData = json_decode( $dataString, true );

                $this->processRawData();
            }
        }
    }

    /**
     * Сохраняет массив сырых данных в файл и возвращает результат записи
     *
     * @return bool
     */
    protected function saveToFile()
    {
        if ( ! $this->fileExists()) {
            $file = $this->createNewFile();
        } else {
            $file = $this->openFile();
        }

        if ($file) {
            return (boolean) (fwrite( $file, json_encode($this)) && fclose( $file ));
        }
        return false;
    }

    /**
     * Проверяет, существует ли требуемая папка и файл
     *
     * @return bool
     */
    protected function fileExists()
    {
        return ( is_dir( $this->modelPath ) && file_exists( $this->modelPath . $this->modelFile ) );
    }

    /**
     * Создает новый файл и возвращает указатель на него или false в случае ошибки
     *
     * @return bool|resource
     */
    private function createNewFile()
    {
        $file = false;

        if ( ! ( $dir = is_dir( $this->modelPath ) )) {
            umask(0000);
            $dir = mkdir( $this->modelPath, 0777, 1 );
        }

        if ($dir) {
            $file = fopen( $this->modelPath . $this->modelFile, "w+" );
            fwrite( $file, json_encode($this));
            fclose( $file );
            chmod( $this->modelPath . $this->modelFile, 0777 );
            $file = fopen( $this->modelPath . $this->modelFile, "w" );
        }
        return $file;
    }

    /**
     * Открывает существующий файл и возвращает указатель на него или false в случае ошибки
     *
     * @return bool|resource
     */
    private function openFile()
    {
        return fopen( $this->modelPath . $this->modelFile, "w" );
    }

    /**
     * Загрузка сырых данных в атрибуты модели
     * Реализуется в конкретной модели
     */
    protected function processRawData()
    {
    }

    /**
     * Реализуется в конкретной модели
     *
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->rawData;
    }

    /**
     * Принимает нефильтрованный список моделей, фильрует его и если нужно конвертирует в массив
     *
     * @param JSONModel[] $modelsList
     * @param [] $criteria
     * @param bool $asArray
     *
     * @return JSONModel[]|[]
     */
    public function getModelsList($modelsList = [], $criteria = [], $asArray = false)
    {
        $filteredList = [];

        foreach($modelsList as $model){
            if($model->testCriteria($criteria)) $filteredList[] = $model;
        }

        if ( ! $asArray) {
            return $filteredList;
        } else {
            return $this->makeList($filteredList);
        }
    }

    /**
     * Проверяет модель на соответствие присланному набору критериев в формате название поля => значение поля
     *
     * @param [] $criteria
     *
     * @return bool
     */
    public function testCriteria($criteria = [])
    {
        $meetCriteria = true;
        foreach($criteria as $property => $value) {
            $model = $this;
            $objAlias = explode(".", $property);
            $propertyGetter = "get".array_pop($objAlias);
            foreach($objAlias as $modelName){
                $model = call_user_func( [ $model, "get" . $modelName ] );
            }

            if(!is_array($value)) $value = [$value];
            // Проверки
            if(in_array("notIn", $value, true)){
                $value = $value[1];
                if(in_array($model->$propertyGetter(), $value)){
                    $meetCriteria = false;
                    break;
                }
            }
            elseif(in_array("in", $value, true)){
                $value = $value[1];
                if(!in_array($model->$propertyGetter(), $value)){
                    $meetCriteria = false;
                    break;
                }
                break;
            }
            else if(!in_array($model->$propertyGetter(), $value)){
                $meetCriteria = false;
                break;
            }
        }
        return $meetCriteria;
    }


    /**
     * Преобразует набор моделей в ассоциативный массив
     *
     * @param \JSONModel[] $models
     *
     * @return array
     */
    public static function makeList($models){
        $list = [ ];
        foreach ($models as $model) {
            $list[] = $model->jsonSerialize();
        }
        return $list;
    }

    protected function onAttributeChange($attributeName, $oldValue, $newValue)
    {
    }
} 