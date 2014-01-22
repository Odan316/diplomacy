<?php

class Project13Module extends CWebModule
{
	public function init()
	{
        Yii::app()->name = 'Дипломатия: Проект 13';
		// import the module-level models and components
		$this->setImport(array(
			'project13.models.*',
			'project13.components.*',
            'project13.map.*',
		));
	}
    /**
     * метод для работы с ассетами, взят здесь : http://habrahabr.ru/post/139166/
     */
    private $_assetsBase;
    public function getAssetsBase()
    {
        if ($this->_assetsBase === null) {
            $this->_assetsBase = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('project13.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
        return $this->_assetsBase;
    }
}
