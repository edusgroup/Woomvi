<?php

namespace Site\Route\User\Dao;

class UserDao extends \Flame\Abstracts\Db\Dao
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