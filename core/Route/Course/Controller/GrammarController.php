<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class GrammarController extends \Site\Common\Controller\BaseController
{
    use \Flame\Traits\Session;

    const TPL_USER_NOT_AUTH = 'global/user/notAuth.twig';

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
