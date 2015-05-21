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

        $vars['questionList'] = $courseService->getQuestionList($itemName);
        $this->ifNullInvokeError4xx($vars['questionList']);

        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            die('User not reg');
        }

        $openCourse = $courseService->getEventsByName(
            CourseService::QUESTION_ANSWER . '.' . $itemName,
            $user->getId(),
            ['course.' . CourseService::QUESTION_ANSWER => 1]
        );
        if (!$openCourse) {
            die('Not access');
        }

        return new Html('route/course/question-answer/item.twig', $vars, $this);
    }

    public function nextLevelAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::QUESTION_ANSWER, $itemName);
        if ($response !== null) {
            return $response;
        }
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        /** @var User $user */
        $user = $this->getUser($this->fabric('user.dao'));

        $item = $courseService->openNextLevel(CourseService::QUESTION_ANSWER, $itemName, $user->getId());
        $this->ifNullInvokeError4xx($item);

        echo 3;
    }
}
