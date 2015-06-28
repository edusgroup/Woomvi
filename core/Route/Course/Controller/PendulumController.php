<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class PendulumController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $itemName Название группы
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::PENDULUM, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $params['pendulumList'] = $courseService->getPendulumList($itemName);
        $this->ifNullInvokeError4xx($params['pendulumList']);

        $params['pendulumName'] = $itemName;

        return new Html('route/course/pendulum/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::PENDULUM);
    }
}
