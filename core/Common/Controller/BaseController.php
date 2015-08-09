<?php

namespace Site\Common\Controller;

use Flame\Classes\Http\Response\Html;
use Flame\Traits\Twig;
use Site\Common\Traits\User;
use Flame\Abstracts\BaseController as BaseAbstractController;
use Site\Route\Course\Service\CourseService;

use \Site\Common\Classes\User as UserModel;

class BaseController extends BaseAbstractController
{
    const TPL_USER_NOT_AUTH = 'global/user/page-no-auth.twig';
    const TPL_USER_NOT_OPEN = 'global/user/page-no-open.twig';
    const TPL_USER_NOT_TIME = 'global/user/page-no-time.twig';

    use User;
    use Twig;

    public function preCallAction($methodName)
    {
        $this->user = $this->getUser($this->fabric('user.dao'));
    }

    public function preInitCommon($methodName, $matches)
    {
        // $this->initMenuCommon($methodName, $matches);
    }

    public function preRenderCommon(\Twig_Environment $twig, &$tplName, &$varible)
    {
        $this->extendsTwig($twig);
        $varible['isAuth'] = $this->user->isAuth() ? 1 : 0;
    }

    public function nextLevel($path, $courseName, $type)
    {
        $response = $this->checkRight($type, $courseName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $item = $courseService->openNextLevel($type, $courseName, $this->user->getId());
        $this->ifNullInvokeError4xx(
            $item,
            'Open level type="' . $type . '" course="' . htmlspecialchars($courseName) . '" not found'
        );

        $groupName = $courseService->getCourseName($type, $courseName);
        $this->ifNullInvokeError4xx($groupName);

        $vars['courseName'] = $groupName;

        return new Html('route/course/testing.twig', $vars, $this);
    }

    public function checkRight($courseType, $itemName, $courseName = '')
    {
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $isDemoCategory = $courseService->isDemo($courseType, $itemName);
        if ($isDemoCategory) {
            return null;
        }

        if (!$this->user->isAuth()) {
            return new Html(self::TPL_USER_NOT_AUTH, [], $this);
        }

        if ($this->user->getSum() <= 0) {
            die('No money');
        }

        if ($courseType == CourseService::GET_ABSTRACT) {
            // Получаем все открытые блоки для пользователя и если есть выбранную книгу
            $bookKey = 'course.' . $courseType . '.' . $courseName;
            $openCourse = $courseService->getEventsByName(
                $courseType . '.' . $courseName,
                $this->user->getId(),
                [$bookKey => 1]
            );

            if (isset($openCourse[CourseService::GET_ABSTRACT][$courseName]['name'])) {
                $bookName = $openCourse[CourseService::GET_ABSTRACT][$courseName]['name'];
                $openCourse = $bookName != $itemName ? [] : [$courseType => [$itemName => ['open' => true]]];
            } else {
                $openCourse = [];
            }
        } else {
            $openCourse = $courseService->getEventsByName(
                $courseType . '.' . $itemName,
                $this->user->getId(),
                ['course.' . $courseType => 1]
            );
        }

        if (!$openCourse) {
            return new Html(self::TPL_USER_NOT_OPEN, [], $this);
        }

        if (!$openCourse[$courseType][$itemName]['open']) {
            return new Html(self::TPL_USER_NOT_TIME, [], $this);
        }

        return null;
    }
}
