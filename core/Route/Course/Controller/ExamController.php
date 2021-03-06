<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class ExamController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $itemName Название группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::EXAM, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        //$vars['videoData'] = $materialService->getVideoData($itemName);
        //$this->ifNullInvokeError4xx($vars['videoData'], 'Video ' . htmlspecialchars($itemName) . ' not found');

        $vars = [];

        return new Html('route/course/exam/item.twig', $vars, $this);
    }
}
