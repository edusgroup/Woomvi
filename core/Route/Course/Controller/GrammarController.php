<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

use Flame\Traits\Session;

class GrammarController extends BaseController
{
    use Session;

    const TPL_USER_NOT_AUTH = 'global/user/notAuth.twig';

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
        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            return new Html(self::TPL_USER_NOT_AUTH, [], $this);
        }

        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        /** @var string $filename */
        $filename = $courseService->getGrammarFile($courseName);
        $this->ifNullInvokeError4xx($filename);

        $vars['user'] = $user;
        $vars['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/grammar/item.twig', $vars, $this);
    }

    /**
     * @param string $path Полный путь из URL
     * @param string $courseName Название курса
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function testingAction($path, $courseName)
    {
        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            return new Html(self::TPL_USER_NOT_AUTH, [], $this);
        }

        /** @var \Site\Route\Course\Service\TestingService $grammarService */
        $testingService = $this->fabric('testing.service');
        $testingService->addGrammarEvent($user->getId(), $courseName);

        $vars['courseName'] = $courseName;

        return new Html('route/course/grammar/testing.twig', $vars, $this);
    }
}
