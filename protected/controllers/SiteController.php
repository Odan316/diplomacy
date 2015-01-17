<?php
/**
 * Class SiteController Базовый контроллер, испольщуется для логин-логаута, регистрации, отображения ошибок
 */
class SiteController extends DiploController
{
    public function init()
    {
        /** @var $ClientScript CClientScript */
        $ClientScript = Yii::app()->clientScript;
        $ClientScript->registerCssFile($this->assetsBase.'/main/css/styles.css');
        parent::init();
    }

    public function beforeAction($action)
    {
        Yii::app()->user->setState('game_role', 0);
        return parent::beforeAction($action);
    }

    /** Дефолтное действие - залогинивание */
	public function actionIndex()
	{
        $this->actionLogin();
	}

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new Users;
        // collect user input data
        if(isset($_POST['Users']))
        {
            $model->setScenario('login');
            $model->setAttributes($_POST['Users']);
            // validate user input and redirect to the previous page if valid
            if($model->authenticate()){
                $this->redirect($this->createUrl('/cabinet'));
            }
        }
        // display the login form
        $this->render('index', array('model' => $model));
    }

    /**
     * Регистрация нового пользователя
     */
    public function actionRegistration()
    {
        $users_model = new Users();
        $persons_model = new Persons();
        $registration_result = false;

        if(!empty($_POST)){
            if($_POST['Users']['password'] === $_POST['Users']['repeat_password']){
                $users_model->setAttributes($_POST['Users']);
                /** @var $repeated_user Users */
                $repeated_user = Users::model()->findByAttributes(array('login' => $users_model->login));
                if(!$repeated_user){
                    $users_model->setScenario('registration');
                    if($users_model->save()){
                        $persons_model->setAttributes($_POST['Persons']);
                        $persons_model->user_id = $users_model->id;
                        if($persons_model->save()){
                            $users_model->setScenario('login');
                            $users_model->password = $_POST['Users']['password'];
                            $users_model->authenticate();
                            $registration_result = true;
                        }
                    }
                }
                else{
                    $users_model->addError('login', 'Такой логин уже зарегистрирован!');
                }
            }
            else{
                $users_model->addError('repeat_password', 'Пароли не совпадают!');
            }
        }
        $this->render(
            'registration',
            array(
                'users_model' => $users_model,
                'persons_model' => $persons_model,
                'registration_result' => $registration_result
            )
        );
    }


    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        /** @var $User CWebUser */
        $User = Yii::app()->user;
        $User->logout();
        $this->redirect($this->createUrl('site/login'));
    }

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
}