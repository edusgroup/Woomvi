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
        $vars['pendulumList'] = $courseService->getPendulumList($itemName);
        $this->ifNullInvokeError4xx($vars['pendulumList']);

        $vars['pendulumName'] = $itemName;

        return new Html('route/course/pendulum/item.twig', $vars, $this);
    }

    public function pdfAnswerAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::PENDULUM, $itemName);
        if ($response !== null) {
            return $response;
        }

        $user = $this->getUser($this->fabric('user.dao'));

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $item = $courseService->openNextLevel(CourseService::PENDULUM, $itemName, $user->getId());
        $this->ifNullInvokeError4xx($item);

        echo 1;
    }
}
