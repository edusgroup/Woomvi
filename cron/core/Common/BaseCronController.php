<?php

namespace Cron\Common;

use Flame\Abstracts\BaseController;

class BaseCronController extends BaseController
{
    public function preInitCommon($methodName, $matches)
    {
        // TODO: Implement preInitCommon() method.
    }

    public function preRenderCommon(\Twig_Environment $twig, &$tplName, &$varible)
    {
        // TODO: Implement preRenderCommon() method.
    }

    protected function preCallAction($methodName)
    {
        // TODO: Implement preCallAction() method.
    }
}
