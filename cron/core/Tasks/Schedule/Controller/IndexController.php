<?php

namespace Cron\Tasks\Schedule\Controller;

use Cron\Common\BaseCronController;
use Cron\Tasks\Schedule\Service\ScheduleService;

class IndexController extends BaseCronController
{
    public function indexAction()
    {
        /** @var ScheduleService $scheduleService */
        $scheduleService = $this->fabric('schedule.service');

        $scheduleService->updateScheduleTime();
    }
}
