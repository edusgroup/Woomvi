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
    const TPL_USER_NO_MONEY = 'global/user/page-no-money.twig';

    //const TIME_HOUR_UNBLOCK_COUNT = 16 * 60 * 60;

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
        $this->extendsTwig($twig, $this);
        $varible['isAuth'] = $this->user->isAuth() ? 1 : 0;
    }

//    public function nextLevel($path, $courseName, $type)
//    {
//        $response = $this->checkRight($type, $courseName);
//        if ($response !== null) {
//            return $response;
//        }
//
//        /** @var CourseService $courseService */
//        $courseService = $this->fabric('course.service');
//
//        //$item = $courseService->openNextLevel($type, $courseName, $this->user->getId());
//        $this->ifNullInvokeError4xx(
//            $item,
//            'Open level type="' . $type . '" course="' . htmlspecialchars($courseName) . '" not found'
//        );
//
//        $groupName = $courseService->getCourseName($type, $courseName);
//        $this->ifNullInvokeError4xx($groupName);
//
//        $vars['courseName'] = $groupName;
//
//        return new Html('route/course/testing.twig', $vars, $this);
//    }

    public function checkRight($courseType, $itemName, $courseGroupName)
    {
        if (!$courseGroupName) {
            die('Not found');
            return new Html(self::TPL_USER_NOT_OPEN, [], $this);
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $isDemoCategory = $courseService->isDemo($courseType, $itemName);
        if ($isDemoCategory) {
            //return null;
        }

        if (!$this->user->isAuth()) {
            return new Html(self::TPL_USER_NOT_AUTH, [], $this);
        }

        if ($this->user->getSum() <= 0) {
            return new Html(
                self::TPL_USER_NO_MONEY,
                ['sum' => $this->user->getSum()],
                $this
            );
        }

        /** boolean $isEnoughRight */

        if ($courseType == CourseService::GET_ABSTRACT) {
            // Получаем все открытые блоки для пользователя и если есть выбранную книгу
            $bookName = $courseService->getEventListByName(
                $courseGroupName,
                $courseType,
                $this->user->getInnerId()
            );

            $isEnoughRight = (boolean) $bookName;

            /*if (isset($openCourse[CourseService::GET_ABSTRACT][$courseGroupName]['name'])) {
                $bookName = $openCourse[CourseService::GET_ABSTRACT][$courseGroupName]['name'];
                $openCourse = $bookName != $itemName ? [] : [$courseType => [$itemName => ['time' => true]]];
            } else {
                $openCourse = [];
            }*/
        } else {
            $openCourseList = $courseService->getEventListByName(
                $courseGroupName,
                $courseType,
                $this->user->getInnerId()
            );

            $isEnoughRight = in_array($itemName, $openCourseList);
        }

        return $isEnoughRight ? null : new Html(self::TPL_USER_NOT_OPEN, [], $this);
    }
}
