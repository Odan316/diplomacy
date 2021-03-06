<?php

/**
 * Class Users
 *
 * @property integer $id
 * @property string $login
 * @property string $password
 * @property string $salt
 *
 * @property Persons $person
 * @property Games $games
 *
 * @method Games games() Получение игр, в которых числится юзер
 * @method Games master_games() Получение игр, в которых юзер - мастер
 * @method Games claimed_games() Получение игр, в которых юзер подал заявку
 * @method Games player_games() Получение игр, в которых юзер принят игроком
 * @method Games open() Получение открытых игр
 */
class Users extends CActiveRecord implements JsonSerializable
{
    /** @var string Повтор пароля при регистрации */
    public $repeat_password;

    /** @var string Новый пароль - при смене пароля */
    public $password_new;

    /** @var UserIdentity */
    private $_identity;

    const MASTER_ROLE = 1;
    const CLAIMER_ROLE = 2;
    const PLAYER_ROLE = 3;

    /**
     * @param string $className
     *
     * @return Users
     */
    public static function model( $className = __CLASS__ )
    {
        return parent::model( $className );
    }

    public function tableName()
    {
        return 'users';
    }

    public function attributeLabels()
    {
        return [
            "id"              => '#',
            "login"           => 'Логин',
            "password"        => 'Пароль',
            "salt"            => 'Соль',
            "repeat_password" => 'Повторите пароль',
            "password_new"    => 'Новый пароль',
        ];
    }

    public function rules()
    {
        return [
            [ 'login, password', 'required' ],
            [ 'repeat_password, password_new, salt', 'safe' ]
        ];
    }

    public function relations()
    {
        return [
            'person'        => [ self::HAS_ONE, 'Persons', 'user_id' ],
            'games'         => [ self::MANY_MANY, 'Games', 'users2games(user_id, game_id)' ],
            'master_games'  => [
                self::MANY_MANY,
                'Games',
                'users2games(user_id, game_id)',
                'condition' => "role_id = :role_id AND status_id NOT IN (:ended, :cancelled)",
                'params'    => [
                    ':role_id'   => self::MASTER_ROLE,
                    ':ended'     => Game_statuses::ENDED,
                    ':cancelled' => Game_statuses::CANCELLED
                ]
            ],
            'claimed_games' => [
                self::MANY_MANY,
                'Games',
                'users2games(user_id, game_id)',
                'condition' => "role_id = :role_id AND status_id NOT IN (:ended, :cancelled)",
                'params'    => [
                    ':role_id'   => self::CLAIMER_ROLE,
                    ':ended'     => Game_statuses::ENDED,
                    ':cancelled' => Game_statuses::CANCELLED
                ]
            ],
            'player_games'  => [
                self::MANY_MANY,
                'Games',
                'users2games(user_id, game_id)',
                'condition' => "role_id = :role_id AND status_id NOT IN (:ended, :cancelled)",
                'params'    => [
                    ':role_id'   => self::PLAYER_ROLE,
                    ':ended'     => Game_statuses::ENDED,
                    ':cancelled' => Game_statuses::CANCELLED
                ]
            ],
        ];
    }

    public function scopes()
    {
        return [ ];
    }

    /**
     * Условие для поиска моделей по заданным ID
     *
     * @param int[] $ids
     *
     * @return Users
     */
    public function id_in( $ids )
    {
        $criteria = $this->getDbCriteria();
        $criteria->addInCondition( 't.id', $ids );

        return $this;
    }

    public function beforeSave()
    {
        if (in_array( $this->getScenario(), [ 'registration' ] )) {
            $this->salt     = sprintf( '%08x%08x%08x%08x', mt_rand(), mt_rand(), mt_rand(), mt_rand() );
            $this->password = $this->createHash( $this->password, $this->salt );
        } else if (in_array( $this->getScenario(), [ 'change_password' ] )) {
            $this->salt     = sprintf( '%08x%08x%08x%08x', mt_rand(), mt_rand(), mt_rand(), mt_rand() );
            $this->password = $this->createHash( $this->password_new, $this->salt );
        }
        return parent::beforeSave();
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     *
     * @return boolean Результат попытки авторизации
     */
    public function authenticate()
    {
        if ( ! $this->hasErrors()) {
            $this->_identity = new UserIdentity( $this->login, $this->password );
            if ( ! $this->_identity->authenticate()) {
                $this->addError( 'password', 'Incorrect username or password.' );
                return false;
            } else {
                /** @var $user CWebUser */
                $user = Yii::app()->user;
                $this->_identity->setState( 'uid', $this->_identity->getUID() );
                $user->login( $this->_identity, 3600 * 24 * 30 );
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * Функция создания хэша для пароля
     *
     * @param string $password
     * @param string $salt
     *
     * @return string Хэшированный пароль
     */
    public function createHash( $password, $salt )
    {
        return md5( $password . md5( md5( Yii::app()->params->globalsalt . $password ) . md5( $salt . $password ) ) );
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'nickname' => $this->person->nickname
        ];
    }
}

?>
