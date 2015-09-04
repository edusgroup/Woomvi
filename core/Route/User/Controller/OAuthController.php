<?php

namespace Site\Route\User\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;
use Flame\Classes\Http\Response\Json;


use Flame\Classes\OAuth\OAuthBase\Facebook\OAuthFacebook;
use Flame\Classes\OAuth\OAuthBase\Google\OAuthGoogle;
use Flame\Classes\OAuth\OAuthBase\Mailru\OAuthMailru;
use Flame\Classes\OAuth\OAuthBase\Mailru\TokenCodeResponse as TokenCodeResponseMailru;
use Flame\Classes\OAuth\OAuthBase\Ok\OAuthOk;
use Flame\Classes\OAuth\OAuthBase\Vk\TokenCodeResponse as TokenCodeResponseVk;
use Flame\Classes\OAuth\OAuthBase\Yandex\OAuthYandex;
use Flame\Classes\OAuth\OAuthBase\Yandex\TokenCodeResponse as TokenCodeResponseYandex;
use Flame\Classes\OAuth\OAuthBase\Ok\TokenCodeResponse as TokenCodeResponseOk;
use Flame\Classes\OAuth\OAuthBase\Facebook\TokenCodeResponse as TokenCodeResponseFacebook;
use Flame\Classes\OAuth\OAuthBase\Google\TokenCodeResponse as TokenCodeResponseGoogle;
use Flame\Classes\OAuth\OAuthBase\OAuthBase;
use Flame\Classes\OAuth\OAuthBase\Vk\OAuthVk;
use Flame\Classes\RequestHttp;
use Flame\Classes\Social\Facebook\FbSocial;
use Flame\Classes\Social\Google\GoogleSocial;
use Flame\Classes\Social\Mailru\Mailru;
use Flame\Classes\Social\Mailru\UserInfo;
use Flame\Classes\Social\Ok\OkSocial;
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
         * $obj->email = 'sdfsdf@sdf.ru';
         * $obj->uid = '111222';
         * $obj->first_name = 'Виталий';
         * $mailruUser[0] = new UserInfo($obj);*/
        // -------------------------------------------------------------

        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        /** @var UserInfo $mailruUserOne */
        $mailruUserOne = $mailruUser[0];
        unset($mailruUser);

        $customOAuthParamUpdate = [
            'refreshToken' => $tokenResponse->refreshToken
        ];

        return $this->saveToDb($mailruUserOne, $customOAuthParamUpdate, OAuthMailru::USER_DB_OAUTH_KEY);
    }

    public function vkAction($path, $oauthId)
    {
        $urlCourse = $this->getRoutePath('error-page');
        $request = new RequestHttp();
        $error = $request->get('error');
        if ($error) {
            return $this->redirectToUrl($urlCourse . '?n=10');
        }

        $code = $request->get('code');

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

        $customOAuthParamUpdate = [
            'accessToken' => $tokenResponse->accessToken
        ];

        /** @var Vk $socialVk */
        $socialVk = $this->fabric('social.vk');
        $vkUsersList = $socialVk->getUserInfo($tokenResponse->userId, $tokenResponse->accessToken);
        if (!isset($vkUsersList[0])) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        /** @var VkUser $vkUser */
        $vkUser = $vkUsersList[0];
        unset($vkUsersList);
        $vkUser->setEmail($tokenResponse->email);

        return $this->saveToDb($vkUser, $customOAuthParamUpdate, OAuthVk::USER_DB_OAUTH_KEY);
    }

    public function yandexAction()
    {
        $urlCourse = $this->getRoutePath('error-page');
        $request = new RequestHttp();
        $error = $request->get('error');
        if ($error) {
            return $this->redirectToUrl($urlCourse . '?n=10');
        }

        $oauthId = $request->get('state');
        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            return $this->redirectToUrl($urlCourse . '?n=8');
        }

        $code = $request->get('code');

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

        return $this->saveToDb($yaUser, [], OAuthYandex::USER_DB_OAUTH_KEY);
    }

    /** @todo Вынести в подController */
    private function saveToDb($user, $customOAuthParamUpdate, $userDbOAuthKey)
    {
        /** @var UserService $userService */
        $userService = $this->fabric('user.service');

        if ($this->user->isAuth()) {
            $userService->updateOAuth(
                $userDbOAuthKey,
                $this->user->getInnerId(),
                $user->id,
                $user->email,
                $customOAuthParamUpdate
            );
            // @todo Сделать поддержку параметра _from для реридекта
            return $this->redirectToUrl('/');
        }

        $userDbId = $userService->oauthRegistrUser(
            $userDbOAuthKey,
            $user->id,
            $user->firstName,
            $user->email,
            $customOAuthParamUpdate
        );

        $this->setSession(User::USER_SESSION_ID, $userDbId);
        // @todo Сделать поддержку параметра _from для реридекта
        return $this->redirectToUrl('/');
    }

    public function odnoklassnikiAction($path)
    {
        $urlCourse = $this->getRoutePath('error-page');
        $request = new RequestHttp();
        $error = $request->get('error');
        if ($error) {
            return $this->redirectToUrl($urlCourse . '?n=10');
        }

        $oauthId = $request->get('state');
        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            return $this->redirectToUrl($urlCourse . '?n=8');
        }

        $code = $request->get('code');

        /** @var OAuthVk $oauth */
        $oauth = $this->fabric('oauth2.ok');
        /** @var TokenCodeResponseOk $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, 'http://wumvi.com' . $path);
        if (!$tokenResponse) {
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var OkSocial $okSocial */
        $okSocial = $this->fabric('social.ok');
        $okUser = $okSocial->getUserInfo($tokenResponse->accessToken);
        if (!$okUser) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        return $this->saveToDb($okUser, [], OAuthOk::USER_DB_OAUTH_KEY);
    }

    public function facebookAction($path, $oauthId)
    {
        $request = new RequestHttp();
        $code = $request->get('code');
        $urlCourse = $this->getRoutePath('error-page');

        // @todo сделать обработку запрета oauth

        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            return $this->redirectToUrl($urlCourse . '?n=1');
        }

        /** @var OAuthBase $oauth */
        $oauth = $this->fabric('oauth2.facebook');
        /** @var TokenCodeResponseFacebook $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, 'http://wumvi.com' . $path);
        if (!$tokenResponse) {
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var FbSocial $fbSocial */
        $fbSocial = $this->fabric('social.facebook');
        $fbUser = $fbSocial->getUserInfo($tokenResponse->accessToken);
        if (!$fbUser) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        return $this->saveToDb($fbUser, [], OAuthFacebook::USER_DB_OAUTH_KEY);
    }

    public function googleAction($path)
    {
        // http://wumvi.com/login/oauth20/google/?error=access_denied&state=caf65f86f93677e48a8dab9f54caae09#
        $request = new RequestHttp();
        $code = $request->get('code');
        $urlCourse = $this->getRoutePath('error-page');

        // @todo сделать обработку запрета oauth
        $oauthId = $request->get('state');
        $sessionAuthId = $this->getSession(self::OAUTH_SESSION_NAME);
        if ($sessionAuthId != $oauthId) {
            return $this->redirectToUrl($urlCourse . '?n=1');
        }

        /** @var OAuthBase $oauth */
        $oauth = $this->fabric('oauth2.google');
        /** @var TokenCodeResponseGoogle $tokenResponse */
        $tokenResponse = $oauth->getAuthorizationCode($code, 'http://wumvi.com' . $path);
        if (!$tokenResponse) {
            return $this->redirectToUrl($urlCourse . '?n=2');
        }

        /** @var GoogleSocial $googleSocial */
        $googleSocial = $this->fabric('social.google');
        $googlUser = $googleSocial->getUserInfo($tokenResponse->accessToken);
        if (!$googlUser) {
            return $this->redirectToUrl($urlCourse . '?n=3');
        }

        return $this->saveToDb($googlUser, [], OAuthGoogle::USER_DB_OAUTH_KEY);
    }
}
