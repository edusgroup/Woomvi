<?php

namespace Cron\Tasks\Schedule\Dao;

use Flame\Abstracts\Db\Dao;

/**
 * Дао по расписаниям
 */
class ScheduleDao extends Dao
{
    /**
     * Получаем курсор по всем пользователям
     *
     * @return \MongoCursor Mongo курсор
     */
    public function getUser()
    {
        return $this->driver->table('users')->queryAll();
    }


    /**
     * Получаем список выполненынй заданий по пользователю
     *
     * @param \MongoId $userInnerId Внутренний Id пользователя
     * @return array
     */
    public function getTaskOfSchedule($userInnerId)
    {
        return $this->driver->table('userScheduleList')->selectFirst(
            ['list' => 1, '_id' => 0],
            [
                '_id' => $userInnerId
            ]
        );
    }

    /**
     * Устанавливаем время просрочки дня для заданий
     *
     * @param integer $time Дата просрочки
     * @param \MongoId $userInnerId Внутренний Id пользователя
     */
    public function setOverDue($time, $userInnerId)
    {
        $this->driver->table('users')->update(
            ['$set' => ['overdue' => $time]],
            ['_id' => $userInnerId]
        );
    }

    /**
     * Устанавливаем следующий день в расписании
     *
     * @param \MongoId $userInnerId Внутренний Id пользователя
     */
    public function setNextScheduleDay($userInnerId)
    {
        $this->driver->table('users')->update(
            ['$inc' => ['scheduleDay' => 1], '$set' => ['overdue' => 0]],
            ['_id' => $userInnerId]
        );
    }

    /**
     * Устанавливаем следующий день в расписании
     *
     * @param \MongoId $userInnerId Внутренний Id пользователя
     */
    public function clearUserScheduleList($userInnerId)
    {
        $this->driver->table('userScheduleList')->update(
            ['$set' => ['list' => new \stdClass()]],
            ['_id' => $userInnerId]
        );
    }
}
