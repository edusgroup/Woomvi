<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class TrashMistakeController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $groupName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $vars['trashMistakeList'] = $courseService->getTrashMistakeData($groupName);
        $this->ifNullInvokeError4xx($vars['trashMistakeList']);

        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            die('User not reg');
        }

        $openCourse = $courseService->getEventsByName('trashMistake.'.$groupName, $user->getId(), ['course.trashMistake'=>1]);
        if (!$openCourse) {
            die('Not access');
        }

        return new Html('route/course/trash-mistake/item.twig', $vars, $this);
    }
}
