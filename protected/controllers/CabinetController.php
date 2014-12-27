<?php

/**
 * Class CabinetController Контроллер для работы в Кабинете Пользователя
 */
class CabinetController extends Controller
{
    /**
     * TODO: Сделать для функций ГМа проверку на роль ГМа
     */
    public function init()
    {
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        $ClientScript->registerCssFile( $this->assetsBase . '/main/css/styles.css' );
        $ClientScript->registerScriptFile( $this->assetsBase . '/main/js/cabinet.js' );
        parent::init();
    }

    /**
     * @param CAction $action
     *
     * @return bool
     */
    public function beforeAction( $action )
    {
        Yii::app()->user->setState( 'game_role', 0 );
        return parent::beforeAction( $action );
    }

    /**
     * Дефолтное действие - отображение базовой страницы кабинета
     *
     * @param int $game_id ИД игры
     */
    public function actionIndex( $game_id = 0 )
    {
        $this->actionView( $game_id );
    }

    /**
     * Отображение базовой страницы кабинета
     *
     * @param int $game_id
     */
    public function actionView( $game_id = 0 )
    {
        /** @var $user_model Users */
        $user_model = Users::model()->with( 'person' )->findByPk( Yii::app()->user->uid );

        $this->render( 'index', [
            'user_model'     => $user_model,
            'game_info_html' => $this->getGameInfoHTML( $game_id ),
            'modules_list'   => Modules::model()->active()->getSelectList(),
            'master_games'   => $user_model->master_games(),
            'claimed_games'  => $user_model->claimed_games(),
            'player_games'   => $user_model->player_games(),
            'open_games'     => ( new Games() )->hasNoUser( Yii::app()->user->uid )->findAll()
        ] );
    }

    /**
     * (AJAX) Создание новой игры, вызывается из Кабинета пользователя
     */
    public function actionCreateGame()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset( $_POST['game_title'] ) && isset( $_POST['module_id'] )) {
                $model = new Games();
                $model->setScenario( 'new_game' );
                $model->title     = htmlspecialchars( $_POST["game_title"] );
                $model->module_id = htmlspecialchars( $_POST["module_id"] );
                $model->last_turn = 0;
                if ($model->save()) {
                    $relations          = new Users2games();
                    $relations->user_id = Yii::app()->user->uid;
                    $relations->game_id = $model->id;
                    $relations->role_id = Game_roles::GM_ROLE;
                    if ($relations->save()) {
                        /** @var Modules $module */
                        $module = Modules::model()->findByPk( $model->module_id );
                        Yii::import( $module->system_name . '.models.*' );
                        Yii::import( $module->system_name . '.components.*' );
                        ( new Game( $model->id, 0 ) )->createNewGame();
                        $result = [ 'result' => true ];
                    } else {
                        $result = [ 'result' => false ];
                    }
                } else {
                    $result = [ 'result' => false ];
                }
            } else {
                $result = [ 'result' => false ];
            }
            echo json_encode( $result );
        } else {
            $this->redirect( $this->createUrl( '/cabinet' ) );
        }
    }

    /**
     * (AJAX) Подача заявки на игру, вызывается из Кабинета пользователя
     *
     * @param int $game_id ИД игры
     */
    public function actionMake_claim( $game_id )
    {
        $relation_model          = new Users2games();
        $relation_model->game_id = $game_id;
        $relation_model->user_id = Yii::app()->user->uid;
        $relation_model->role_id = Game_roles::CLAIM_ROLE;

        $relation_model->save();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $game_id ] ) );
    }

    /**
     * (AJAX) Одобрение заявки на игру, вызывается из Кабинета пользователя
     *
     * @param int $game_id ИД игры
     * @param int $user_id
     */
    public function actionAccept_claim( $game_id, $user_id )
    {
        /** @var $relation_model Users2games */
        $relation_model          = Users2games::model()->findByAttributes( [
            'game_id' => $game_id,
            'user_id' => $user_id
        ] );
        $relation_model->role_id = Game_roles::PLAYER_ROLE;

        $relation_model->save();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $game_id ] ) );
    }

    /**
     * (AJAX) Отклонение заявки на игру, вызывается из Кабинета пользователя
     *
     * @param int $game_id ИД игры
     * @param int $user_id
     */
    public function actionReject_claim( $game_id, $user_id )
    {
        /** @var $relation_model Users2games */
        $relation_model = Users2games::model()->findByAttributes( [ 'game_id' => $game_id, 'user_id' => $user_id ] );
        $relation_model->delete();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $game_id ] ) );
    }

    /**
     * (AJAX) Старт игры, вызывается из Кабинета пользователя
     *
     * @param int $id ИД игры
     */
    public function actionStart_game( $id )
    {
        /** @var $game_model Games */
        $game_model            = Games::model()->findByPk( $id );
        $game_model->status_id = Game_statuses::ACTIVE;
        $game_model->save();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $id ] ) );
    }

    /**
     * (AJAX) Отмена игры, вызывается из Кабинета пользователя
     *
     * @param int $id ИД игры
     */
    public function actionCancel_game( $id )
    {
        /** @var $game_model Games */
        $game_model            = Games::model()->findByPk( $id );
        $game_model->status_id = Game_statuses::CANCELLED;
        $game_model->save();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $id ] ) );
    }


    /**
     * (AJAX) Завершение игры, вызывается из Кабинета пользователя
     *
     * @param int $id ИД игры
     */
    public function actionEnd_game( $id )
    {
        /** @var $game_model Games */
        $game_model            = Games::model()->findByPk( $id );
        $game_model->status_id = Game_statuses::ENDED;
        $game_model->save();

        $this->redirect( $this->createUrl( 'cabinet/view', [ 'game_id' => $id ] ) );
    }

    /**
     * (AJAX) Получение HTML для вставки в поле "Информация об игре" в Кабинете пользователя
     */
    public function actionGetGameInfo()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset( $_POST['game_id'] )) {
                $game_id = htmlspecialchars( $_POST["game_id"] );

                echo $this->getGameInfoHTML( $game_id );
            }
        } else {
            $this->redirect( $this->createUrl( '/cabinet' ) );
        }
    }

    /**
     * Функция, составляющая HTML для вставки в поле "Информация об игре" в Кабинете пользователя
     *
     * @param int $game_id ИД игры
     *
     * @return string Код HTML для вставки в поле "Информация об игре" в Кабинете пользователя
     */
    private function getGameInfoHTML( $game_id )
    {
        if ( ! empty( $game_id )) {
            /** @var $model Games */
            $model = Games::model()->with( 'module' )->findByPk( $game_id );

            /** @var $user_role Users2games */
            $user_role = Users2games::model()->findByAttributes( [
                'user_id' => Yii::app()->user->uid,
                'game_id' => $game_id
            ] );

            $master_user = $model->master_user;
            /** @var $master_model Users */
            $master_model = Users::model()->with( 'person' )->findByPk( $master_user[0]->id );

            $players_ids = CHtml::listData( $model->players_users, 'id', 'id' );
            /** @var $players_model Users */
            $players_model = Users::model()->id_in( $players_ids )->with( 'person' )->findAll();

            $claimers_ids = CHtml::listData( $model->claimers_users, 'id', 'id' );
            /** @var $claimers_model Users */
            $claimers_model = Users::model()->id_in( $claimers_ids )->with( 'person' )->findAll();

            return $this->renderPartial( 'game_info', [
                'model'          => $model,
                'user_role'      => $user_role,
                'master_model'   => $master_model,
                'players_model'  => $players_model,
                'claimers_model' => $claimers_model,
            ], true );
        } else {
            return '';
        }
    }
}