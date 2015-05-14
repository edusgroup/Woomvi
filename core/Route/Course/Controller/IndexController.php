<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class IndexController extends \Site\Common\Controller\BaseController
{
    public function indexAction()
    {
        return new Html('route/course/content.twig', [], $this);
    }

    public function categoryAction($path, $courseName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $courseData= $courseService->getCourseData($courseName);
        $this->ifNullInvokeError4xx($courseData);

        $user = $this->getUser($this->fabric('user.dao'));

        $openCourse = $courseService->getEventsByName('', $user->getId());
        if (!isset($openCourse['grammar'][$courseName])) {
            die('Вам еще не открыта');
        }

        $vars['courseData'] = $courseService->getOpenCategory($courseData, $openCourse);

        return new Html('route/course/list.twig', $vars, $this);
    }
}
