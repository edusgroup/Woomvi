<?php
namespace Cron\Tasks\Schedule\Service;

use Cron\Common\Model\User;
use Cron\Tasks\Schedule\Dao\ScheduleDao;

class ScheduleService
{
    /** @var ScheduleDao Dao для пользователя */
    private $scheduleDao;

    /**
     * Консутруктор
     *
     * @param ScheduleDao $scheduleDao
     */
    public function __construct(ScheduleDao $scheduleDao)
    {
        $this->scheduleDao = $scheduleDao;
    }

    public function updateScheduleTime()
    {
        $userCursor = $this->scheduleDao->getUser();
        foreach ($userCursor as $user) {
            $user = new User($user);

            // Если пользователь не подтвердил email
            if (!$user->isEmailConfirm()) {
                continue;
            }

            $userInnerId = $user->getInnerId();
            $taskList = $this->scheduleDao->getTaskOfSchedule($userInnerId);
            if (!$taskList) {
                continue;
            }

            $isDayDone = true;
            foreach ($taskList['list'] as $task) {
                if (!$task) {
                    $isDayDone = false;
                    break;
                }
            }

            if ($isDayDone) {
                $this->scheduleDao->setNextScheduleDay($userInnerId);
                $this->scheduleDao->clearUserScheduleList($userInnerId);
            } elseif (!$user->getOverdue()) {
                $this->scheduleDao->setOverDue(time(), $userInnerId);
            }

            unset($userInnerId, $taskList);
        }
    }
}
