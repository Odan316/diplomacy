<?php
/**
 * Class PlayerController Контроллер для работы в Кабинете Игрока
 *
 */
class CabinetController extends Controller
{
    /**
     * Отображение базовой страницы кабинета
     */
    public function actionIndex($game_id){
        $this->render('index', array(
        ));
    }
    public function actionView($game_id){
        $this->actionIndex($game_id);
    }

}