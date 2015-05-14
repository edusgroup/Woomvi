<?php

namespace Site\Common\Traits;

trait User
{
    /** @var \Site\Common\Classes\User $user */
    private $user;

    public function getUser($userDao)
    {
        if ($this->user) {
            return $this->user;
        }

        $this->user = new \Site\Common\Classes\User();
        $this->user->init($userDao);
        return $this->user;
    }
}
