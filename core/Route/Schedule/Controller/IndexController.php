<?php

namespace Site\Route\Schedule\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Common\Modules\Schedule\Controller\ScheduleModule;

class IndexController extends BaseController
{
    public function indexAction()
    {
        /** @var ScheduleModule $scheduleModule */
        $scheduleModule = $this->fabric('module.schedule');
        $this->dbus->setReponse('user.schedule', $scheduleModule->make($this->user));

        return new Html('route/schedule/content.twig', [], $this);
    }

    public function taskStatusAction()
    {
        if (!$this->user->isAuth()) {
            return new Json('user not auth', Json::STATUS_ERROR);
        }

        $request = new RequestHttp();
        $taskId = $request->post('id');
        $taskStatus = $request->postInt('status');

        if (!$taskId) {
            return new Json('id not set', Json::STATUS_ERROR);
        }

        /** @var ScheduleModule $scheduleModule */
        $scheduleModule = $this->fabric('module.schedule');
        $scheduleModule->setTaskStatus($this->user->getInnerId(), $taskId, $taskStatus);

        return new Json('ok');
    }
}
