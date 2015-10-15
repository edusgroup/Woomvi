<?php

namespace Site\Route\Forum\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class IndexController extends BaseController
{
    public function listAction()
    {
        $params = [];

        return new Html('route/forum/list.twig', $params, $this);
    }
}
