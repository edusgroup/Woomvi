<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class VideoController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $videoName Название группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $videoName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['videoData'] = $materialService->getVideoData($videoName);
        $this->ifNullInvokeError4xx($vars['videoData'], 'Video ' . htmlspecialchars($videoName) . ' not found');

        return new Html('route/course/video/item.twig', $vars, $this);
    }
}
