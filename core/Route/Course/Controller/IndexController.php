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

        $vars['courseData'] = $courseService->getCourseData($courseName);
        $this->ifNullInvokeError4xx($vars['courseData']);

        return new Html('route/course/list.twig', $vars, $this);
    }
}
