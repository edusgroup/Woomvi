<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class VideoController extends BaseController
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
        $response = $this->checkRight(CourseService::VIDEO, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $params['videoData'] = $materialService->getVideoData($itemName);
        $this->ifNullInvokeError4xx($params['videoData'], 'Video ' . htmlspecialchars($itemName) . ' not found');

        $params['videoName'] = $itemName;

        return new Html('route/course/video/item.twig', $params, $this);
    }

    public function testingAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::VIDEO, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $params['testList'] = $materialService->getTestList($itemName, CourseService::VIDEO);
        $this->ifNullInvokeError4xx(
            $params['testList'],
            'Test list for video ' . htmlspecialchars($itemName) . ' not found'
        );

        $params['videoName'] = $itemName;

        return new Html('route/course/video/testing.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::VIDEO);
    }
}
