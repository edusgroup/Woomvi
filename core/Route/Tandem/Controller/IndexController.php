<?php

namespace Site\Route\Tandem\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Common\Modules\Tandem\Controller\TandemModule;

class IndexController extends BaseController
{
    public function listAction()
    {
        $params = [];

        /** @var TandemModule $scheduleModule */
        $scheduleModule = $this->fabric('module.tandem');
        $this->dbus->setReponse('tandem.all-list', $scheduleModule->getList($this->user, 'all-list'));
        $this->dbus->setReponse('tandem.user-calls', $scheduleModule->getList($this->user, 'user-list'));
        $this->dbus->setReponse('tandem.friends-calls', $scheduleModule->getList($this->user, 'friends-list'));

        return new Html('route/tandem/list.twig', $params, $this);
    }
}
