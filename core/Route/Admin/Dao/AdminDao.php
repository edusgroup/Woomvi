<?php

namespace Site\Route\Admin\Dao;

use Flame\Abstracts\Db\Dao;

class AdminDao extends Dao
{
    public function getUsersInfo()
    {
        return $this->driver->table('users')->selectAll([], []);
    }
}
