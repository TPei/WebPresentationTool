<?php
/**
 * @author Thomas Peikert
 */

use db\MongoAdapter;
use db\objects\User;

/**
 * Class LoginManager
 * handles logins
 */
class LoginManager {

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function createUser($username, $password)
    {
        if(!$this->userExist($username))
        {
            $user = new User();
            $user->setUsername($username);
            $user->setPassword($password);
            MongoAdapter::instance()->save($user, User::COLLECTION_NAME);
            return true;
        }

        return false;

    }

    /**
     * @param $username
     * @return bool
     */
    private function userExist($username)
    {
        $query = array(
            'username' => $username
        );

        $cursor = MongoAdapter::instance()->findOne($query, 'users');

        if(empty ($cursor))
            return false;
        else
            return true;
    }


    /**
     * @param $username
     * @param $password
     * @return bool|User
     */
    public function login($username, $password)
    {
        $query = array(
            'username' => $username,
            'password' => $password
        );

        $cursor = MongoAdapter::instance()->findOne($query, 'users');
        if(empty ($cursor))
        {
            return null;
        }
        else
        {
            $user = new User();
            $user->fromDocument($cursor);

            return $user;
        }
    }
}