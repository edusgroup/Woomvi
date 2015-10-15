<?php

namespace Site\Common\Modules\Schedule\Service;

use Flame\Abstracts\Module;
use Flame\Classes\Model\Collection;
use \Site\Common\Classes\User as UserModel;
use Site\Common\Modules\Schedule\Dao\ScheduleDao;
use Site\Common\Modules\Schedule\Model\ScheduleCollection;
use Site\Common\Modules\Schedule\Model\ScheduleList;

class ScheduleService
{
    private $scheduleDao;

    public function __construct(ScheduleDao $scheduleDao)
    {
        $this->scheduleDao = $scheduleDao;
    }

    /**
     * @param $userInnerId
     * @param $userScheduleDay
     * @return Collection|null
     */
    public function getScheduleList($userInnerId, $userScheduleDay)
    {
        $list = $this->scheduleDao->getScheduleList($userScheduleDay);
        if (!$list) {
            return null;
        }

        $list = iterator_to_array($list);
        $scheduleList = new ScheduleCollection($list, new ScheduleList());
        unset($list);

        $max = 0;
        foreach ($scheduleList as $item) {
            /** @var ScheduleList $item */
            $max = max($item->getList()->length(), $max);
        }

        $scheduleList->setMaxCountElement($max);

        $checkedTastList = $this->scheduleDao->getTaskCheckList($userInnerId);

        if ($checkedTastList) {
            $checkedTastList = array_filter($checkedTastList['list'], function ($isChecked) {
                return $isChecked;
            });
            $scheduleList->setTaskListChecked(array_keys($checkedTastList));
        }

        return $scheduleList;
    }

    public function setTaskStatus($userInnerId, $taskId, $taskStatus)
    {
        $this->scheduleDao->setTaskStatus($userInnerId, $taskId, $taskStatus);
    }
}
