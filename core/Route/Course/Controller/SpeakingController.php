<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class SpeakingController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $speakingName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['speakingData'] = $materialService->getSpeakingData($speakingName);
        $this->ifNullInvokeError4xx($vars['speakingData'], 'Speaking ' . htmlspecialchars($speakingName) . ' not found');

        return new Html('route/course/speaking/item.twig', $vars, $this);
    }
}
