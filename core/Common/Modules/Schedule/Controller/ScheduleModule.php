<?php

namespace Site\Common\Modules\Schedule\Controller;

use Flame\Abstracts\Module;
use \Site\Common\Classes\User as UserModel;
use Site\Common\Modules\Schedule\Model\ScheduleList;
use Site\Common\Modules\Schedule\Service\ScheduleService;

class ScheduleModule extends Module
{
    const MODULE_NAME = 'user.schedule';

    public function make(UserModel $user)
    {
        $params = [];

        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->getController()->fabric('module.schedule.service');
        $params['scheduleList'] = $scheduleService->getScheduleList(
            $user->getInnerId(),
            $user->getScheduleDay()
        );

        $params['isOverdue'] = $user->isOverdue();

        $tplName = $user->isAuth() ? 'schedule.twig' : 'schedule-noauth.twig';
        return $this->getResponse('module/schedule/' . $tplName, $params);
    }

    public function setTaskStatus($userInnerId, $taskId, $taskStatus)
    {
        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->getController()->fabric('module.schedule.service');

        $scheduleService->setTaskStatus($userInnerId, $taskId, $taskStatus);
    }
}
