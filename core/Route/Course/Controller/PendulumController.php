<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;

class PendulumController extends \Site\Common\Controller\BaseController
{
    /**
     * @param $path
     * @param $groupName
     * @return Html
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $groupName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $vars['pendulumList'] = $courseService->getPendulumList($groupName);
        $this->ifNullInvokeError4xx($vars['pendulumList']);

        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            die('User not reg');
        }

        $openCourse = $courseService->getEventsByName('pendulum.'.$groupName, $user->getId(), ['course.pendulum'=>1]);
        if (!$openCourse) {
            die('Not access');
        }

        return new Html('route/course/pendulum/item.twig', $vars, $this);
    }
}
