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

    public function loginFormAction()
    {
        $request = new RequestHttp();
        $fromUrl = $request->get('_from');

        if ($this->user->isAuth()) {
            $url = $this->getRoutePath('user.kabinet');
            return $this->redirectToUrl($url);
        }

        if (substr($fromUrl, 0, 4) == 'http' && !preg_match('#^https?://wumvi\.(com|lo|ru)/#si', $fromUrl)) {
            $fromUrl = '';
        }

        $params['fromUrl'] = $fromUrl;
        unset($fromUrl);

        $confirmKey = trim($request->get('key'));
        if ($confirmKey) {

        }

        $uniqId = md5(uniqid());
        $this->setSession(OAuthController::OAUTH_SESSION_NAME, $uniqId);
        $params['oauthId'] = $uniqId;

        return new Html('global/user/login.twig', $params, $this);
    }

    public function registrationAjaxAction()
    {
        $request = new RequestHttp();
        if (!$request->isPost()) {
            return new Json('', Json::STATUS_ERROR, 'is not post request');
        }

        $login = $request->post('login');
        $pwd = $request->post('pwd');
        $name = $request->post('name');

        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        $status = $userService->registration($login, $pwd, $name);
        switch($status) {
            case UserService::REGISTRATION_STATUS_EMAIL_EXISTS:
                return new Json('', Json::STATUS_ERROR, 'user-with-email-exists');
        }

        $key = md5($status . time());

        $confirmUrl = 'http://' .
            $request->getHost() .
            $this->getRoutePath('user.login') .
            '?key=' . $key;

        // @todo Сделать оптравку email

        return new Json('', Json::STATUS_SUCCESS);
    }

    public function registrationEmailAction()
    {
        $param[''] = '';
        return new Html('global/user/regEmail.twig', $param, $this);
    }

    public function kabinetAction()
    {
        echo 'Kabinet';
    }

    public function registrationAction()
    {
        $params[''] = '';
        return new Html('global/user/registration.twig', $params, $this);
    }

    public function forgotpwdAction()
    {
        echo 'forgotpwdAction';
    }
}
