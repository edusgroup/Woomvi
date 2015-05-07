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
        // Получаем имя файла для отображения
        $filename = $courseService->getGrammarFile($courseName);
        // Если ни чего не найдено, то вызываем 404 ошибку
        if (!$filename) {
            $this->invokeError4xx();
        }

        $vars['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/grammar/item.twig', $vars, $this);
    }

    public function trashMistakeAction()
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        echo 'trash mistake';
    }

    public function questionAnswerAction()
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        echo 'answer-question';
    }
}
