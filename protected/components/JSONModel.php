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
     * Перегруженная функция __call, обеспечивающая геттеры и сеттеры защищенных свойств модели.
     *
     * Что бы геттер или сеттер сработал название функции должно начинаться с get или set, соответственно,
     * а после содержать название публичного или защищенного свойства класса,
     * в т.ч. должен совпадать регистр (за исключением первого символа, который автоматически приводится к нижнему регистру)
     * Например - getOfficerId() и getofficerId вернет свойство $officerId,
     * но не вернет свойств $OfficerId (которое вообще не сможет быть возвращено автогеттером из-за первого символа в верхнем регистре)
     * или $officerid из-за несовпадения регистра седьмого символа
     *
     * Автосеттер принимает 1 параметр - новое значение свойства и возвращает обеъект, которому оно было присвоено
     * Автосеттер обеспечивает выполнение события onAttributeChange()
     *
     * Автогеттер не принимает параметров, возвращает текущее значение параметра
     *
     * @param string $name Function name
     * @param [] $arguments Function arguments
     *
     * @return void|mixed
     */
    public function __call($name, $arguments)
    {
        if(preg_match("/^set(.+)/", $name, $parts)){
            $property = lcfirst($parts[1]);
            if(property_exists($this, $property)){
                $this->setAttribute($property, $arguments[0]);
                return $this;
            }
        }
        if(preg_match("/^get(.+)/", $name, $parts)){
            $property = lcfirst($parts[1]);
            if(property_exists($this, $property)){
                return $this->$property;
            }
        }
    }

    /**
     * Функция, задающая атрибуты (формат: массив ключ => значение)
     *
     * @param [] $data
     */
    public function setAttributes( $data )
    {
        foreach ($data as $attribute => $value) {
            $this->setAttribute($attribute, $value);
        }
    }

    public function setAttribute($attribute, $value)
    {
        $this->onAttributeChange($attribute, $this->$attribute, $value);
        $this->$attribute = $value;
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
            }
            elseif(in_array("hasFlag", $value, true)){
                $value = $value[1];
                if(!method_exists($this, "hasFlag") || !$model->hasFlag($value)){
                    $meetCriteria = false;
                    break;
                }
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

    /**
     * Вызывается перед каждым изменении каждого атрибута модели,
     * эту функцию следует реализовать в модели, по умолчанию ничего не делает
     *
     * @param string $attributeName Содержит имя измененого атрибута
     * @param mixed $oldValue Содержит старое значение атрибута
     * @param mixed $newValue Содержит новое значение атрибута
     */
    protected function onAttributeChange($attributeName, $oldValue, $newValue)
    {
    }
} 