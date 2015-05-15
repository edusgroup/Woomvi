<?php

namespace Site\Common\Classes;

use Flame\Traits\Session;
use Site\Route\User\Dao\UserDao;

/**
 * Class User
 * @package Site\Common\Classes
 * @method float getSum() Возвращает количество денег на счету пользователя
 */
class User extends \Flame\Abstracts\User
{
    use Session;

    protected $_sum_;

    /**
     * @param UserDao $userDao
     */
    public function init($userDao)
    {
        $userId = $this->getSession(self::USER_SESSION_ID);
        if (!$userId) {
            return;
        }

        $this->isAuth = true;
        $userData = $userDao->getUserData($userId);
        foreach (['id', 'sum', 'email', 'login'] as $name) {
            $this->{'_' . $name . '_'} = $userData[$name];
        }
    }
} 