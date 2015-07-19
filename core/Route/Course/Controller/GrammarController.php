<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

use Flame\Traits\Session;

class GrammarController extends BaseController
{
    use Session;



    /**
     * Роутинг для грамматики
     *
     * @param string $path Полный путь из URL
     * @param string $courseName
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $courseName)
    {
        $response = $this->checkRight(CourseService::GRAMMAR, $courseName);
        if ($response !== null) {
            return $response;
        }


        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        /** @var string $filename */
        $filename = $courseService->getGrammarFile($courseName);
        $this->ifNullInvokeError4xx($filename);

        $vars['user'] = $this->user;
        $vars['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/grammar/item.twig', $vars, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::GRAMMAR);
    }
}
