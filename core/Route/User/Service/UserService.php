<?php

namespace Site\Route\User\Service;


class UserService
{
    const AJAX_STATUS_USER_NOT_AUTH = 'user-not-auth';

    /** ����� ����� ������ ������������. 12 ���. */
    const COOKIE_AUTH_LIFE = 378432000; // 12 * 365 * 24 * 60 * 60;

    private $userDao;

    /**
     * @param \Site\Route\User\Dao\UserDao $courseDao
     */
    public function __construct($userDao)
    {
        $this->userDao = $userDao;
    }

    /**
     * ��������� ���� �� ������������������ ������������ � �������
     * @param $email Email ������������
     * @param $pwd ������ ������������
     * @return int|null ���������� ID ������������ ��� ������ ������
     */
    public function auth($email, $pwd)
    {
        $userData = $this->userDao->auth($email, $pwd);
        if (!$userData) {
            return null;
        }

        return $userData['id'];
    }

}
