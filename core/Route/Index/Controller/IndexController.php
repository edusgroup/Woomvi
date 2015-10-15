<?php

namespace Site\Route\Index\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use Site\Common\Modules\Schedule\Controller\ScheduleModule;

class IndexController extends \Site\Common\Controller\BaseController
{
    public function indexAction()
    {
        /** @var ScheduleModule $scheduleModule */
        $scheduleModule = $this->fabric('module.schedule');
        $this->dbus->setReponse('user.schedule', $scheduleModule->make($this->user));

        return new Html('route/index/content.twig', [], $this);
    }

    public function testAction()
    {

    }

    public function errorAction()
    {
        $request = new RequestHttp();
        $num = $request->getVar('n');
        return new Html('global/error-page.twig', ['errorNum' => $num], $this);
    }
}
