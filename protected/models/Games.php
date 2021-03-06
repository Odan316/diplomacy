<?php

/**
 * Class Games
 *
 * @property integer $id
 * @property string $title Название
 * @property string $tag Тэг
 * @property string $module_id Модуль
 * @property string $status_id Статус
 * @property string $create_date Дата создания Y-m-d
 * @property string $start_date Дата начала Y-m-d
 * @property string $end_date Дата окончания Y-m-d
 * @property integer $last_turn Последний ход
 *
 * @property Users $user_relations
 * @property Users $master_user
 * @property Users $players_users
 *
 */
class Games extends CActiveRecord
{
    /**
     * @param string $className
     *
     * @return Games
     */
    public static function model( $className = __CLASS__ )
    {
        return parent::model( $className );
    }

    public function tableName()
    {
        return 'games';
    }

    public function attributeLabels()
    {
        return [
            "id"          => '#',
            'title'       => 'Название',
            'tag'         => 'Тэг',
            'module_id'   => 'Модуль',
            'status_id'   => 'Статус',
            'create_date' => 'Дата создания',
            'start_date'  => 'Дата начала',
            'end_date'    => 'Дата окончания',
            'last_turn'   => 'Ход'
        ];
    }

    public function rules()
    {
        return [
            [ 'title, module_id', 'required' ],
            [ 'status_id, tag, create_date, start_date, end_date', 'safe' ],
        ];
    }

    public function relations()
    {
        return [
            'module'         => [ self::BELONGS_TO, 'Modules', 'module_id' ],
            'user_relations' => [ self::HAS_MANY, 'Users2games', 'game_id' ],
            'master_user'    => [
                self::MANY_MANY,
                'Users',
                'users2games(game_id, user_id)',
                'condition' => "master_user_master_user.role_id = :role_id",
                'params'    => [ ':role_id' => Game_roles::GM_ROLE ]
            ],
            'players_users'  => [
                self::MANY_MANY,
                'Users',
                'users2games(game_id, user_id)',
                'condition' => "players_users_players_users.role_id = :role_id",
                'params'    => [ ':role_id' => Game_roles::PLAYER_ROLE ]
            ],
            'claimers_users' => [
                self::MANY_MANY,
                'Users',
                'users2games(game_id, user_id)',
                'condition' => "claimers_users_claimers_users.role_id = :role_id",
                'params'    => [ ':role_id' => Game_roles::CLAIM_ROLE ]
            ],
        ];
    }

    public function scopes()
    {
        return [ ];
    }

    /**
     * Получение только открытых игр
     * @return Games
     */
    public function open()
    {
        $criteria = $this->getDbCriteria();
        $criteria->addInCondition( 'status_id', [ Game_statuses::OPEN_GAME, Game_statuses::ACTIVE ] );

        return $this;
    }

    /**
     * Условие для получения только игр, относящихся к указанному модулю
     *
     * @param $module_id
     *
     * @return Games
     */
    public function has_module( $module_id )
    {
        $criteria = $this->getDbCriteria();
        $criteria->mergeWith( [
            'condition' => "module_id = :module_id",
            'params'    => [ ':module_id' => $module_id ]
        ] );

        return $this;
    }

    /**
     * Условие для получения только игр, в которых никак не фигурирует указанный юзер
     *
     * @param $user_id
     *
     * @return Games
     */
    public function has_user( $user_id )
    {
        $criteria = $this->getDbCriteria();
        $criteria->addInCondition( 'user_relations.user_id', [ $user_id ] );
        $criteria->mergeWith( [
            'with' => 'user_relations'
        ] );

        return $this;
    }

    /**
     * Условие для получения только игр, в которых никак не фигурирует указанный юзер
     *
     * @param $user_id
     *
     * @return Games
     */
    public function hasNoUser( $user_id )
    {
        $games_with_user = ( new Games() )->has_user( $user_id )->findAll();
        $gamesIds        = CHtml::listData( $games_with_user, 'id', 'id' );

        $criteria = $this->getDbCriteria();
        $criteria->addNotInCondition( 'id', $gamesIds );

        return $this;
    }


    public function beforeSave()
    {
        if ($this->getScenario() == 'new_game') {
            $module                  = Modules::model()->findByPk( $this->module_id );
            $same_module_games_count = Games::model()->has_module( $this->module_id )->count();
            $this->tag               = $module->tag . ( (int) $same_module_games_count + 1 );

            $this->status_id   = Game_statuses::OPEN_GAME;
            $this->create_date = date( 'Y-m-d' );
        }

        return parent::beforeSave();
    }
}

?>
