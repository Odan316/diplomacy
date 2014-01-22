<?php
/**
 * Class Controller
 *
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu = array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs = array();


    public function init(){
        Yii::app()->name = 'Дипломатия';
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        //$ClientScript->registerScriptFile($this->assetsBase.'/main/js/jquery-1.9.1.min.js'); Yii-Debug-Toolbar не поддерживает ((
        $ClientScript->registerScriptFile($this->assetsBase.'/main/js/common.js');
    }

    public function beforeAction($action) {
        if(Yii::app()->user->isGuest){
            if(Yii::app()->controller->id != 'site'){
                $this->redirect($this->createUrl('/site/login'));
            }
        }
        elseif(Yii::app()->controller->id == 'site'
                && !(Yii::app()->controller->action->id == 'logout' || Yii::app()->controller->action->id == 'error')){
                $this->redirect($this->createUrl('/cabinet'));
        }
        return parent::beforeAction($action);
    }

    /**
     * метод для работы с ассетами, взят здесь : http://habrahabr.ru/post/139166/
     */
    private $_assetsBase;
    public function getAssetsBase()
    {
        if ($this->_assetsBase === null) {
            $this->_assetsBase = Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('application.assets'),
                false,
                -1,
                YII_DEBUG
            );
        }
        return $this->_assetsBase;
    }
}