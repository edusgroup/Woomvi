<?php

namespace Site\Route\Admin\Service;

use Flame\Classes\Model\Collection;
use Site\Route\Admin\Dao\AdminDao;
use Site\Route\Admin\Model\User;

class AdminService
{
    private $adminDao;

    /**
     * @param AdminDao $adminDao
     */
    public function __construct(AdminDao $adminDao)
    {
        $this->adminDao = $adminDao;
    }

    public function getUsersInfo()
    {
        $usersList = $this->adminDao->getUsersInfo();
        return new Collection($usersList, new User());
    }
}
