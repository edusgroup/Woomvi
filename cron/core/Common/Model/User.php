<?php

namespace Cron\Common\Model;

use Flame\Abstracts\Model\Read;

/**
 * @method string getConfirmKey() Ключ подтверждения
 * @method string getEmail() Email пользователя
 * @method string getId() Внешний ID пользователя
 * @method integer getLvlCompl() Выбранный уровень сложности
 * @method string getName() Имя пользователя
 * @method string getPwd() Пароль пользователя
 * @method float getSum() Сумма на балансе
 * @method integer getOverdue() Дата в unix формате, если пользователь просрочил сдачу или ноль, если всё норм
 */
class User extends Read
{
    /**
     * Подтверждён ли email пользователя
     *
     * @return boolean Флаг подтверждения
     */
    public function isEmailConfirm()
    {
        return $this->list['emailConfirm'];
    }
}
