<?php
/**
 * Class GameController Контроллер для работы в Кабинете (Ведущего или Игрока)
 */
class GameController extends Controller
{
    /** @var $user_model Users */
    private $user_model;
    /** @var $game_model Games */
    private $game_model;

    public function init()
    {
        $this->layout = 'main';
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        $ClientScript->registerCssFile($this->module->assetsBase.'/css/styles.css');
        $ClientScript->registerScriptFile($this->module->assetsBase.'/js/project13.js');

        parent::init();
    }

    public function beforeAction($action)
    {
        $game_id = false;
        /** @var $user CWebUser */
        $user = Yii::app()->user;
        if(isset($this->actionParams['id'])){
            $game_id = $this->actionParams['id'];
            $cookie = new CHttpCookie('game_id', $game_id);
            $cookie->expire = time()+60*60*24*30;
            Yii::app()->request->cookies['game_id'] = $cookie;
        } elseif(isset(Yii::app()->request->cookies['game_id'])){
            $game_id = Yii::app()->request->cookies['game_id']->value;
        } elseif (!$user->getState('game_id')) {
            $this->redirect($this->createUrl('cabinet/no_such_game'));
        }

        if(!$user->getState('game_role')){
            $user_role = Users2games::model()->findByAttributes(array(
                'user_id' => $user->getState('uid'), 'game_id' => $game_id));
            if(!$user_role){
                $this->redirect($this->createUrl('cabinet/game_access_denied'));
            } else{
                $user->setState('game_role', $user_role->role_id);
            }
        }
        $this->user_model = Users::model()->with('person')->findByPk($user->getState('uid'));
        $this->game_model = Games::model()
            ->with('master_user', 'players_users')
            ->findByPk($user->getState('game_role'));

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $this->actionTribe();
    }

    public function actionGM()
    {
        $players = Games::model()->players_users;
        $this->render('gm', array('players' => $players));
    }

    /**
     * Отображает страницу редактора карт
     */
    public function actionMap_redactor()
    {
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        $ClientScript->registerScriptFile($this->assetsBase.'/main/js/jquery.json-2.4.js');
        $ClientScript->registerScriptFile($this->module->assetsBase.'/js/map_redactor.js');

        $game_id = Yii::app()->request->cookies['game_id']->value;
        $turn_id = 0;
        $map = new P13Map($game_id, $turn_id);
        if(!$map->exists() && isset($_POST['create_map'])){
            $map->createBlankMap(
                htmlspecialchars($_POST['map_width']),
                htmlspecialchars($_POST['map_height'])
            );
        }
        $map_info = $map->getMapInfo();
        $map_object_types = $map::getObjectTypesList();

        $this->render('map_redactor', array(
            'map_info' => $map_info,
            'map_object_types' => $map_object_types,
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionTribe()
    {
        $this->render('index', array(
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionTech()
    {
        $this->render('index', array(
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionRequest()
    {
        $this->render('index', array(
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionMap()
    {
        $this->render('index', array(
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionStatistic()
    {
        $this->render('index', array(
            'user_model' => $this->user_model,
            'game_model' => $this->game_model,
        ));
    }

    public function actionCreate_default_map()
    {
        $game_id = Yii::app()->request->cookies['game_id']->value;
        $turn_id = 0;

        $map = new P13Map($game_id, $turn_id);

        $map->createDefaultMap();

        $this->redirect($this->createUrl("game/map_redactor"));
    }

    /**
     * (AJAX) Возвращает массив с полной информацией о карте
     */
    public function actionGetFullMapInfo()
    {
        $game_id = htmlspecialchars($_POST['game_id']);
        $turn = 0;
        $map = new P13Map($game_id, $turn);
        $map_array = $map->getFullMapArray();

        echo json_encode($map_array);
    }

    /**
     * (AJAX) Возвращает массив с информацией о графическом отображении объектов карты
     */
    public function actionGetMapObjectGFXs()
    {
        $game_id = Yii::app()->request->cookies['game_id'];
        $turn = 0;
        $map = new P13Map($game_id, $turn);
        $object_type_id = htmlspecialchars($_POST['map_object_type']);

        echo json_encode($map->getObjectTypeInfo($object_type_id));
    }

    /**
     * (AJAX) Сохраняет карту
     */
    public function actionSaveMap()
    {
        $game_id = htmlspecialchars($_POST['game_id']);
        $turn = 0;
        $map_db = new P13Map($game_id, $turn);
        $map_data = json_decode($_POST['map_data']);

        echo $map_db->saveMap($map_data);
    }
}