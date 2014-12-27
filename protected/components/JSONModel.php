<?php

/**
 * Class JSONModel
 *
 * Класс для работы с файлами игры в виде JSON
 */
class JSONModel
{
    /**
     * @var string Путь к папке данных модуля
     */
    protected $model_path;

    /**
     * @var string Имя файла игры
     */
    protected $model_file;

    /**
     * @var [] Сырые данные модели
     */
    protected $raw_data;

    /**
     * @var [] Обработанные данные модели (хранилище по умолчанию)
     */
    protected $attributes = [ ];

    /**
     * Загрузка файла в свойство класса $data
     */
    protected function loadFromFile()
    {
        if (is_dir( $this->model_path )) {
            $model_filepath = $this->model_path . $this->model_file;
            if (file_exists( $model_filepath )) {
                $file = fopen( $model_filepath, "r" );
                if (filesize( $model_filepath )) {
                    $data_string = fread( $file, filesize( $model_filepath ) );
                } else {
                    $data_string = "[]";
                }

                fclose( $file );

                $this->raw_data = json_decode( $data_string, true );

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
            $this->parseRawData();

            $data_json = json_encode( $this->raw_data );

            return (boolean) fwrite( $file, $data_json ) && fclose( $file );
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
        return ( is_dir( $this->model_path ) && file_exists( $this->model_path . $this->model_file ) );
    }

    /**
     * Создает новый файл и возвращает указатель на него или false в случае ошибки
     *
     * @return bool|resource
     */
    private function createNewFile()
    {
        $file = false;

        if ( ! ( $dir = is_dir( $this->model_path ) )) {
            $dir = mkdir( $this->model_path, 0777, 1 );
        }

        if ($dir) {
            $file = fopen( $this->model_path . $this->model_file, "w+" );
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
        return fopen( $this->model_path . $this->model_file, "w" );
    }

    /**
     * Загрузка сырых данных в атрибуты модели
     * Может переопределятся в конкретной модели
     */
    protected function processRawData()
    {
        $this->attributes = $this->raw_data;
    }

    /**
     * Выгрузка атрибутов модели в сырые данные
     * Может переопределятся в конкретной модели для
     */
    protected function parseRawData()
    {
        $this->raw_data = $this->attributes;
    }
} 