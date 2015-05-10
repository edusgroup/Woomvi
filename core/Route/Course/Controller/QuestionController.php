<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class QuestionController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $groupName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $vars['questionList'] = $courseService->getQuestionList($groupName);
        $this->ifNullInvokeError4xx($vars['questionList']);

        return new Html('route/course/question-answer/item.twig', $vars, $this);
    }
}
