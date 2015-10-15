<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Common\Modules\WordSpeed\Controller\WordSpeedModule;

class WordSpeedController extends BaseController
{
    public function indexAction($path, $wordItemName)
    {
        $params = [];

        /** @var WordSpeedModule $wordspeedModule */
        $wordspeedModule = $this->fabric('module.wordspeed');
        $this->dbus->setReponse('wordspeed.mainbox', $wordspeedModule->getBlock($this->user, $wordItemName));

        return new Html('route/course/wordspeed/content.twig', $params, $this);
    }
}
