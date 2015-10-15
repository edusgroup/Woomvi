<?php

namespace Site\Route\Admin\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Admin\Service\AdminService;
use Site\Route\Teacher\Service\TeacherService;

class IndexController extends BaseController
{
    public function usersAction($path)
    {
        /** @var AdminService $adminService */
        $adminService = $this->fabric('admin.service');

        $usersList = $adminService->getUsersInfo();
        var_dump($usersList);

        return new Html('route/admin/users.twig', [], $this);
    }
}
