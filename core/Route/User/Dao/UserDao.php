<?php

namespace Site\Route\User\Dao;

class UserDao
{
    public $driver;

    /**
     * @param \Flame\Abstracts\Db\Driver $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function auth($email, $pwd)
    {
        return $this->driver->table('users')->selectFirst(['id'], ['email' => $email, 'pwd' => $pwd]);
    }
}