<?php

namespace Site\Route\User\Service;

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

    private $userDao;

    /**
     * Консутруктор
     *
     * @param UserDao $userDao
     */
    public function __construct($userDao)
    {
        $this->userDao = $userDao;
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
     * Регистрирует пользователя
     *
     * @param string $email Email пользователя
     * @param string $pwd Пароль пользователя
     * @param string $userName Имя пользователя
     *
     * @return string Результат регистрации. @see self::REGISTRATION_STATUS_*
     */
    public function registration($email, $pwd, $userName)
    {
        $user = $this->userDao->getUserByEmail($email);
        if ($user) {
            return self::REGISTRATION_STATUS_EMAIL_EXISTS;
        }

        $data = $this->userDao->registration($email, $pwd, $userName);
        $outerId = md5($data['_id']);
        $this->userDao->setOutId($data['_id'], $outerId);

        return $outerId;
    }
}
