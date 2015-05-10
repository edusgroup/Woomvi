<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class GrammarController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $courseName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        /** @var string $filename */
        $filename = $courseService->getGrammarFile($courseName);
        $this->ifNullInvokeError4xx($filename);

        $vars['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/grammar/item.twig', $vars, $this);
    }
}
