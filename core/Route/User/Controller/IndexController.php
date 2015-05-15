<?php

namespace Site\Route\User\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;
use Flame\Classes\Http\Response\Json;

use Flame\Classes\RequestHttp;
use Site\Common\Controller\BaseController;
use Site\Route\User\Service\UserService;

use Flame\Abstracts\User;

class IndexController extends BaseController
{
    use \Flame\Traits\Session;

    public function authAction()
    {
        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        $request = new RequestHttp();

        $email = 'www.dft@mail.ru';//$request->post('email');
        $pwd = 'pwd';//$request->post('pwd');

        $userId = $userService->auth($email, $pwd);
        if ($userId) {
            $this->setSession(User::USER_SESSION_ID, $userId);

            return new Json(['id' => $userId, 'url' => $this->getRoutePath('user.office')]);
        }

        return new Json('', Json::STATUS_ERROR, UserService::AJAX_STATUS_USER_NOT_AUTH);
    }

}

