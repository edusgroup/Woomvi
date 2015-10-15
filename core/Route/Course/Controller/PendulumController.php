<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\BreadCrumbs;
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
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $courseGroupName = $courseService->getCourseGroupName(CourseService::TRASH_MISTAKE, $itemName);

        $response = $this->checkRight(CourseService::PENDULUM, $itemName, $courseGroupName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $pendulumList = $courseService->getPendulumList($itemName);
        $this->ifNullInvokeError4xx($pendulumList);
        $params['pendulumList'] = $pendulumList;
        unset($pendulumList);

        $params['breadcrumbs'] = new BreadCrumbs([
            ['url' => '/course/', 'name' => 'Программа изучения'],
            ['url' => '/course/be-have/', 'name' => 'Урок 1. "Be-have"']
        ], 'Speaking №1');

        $params['pendulumName'] = $itemName;

        return new Html('route/course/pendulum/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::PENDULUM);
    }
}
