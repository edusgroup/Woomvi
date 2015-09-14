<?php

namespace Site\Route\Pay\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use \Site\Common\Controller\BaseController;

class IndexController extends BaseController
{
    public function listAction()
    {
        $params[''] = '';
        return new Html('global/pay/list-of-pay.twig', $params, $this);
    }
}
