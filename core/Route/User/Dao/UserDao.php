<?php

namespace Site\Route\User\Dao;

use Flame\Abstracts\Db\Dao;

/**
 * Дао для пользователя
 *
 * @package Site\Route\User\Dao
 */
class UserDao extends Dao
{
    /** Таблица пользователя */
    const USER_TABLE = 'users';

    /**
     * @param string $email
     * @param string $pwd
     * @return array
     */
    public function auth($email, $pwd)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst(['id'], ['email' => $email, 'pwd' => $pwd]);
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getUserData($userId)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst([], ['id' => $userId]);
    }

    /**
     * @param string $email
     * @return array
     */
    public function getUserByEmail($email)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst(['id'], ['email' => $email]);
    }

    /**
     * @param string @email
     * @return array
     */
    public function registration($email, $pwd, $name)
    {
        $insert = [
            'email' => $email,
            'pwd' => $pwd,
            'name' => $name,
            'sum' => 0
        ];
        $this->driver->table(self::USER_TABLE)->insert($insert);

        return $insert;
    }

    public function setOutId($innerId, $outerId)
    {
        $this->driver->table(self::USER_TABLE)->update(
            ['$set' => ['id' => $outerId]],
            ['_id' => $innerId]
        );
    }
}
