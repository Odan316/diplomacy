<?php

/**
 * Class UserIdentity
 *
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    /**
     * @var integer ID авторизованного пользователя
     */
    private $_id;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
        /** @var $record Users */
        $record = Users::model()->find('login=:login', array(':login' => $this->username));

        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;

        else if($record->password !== Users::model()->createHash($this->password, $record->salt))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else{
            $this->_id = $record->id;
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
	}

    /**
     * @return integer ID авторизованного пользователя
     */
    public function getUID(){
        return $this->_id;
    }
}