<?php

namespace Site\Common\Controller;

use Flame\Classes\Http\Response\Html;
use Flame\Traits\Twig;
use Site\Common\Traits\User;
use Flame\Abstracts\BaseController as BaseAbstractController;
use Site\Route\Course\Service\CourseService;

class BaseController extends BaseAbstractController
{
    const TPL_USER_NOT_AUTH = 'global/user/notAuth.twig';

	use User;
    use Twig;

    public function preCallAction($methodName)
    {

    }

	public function preInitCommon($methodName, $matches)
	{
		$this->initMenuCommon($methodName, $matches);
	}
	
	public function preRenderCommon(\Twig_Environment $twig, &$tplName, &$varible)
    {
		$this->extendsTwig($twig);
        $varible['isAuth'] = $this->getUser($this->fabric('user.dao'))->isAuth() ? 'true' : 'false';
	}
	
	protected function initMenuCommon()
	{
	
	}

    public function nextLevel($path, $courseName, $type)
    {
        $response = $this->checkRight($type, $courseName);
        if ($response !== null) {
            return $response;
        }

        $user = $this->getUser($this->fabric('user.dao'));

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $item = $courseService->openNextLevel($type, $courseName, $user->getId());
        $this->ifNullInvokeError4xx(
            $item,
            'Open level type="' . $type . '" course="' . htmlspecialchars($courseName). '" not found'
        );

        $groupName = $courseService->getCourseName($type, $courseName);
        $this->ifNullInvokeError4xx($groupName);

        $vars['courseName'] = $groupName;

        return new Html('route/course/testing.twig', $vars, $this);
    }

    public function checkRight($type, $key)
    {
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        $isDemoCategory = $courseService->isDemo($type, $key);
        if ($isDemoCategory) {
            return null;
        }

        $user = $this->getUser($this->fabric('user.dao'));
        if (!$user->isAuth()) {
            return new Html(self::TPL_USER_NOT_AUTH, [], $this);
        }

        if ($user->getSum() <= 0) {
            die('No money');
        }

        $openCourse = $courseService->getEventsByName(
            $type . '.' . $key,
            $user->getId(),
            ['course.' . $type => 1]
        );


        if (!$openCourse) {
            die('Not access');
        }

        if (!$openCourse[$type][$key]['open']){
            die('Not open yet');
        }

        return null;
    }
}
