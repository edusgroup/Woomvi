<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class QuestionController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $itemName Названи группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::QUESTION_ANSWER, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $params['questionList'] = $courseService->getQuestionList($itemName);
        $this->ifNullInvokeError4xx($params['questionList']);

        $params['questionName'] = $itemName;

        return new Html('route/course/question-answer/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::QUESTION_ANSWER);
    }
}
