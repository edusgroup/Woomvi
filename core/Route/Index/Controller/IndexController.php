<?php

namespace Site\Route\Index\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;

class IndexController extends \Site\Common\Controller\BaseController
{
    public function indexAction()
    {
        return new Html('route/index/content.twig', [], $this);
    }

    public function testAction()
    {

    }
}

