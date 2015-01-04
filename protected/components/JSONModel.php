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
            $dir = mkdir( $this->modelPath, 0777, 1 );
        }

        if ($dir) {
            $file = fopen( $this->modelPath . $this->modelFile, "w+" );
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
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->rawData;
    }
} 