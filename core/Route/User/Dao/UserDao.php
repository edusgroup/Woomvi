<?php

namespace Site\Route\User\Dao;

use Flame\Abstracts\Db\Dao;
use Site\Common\Classes\User;

/**
 * Дао для пользователя
 *
 * @package Site\Route\User\Dao
 */
class UserDao extends Dao
{
    /** Таблица пользователя */
    const USER_TABLE = 'users';
    const USER_OAUTH_TABLE = 'usersOAuth';

    const EMAIL_FIELD = 'email';
    const PWD_FIELD = 'pwd';

    /**
     * @param string $email
     * @param string $pwd
     * @return array
     */
    public function auth($email, $pwd)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst(
            ['id'],
            [
                self::EMAIL_FIELD => $email,
                self::PWD_FIELD => $pwd,
                'emailConfirm' => true
            ]
        );
    }

    public function checkConfirmKey($confirmKey)
    {
        return $this->driver->table(self::USER_TABLE)->findAndModify(
            [],
            ['$set' => ['emailConfirm' => true, 'confirmKey' => '']],
            ['confirmKey' => $confirmKey, 'emailConfirm' => false]
        );
    }

    /**
     * @param string $userId
     * @return array
     */
    public function getUserData($userId)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst([], ['id' => $userId]);
    }

    /**
     * @param string $oauthKey
     * @param User $user
     * @return array
     */
    public function getOAuthData($oauthKey, $user)
    {
        return $this->driver->table(self::USER_OAUTH_TABLE)->selectFirst(
            [$oauthKey => 1],
            ['id' => $user->getId()]
        );
    }

    /**
     * @param string $userId
     * @param string $oauthKey
     * @param string $oauthUId
     * @param string $email
     * @param array $customParam
     */
    public function updateOAuthData($userId, $oauthKey, $oauthUId, $email, $customParam = [])
    {
        $setParam = ['id' => $oauthUId, 'email' => $email];
        $setParam = array_merge($setParam, $customParam);
        $this->driver->table(self::USER_OAUTH_TABLE)->update(
            ['$set' => [$oauthKey => $setParam]],
            ['_id' => $userId]
        );
    }

    /**
     * @param User $user
     * @param string $email
     */
    public function updateEmail($userId, $email)
    {
        return $this->driver->table(self::USER_TABLE)->update(
            [self::EMAIL_FIELD => $email],
            ['$set' => ['id' => $userId]]
        );
    }

    /**
     * @param string $email
     * @return array
     */
    public function getUserByEmail($email)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst(
            [],
            [self::EMAIL_FIELD => $email, 'emailConfirm' => true]
        );
    }

    /**
     * @param \MongoId $id
     * @return array
     */
    public function getUserByInnerId($id)
    {
        return $this->driver->table(self::USER_TABLE)->selectFirst([], ['_id' => $id]);
    }

    /**
     * @param string $oauthKey
     * @param string $oauthId
     * @return array
     */
    public function getUserOuterIdByOAuthId($oauthKey, $oauthId)
    {
        return $this->driver->table(self::USER_OAUTH_TABLE)->selectFirst(
            [$oauthKey => 1, '_id' => 1],
            [$oauthKey . '.id' => $oauthId]
        );
    }

    /**
     * @param $email
     * @param $pwd
     * @param $name
     * @param $confirmKey
     * @param null $oauthId
     * @return array
     */
    public function registration($email, $pwd, $name, $confirmKey, $oauthId = null)
    {
        $insert = [
            self::EMAIL_FIELD => $email,
            self::PWD_FIELD => $pwd,
            'name' => $name,
            'sum' => 0,
            // 'oauthId' => $oauthId,
            'emailConfirm' => false, // Подтверждён ли email
            'lvlCompl' => 1, // Уровень сложности
            'courseOpen' => 1, // Номер открытого курса,
            'confirmKey' => $confirmKey
        ];
        $this->driver->table(self::USER_TABLE)->insert($insert);

        return $insert;
    }

    public function setOutId($innerId, $outerId)
    {
        $this->driver->table(self::USER_TABLE)->update(
            ['$set' => ['id' => $outerId]],
            ['_id' => $innerId]
        );
    }
}
