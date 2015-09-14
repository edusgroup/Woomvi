<?php

namespace Site\Route\User\Service;

use Site\Common\Classes\User;
use Site\Route\User\Dao\UserDao;

/**
 * Сервис пользователя
 *
 * @package Site\Route\User\Service
 */
class UserService
{
    const AJAX_STATUS_USER_NOT_AUTH = 'user-not-auth';

    /** Пользователь с таким Email Существует */
    const REGISTRATION_STATUS_EMAIL_EXISTS = 'email-exists';
    /** Пользователь удачно зарегистрирован */
    const REGISTRATION_STATUS_OK = 'ok';

    /** Время жизни сессии пользовател. 12 лет. */
    const COOKIE_AUTH_LIFE = 378432000; // 12 * 365 * 24 * 60 * 60;

    /** @var UserDao Dao для пользователя */
    private $userDao;

    /**
     * Консутруктор
     *
     * @param UserDao $userDao
     */
    public function __construct(UserDao $userDao)
    {
        $this->userDao = $userDao;
    }

    public function checkConfirmKey($confirmKey)
    {
        $userData = $this->userDao->checkConfirmKey($confirmKey);
        if (!$userData) {
            return null;
        }

        return $userData['id'];
    }

    /**
     * Проверяет есть ли зарегистрированный пользователь в системе
     *
     * @param string $email Email пользователя
     * @param string $pwd пароль пользователя
     *
     * @return int|null возвращает ID пользователь или пустую строку
     */
    public function auth($email, $pwd)
    {
        $userData = $this->userDao->auth($email, $pwd);
        if (!$userData) {
            return null;
        }

        return $userData['id'];
    }

    /**
     * @param string $oauthKey
     * @param string $oauthUId
     * @param string $userName
     * @param string $email
     * @param array $customParam
     */
    public function oauthRegistrUser($oauthKey, $oauthUId, $userName, $email, $customParam = [])
    {
        // Проверяем, а может уже есть пользователь в таблице с OAuth регистрациями
        $userOAuthInfo = $this->userDao->getUserOuterIdByOAuthId($oauthKey, $oauthUId);
        if ($userOAuthInfo) {
            $userDb = $this->userDao->getUserByInnerId($userOAuthInfo['_id']);
            return $userDb['id'];
        }

        // Если пользователь найден и у него почта подтверждена, то надо просто привязать данные oauth
        $userDb = $this->userDao->getUserByEmail($email);
        if ($userDb && $userDb['emailConfirm']) {
            $this->userDao->updateOAuthData($userDb['_id'], $oauthKey, $oauthUId, $email, $customParam);
            return $userDb['id'];
        }

        // @todo Написать поиск email в списке user-oauth записей

        // Создём нового пользователя
        $pwd = md5(uniqid());
        $emailForReg = $email ?: '';
        $userDb = $this->userDao->registration($emailForReg, $pwd, $userName, '', $oauthUId);
        $userDbInnerId = $userDb['_id'];
        $userDbOuterId = md5($userDbInnerId);
        $this->userDao->setOutId($userDbInnerId, $userDbOuterId);

        // Создаём запись о oauth
        $this->userDao->updateOAuthData($userDbInnerId, $oauthKey, $oauthUId, $email, $customParam);

        return $userDbOuterId;
    }

    /**
     * Регистрирует пользователя
     *
     * @param string $email Email пользователя
     * @param string $pwd Пароль пользователя
     * @param string $userName Имя пользователя
     * @param string $confirmKey Ключ подтвердения
     *
     * @return string Результат регистрации. @see self::REGISTRATION_STATUS_*
     */
    public function registration($email, $pwd, $userName, $confirmKey)
    {
        $user = $this->userDao->getUserByEmail($email);
        if ($user) {
            return self::REGISTRATION_STATUS_EMAIL_EXISTS;
        }

        $data = $this->userDao->registration($email, $pwd, $userName, $confirmKey);
        $outerId = md5($data['_id']);
        $this->userDao->setOutId($data['_id'], $outerId);

        return $outerId;
    }

    /**
     * @param string $oauthKey
     * @param \MongoId $userDbInnerId
     * @param string $oauthUId
     * @param string $email
     * @param array $customParam
     */
    public function updateOAuth($oauthKey, $userDbInnerId, $oauthUId, $email, $customParam)
    {
        $userOAuthInfo = $this->userDao->getUserOuterIdByOAuthId($oauthKey, $oauthUId);
        if ($userOAuthInfo) {
            return;
        }
        // Создаём запись о oauth
        $this->userDao->updateOAuthData($userDbInnerId, $oauthKey, $oauthUId, $email, $customParam);
    }
}
