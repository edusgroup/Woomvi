<?php

namespace Site\Route\User\Dao;

use Flame\Abstracts\Db\Dao;

class UserDao extends Dao
{
    const USER_TABLE = 'users';

    public function auth($email, $pwd)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst(['id'], ['email' => $email, 'pwd' => $pwd]);
    }

    public function getUserData($userId)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst([], ['id' => $userId]);
    }
}