<?php

namespace Site\Common\Traits;

use \Site\Common\Classes\User as UserModel;

trait User
{
    /** @var UserModel $user */
    protected $user;

    public function getUser($userDao)
    {
        if ($this->user) {
            return $this->user;
        }

        $this->user = new UserModel();
        $this->user->init($userDao);
        return $this->user;
    }
}
