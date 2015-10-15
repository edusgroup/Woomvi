<?php

namespace Site\Common\Modules\Schedule\Dao;

use Flame\Abstracts\Db\Dao;

class ScheduleDao extends Dao
{
    const TABLE_SCHEDULE_LIST = 'scheduleList';
    const TABLE_USER_SCHEDULE_LIST = 'userScheduleList';

    public function getScheduleList($userScheduleDay)
    {
        return $this->driver
            ->table(self::TABLE_SCHEDULE_LIST)
            ->select([])
            ->where(['_id' => ['$gte' => $userScheduleDay ]])
            ->limit(7)
            ->queryAll();
    }

    public function getTaskCheckList($userInnerId)
    {
        return $this->driver->table(self::TABLE_USER_SCHEDULE_LIST)->selectFirst([], [
            '_id' => $userInnerId
        ]);
    }

    public function setTaskStatus($userInnerId, $taskId, $taskStatus)
    {
        return $this->driver->table(self::TABLE_USER_SCHEDULE_LIST)->update(
            [

                '$set' => ['list.' . $taskId => $taskStatus ? true : false]
            ],
            [
                '_id' => $userInnerId
            ]
        );
    }
}
