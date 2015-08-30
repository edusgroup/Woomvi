<?php

namespace Site\Common\Classes;

use Flame\Traits\Session;
use Site\Route\User\Dao\UserDao;

/**
 * Class User
 * @package Site\Common\Classes
 * @method float getSum() Возвращает количество денег на счету пользователя
 * @method string getOAuthId()
 */
class User extends \Flame\Abstracts\User
{
    use Session;

    protected $sum_;

    /** @var int Выбранный уровень сложности */
    protected $lvlCompl_ = 1;
    protected $courseOpen_ = 1;
    protected $oauthId_ = 1;
    protected $innerId = null;
    protected $emailConfirm = false;

    /**
     * @param UserDao $userDao
     */
    public function init($userDao)
    {
        $userId = $this->getSession(self::USER_SESSION_ID);
        if (!$userId) {
            return;
        }

        $userData = $userDao->getUserData($userId);
        if (!$userData) {
            return;
        }

        $this->isAuth = true;
        foreach (['id', 'sum', 'email', 'lvlCompl', 'courseOpen', 'emailConfirm'] as $name) {
            $this->{$name . '_'} = $userData[$name];
        }

        $this->innerId = $userData['_id'];
    }

    public function isEmailConfirm()
    {
        return $this->emailConfirm;
    }

    public function getLevelOfComplexity()
    {
        return $this->lvlCompl_;
    }

    public function getCourseLevelOpened()
    {
        return $this->courseOpen_;
    }

    public function getInnerId()
    {
        return $this->innerId;
    }
}
