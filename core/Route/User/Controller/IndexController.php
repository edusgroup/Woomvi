<?php

namespace Site\Route\User\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;
use Flame\Classes\Http\Response\Json;

use Flame\Classes\RequestHttp;
use Site\Common\Controller\BaseController;
use Site\Route\User\Service\UserService;

use \Flame\Traits\Session;

use Flame\Abstracts\User;

class IndexController extends BaseController
{
    use Session;

    public function authAction()
    {
        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        $request = new RequestHttp();

        $email = 'www.dft@mail.ru';//$request->post('login');
        $pwd = 'pwd';//$request->post('pwd');

        $userId = $userService->auth($email, $pwd);
        if ($userId) {
            $this->setSession(User::USER_SESSION_ID, $userId);

            return new Json(['id' => $userId]);
        }

        return new Json('', Json::STATUS_ERROR, UserService::AJAX_STATUS_USER_NOT_AUTH);
    }

    public function exitAction()
    {
        $request = new RequestHttp();
        /*if (!$request->isPost()) {
            return new Json('', Json::STATUS_ERROR, 'It\' not post request');
        }*/


        $this->removeSession(User::USER_SESSION_ID);

        echo '<br/>userExit';
    }

    public function loginAction()
    {
        $request = new RequestHttp();
        $fromUrl = $request->get('_from');

        if (substr($fromUrl, 0, 4) == 'http' && !preg_match('#^https?://wumvi\.(com|lo|ru)/#si', $fromUrl)) {
            $fromUrl = '';
        }

        $param['fromUrl'] = $fromUrl;
        unset($fromUrl);

        return new Html('global/user/login.twig', $param, $this);
    }

    public function kabinetAction()
    {
        echo 'Kabinet';
    }

    public function registrationAction()
    {
        $param[''] = '';

        return new Html('global/user/registration.twig', $param, $this);
    }

    public function forgotpwdAction()
    {
        echo 'forgotpwdAction';
    }
}
