<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class IndexController extends BaseController
{
    public function indexAction()
    {
        return new Html('route/course/content.twig', [], $this);
    }

    /**
     * Роутинг категорий курса
     *
     * @param string $path Полный путь из URL
     * @param string $courseName Название курса
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function categoryAction($path, $courseName)
    {
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $courseData= $courseService->getCourseData($courseName);
        $this->ifNullInvokeError4xx($courseData);

        $user = $this->getUser($this->fabric('user.dao'));

        $openCourse = $courseService->getEventsByName('', $user->getId());
        if (!isset($openCourse['grammar'][$courseName])) {
            die('Not open yet');
        }

        $vars['courseData'] = $courseService->getOpenCategory($courseData, $openCourse);

        return new Html('route/course/list.twig', $vars, $this);
    }
}
