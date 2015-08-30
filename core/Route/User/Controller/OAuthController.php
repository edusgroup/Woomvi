<?php

namespace Site\Route\User\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;
use Flame\Classes\Http\Response\Json;


use Flame\Classes\OAuth\OAuthBase\Mailru\OAuthMailru;
use Flame\Classes\OAuth\OAuthBase\Mailru\TokenCodeResponse as TokenCodeResponseMailru;
use Flame\Classes\OAuth\OAuthBase\Vk\TokenCodeResponse as TokenCodeResponseVk;
use Flame\Classes\OAuth\OAuthBase\Yandex\OAuthYandex;
use Flame\Classes\OAuth\OAuthBase\Yandex\TokenCodeResponse as TokenCodeResponseYandex;
use Flame\Classes\OAuth\OAuthBase\OAuthBase;
use Flame\Classes\OAuth\OAuthBase\Vk\OAuthVk;
use Flame\Classes\RequestHttp;
use Flame\Classes\Social\Mailru\Mailru;
use Flame\Classes\Social\Mailru\UserInfo;
use Flame\Classes\Social\Vk\Vk;
use Flame\Classes\Social\Vk\VkUser;
use Flame\Classes\Social\Yandex\Yandex;
use Site\Common\Controller\BaseController;
use Site\Route\User\Service\UserService;

use \Flame\Traits\Session;

use Flame\Abstracts\User;

class OAuthController extends BaseController
{
    use Session;

    const OAUTH_SESSION_NAME = 'OAuthSessionId';

    public function mailruAction($path, $oauthId)
    {
        $request = new RequestHttp();
        $code = $request->get('code');

        // @todo сделать обработку запрета oauth

        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            $urlCourse = $this->getRoutePath('error-page');
            return $this->redirectToUrl($urlCourse . '?n=1');
        }

        /** @var OAuthBase $oauth */
        $oauth = $this->fabric('oauth2.mailru');
        /** @var TokenCodeResponseMailru $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, 'http://wumvi.com' . $path);
        if (!$tokenResponse) {
            $urlCourse = $this->getRoutePath('error-page');
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var Mailru $mailru */
        $mailru = $this->fabric('social.mailru');
        $mailruUser = $mailru->getUserInfo($tokenResponse);
        if (!isset($mailruUser[0])) {
            $urlCourse = $this->getRoutePath('error-page');
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        // @debug For debug
        /**$obj = new \stdClass();
        $obj->email = 'sdfsdf@sdf.ru';
        $obj->uid = '111222';
        $obj->first_name = 'Виталий';
        $mailruUser[0] = new UserInfo($obj);*/
        // -------------------------------------------------------------

        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        /** @var UserInfo $mailruUserOne */
        $mailruUserOne = $mailruUser[0];
        unset($mailruUser);

        $customOAuthParamUpdate = [
            'refreshToken' => $tokenResponse->refreshToken
        ];

        if ($this->user->isAuth()) {
            $userService->updateOAuth(
                OAuthMailru::USER_DB_OAUTH_KEY,
                $this->user->getInnerId(),
                $mailruUserOne->id,
                $mailruUserOne->email,
                $customOAuthParamUpdate
            );
            // @todo Сделать поддержку параметра _from для реридекта
            return $this->redirectToUrl('/');
        }

        $userDbId = $userService->oauthRegistrUser(
            OAuthMailru::USER_DB_OAUTH_KEY,
            $mailruUserOne->id,
            $mailruUserOne->name,
            $mailruUserOne->email,
            $customOAuthParamUpdate
        );

        $this->setSession(User::USER_SESSION_ID, $userDbId);

        // @todo Сделать поддержку параметра _from для реридекта
        return $this->redirectToUrl('/');
    }

    public function vkAction($path, $oauthId)
    {
        $request = new RequestHttp();
        $error = $request->get('error');
        if ($error) {
            // http://wumvi.com/login/oauth20/vk/805eb1c3a01f57996c1885647dc45dc6/?error=access_denied&error_reason=user_denied&error_description=User+denied+your+request
            // @todo redirect
        }

        $code = $request->get('code');
        $urlCourse = $this->getRoutePath('error-page');

        // @todo сделать обработку запрета oauth

        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            return $this->redirectToUrl($urlCourse . '?n=1');
        }

        /** @var OAuthVk $oauth */
        $oauth = $this->fabric('oauth2.vk');
        /** @var TokenCodeResponseVk $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, 'http://wumvi.com' . $path);

        if (!$tokenResponse) {
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        $customOAuthParamUpdate = [
            'accessToken' => $tokenResponse->accessToken
        ];

        if ($this->user->isAuth()) {
            $userService->updateOAuth(
                OAuthVk::USER_DB_OAUTH_KEY,
                $this->user->getInnerId(),
                $tokenResponse->userId,
                $tokenResponse->email,
                $customOAuthParamUpdate
            );
            // @todo Сделать поддержку параметра _from для реридекта
            return $this->redirectToUrl('/');
        }

        /** @var Vk $socialVk */
        $socialVk = $this->fabric('social.vk');
        $vkUsersList = $socialVk->getUserInfo($tokenResponse->userId, $tokenResponse->accessToken);
        if (!isset($vkUsersList[0])) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        /** @var VkUser $vkUser */
        $vkUser = $vkUsersList[0];
        unset($vkUsersList);

        $userDbId = $userService->oauthRegistrUser(
            OAuthVk::USER_DB_OAUTH_KEY,
            $tokenResponse->userId,
            $vkUser->firstName,
            $tokenResponse->email,
            $customOAuthParamUpdate
        );

        $this->setSession(User::USER_SESSION_ID, $userDbId);

        // @todo Сделать поддержку параметра _from для реридекта
        return $this->redirectToUrl('/');
    }

    public function yandexAction()
    {
        $request = new RequestHttp();
        $error = $request->get('error');
        if ($error) {
            // http://wumvi.com/login/oauth20/vk/805eb1c3a01f57996c1885647dc45dc6/?error=access_denied&error_reason=user_denied&error_description=User+denied+your+request
            // @todo redirect
        }

        $code = $request->get('code');
        $urlCourse = $this->getRoutePath('error-page');

        /** @var OAuthVk $oauth */
        $oauth = $this->fabric('oauth2.yandex');
        /** @var TokenCodeResponseYandex $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, '');

        if (!$tokenResponse) {
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var Yandex $yandexSocial */
        $yandexSocial = $this->fabric('social.yandex');
        $yaUser = $yandexSocial->getUserInfo($tokenResponse->accessToken);
        if (!$yaUser) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        /** @var UserService $userService */
        $userService = $this->fabric('user.service');
        $customOAuthParamUpdate = [];

        if ($this->user->isAuth()) {
            $userService->updateOAuth(
                OAuthYandex::USER_DB_OAUTH_KEY,
                $this->user->getInnerId(),
                $yaUser->id,
                $yaUser->email,
                $customOAuthParamUpdate
            );
            // @todo Сделать поддержку параметра _from для реридекта
            return $this->redirectToUrl('/');
        }

        $userDbId = $userService->oauthRegistrUser(
            OAuthYandex::USER_DB_OAUTH_KEY,
            $yaUser->id,
            $yaUser->firstName,
            $yaUser->email,
            $customOAuthParamUpdate
        );

        $this->setSession(User::USER_SESSION_ID, $userDbId);
        // @todo Сделать поддержку параметра _from для реридекта
        return $this->redirectToUrl('/');
    }
}
