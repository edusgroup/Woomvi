<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class VideoController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $videoName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['videoData'] = $materialService->getVideoData($videoName);
        $this->ifNullInvokeError4xx($vars['videoData'], 'Video ' . htmlspecialchars($videoName) . ' not found');

        return new Html('route/course/video/item.twig', $vars, $this);
    }
}
