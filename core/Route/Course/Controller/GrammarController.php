<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Di\Exception\DiException;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

use Flame\Traits\Session;

/**
 * Контроллер для работы с блоком Грамматики
 * @since 0.1
 */
class GrammarController extends BaseController
{
    // Работа с сессиям
    use Session;

    /**
     * Роутинг для грамматики
     *
     * @param string $path Полный путь из URL
     * @param string $courseName
     *
     * @return Html Респонс
     * @throws DiException
     */
    public function indexAction($path, $courseName)
    {
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $courseGroupName = $courseService->getCourseGroupName(CourseService::TRASH_MISTAKE, $courseName);

        $response = $this->checkRight(CourseService::GRAMMAR, $courseName, $courseGroupName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        /** @var string $filename */
        $filename = $courseService->getGrammarFile($courseName);
        $this->ifNullInvokeError4xx($filename);

        $params['user'] = $this->user;
        $params['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/grammar/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::GRAMMAR);
    }
}
