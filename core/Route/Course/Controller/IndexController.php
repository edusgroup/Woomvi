<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Flame\Classes\RequestHttp;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;
use Site\Route\Course\Service\IndexService;
use Site\Route\Course\Service\MaterialService;

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
        /** @var IndexService $indexService */
        $indexService = $this->fabric('course.service.index');

        $data = $indexService->checkRight($courseName, $this->user);
        if ($data instanceof Html) {
            return $data;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $params['courseData'] = $courseService->getOpenCategory(
            $data['courseData'],
            $data['openCourse'],
            $courseName
        );
        unset($data);

        $params['courseName'] = $courseName;

        //$request = new RequestHttp();
        //$params['thisPath'] = $request->getPath();

        return new Html('route/course/list.twig', $params, $this);
    }

    public function chooseBookAction($path, $courseName)
    {
        if ($this->user->isAuth()) {
            /** @var IndexService $indexService */
            $indexService = $this->fabric('course.service.index');
            $data = $indexService->checkRight($courseName, $this->user);
            if ($data instanceof Html) {
                return $data;
            }

            $openCourse = $data['openCourse'];
            unset($data);
            $params['courseName'] = $courseName;
        } else {
            $openCourse[CourseService::GET_ABSTRACT] = [];
            $params['isDemoMode'] = true;

            $request = new RequestHttp();
            $params['documentUrl'] = $request->getPath();
        }

        /** @var MaterialService $materialService */
        $materialService = $this->fabric('material.service');
        $params['bookList'] = $materialService->getAvailableBookList($openCourse);

        return new Html('route/course/getAbstract/choose.twig', $params, $this);
    }

    public function chooseBookLogicAction($path, $courseName)
    {
        /** @var IndexService $indexService */
        $indexService = $this->fabric('course.service.index');
        $data = $indexService->checkRight($courseName, $this->user);
        if ($data instanceof Html) {
            return $data;
        }

        $openCourse = $data['openCourse'];
        unset($data);

        $urlCourse = $this->getRoutePath('course.category', $courseName);
        if (!$this->user->isAuth()) {
            return $this->redirectToUrl($urlCourse);
        }

        //if (isset($openCourse[CourseService::GET_ABSTRACT][$courseName]['name'])) {
        //    return $this->redirectToUrl($urlCourse);
        //}

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $request = new RequestHttp();
        $bookId = $request->get('book-id');
        if ($bookId) {
            $courseService->chooseBook($bookId, $courseName, $this->user->getId());
        }

        return $this->redirectToUrl($urlCourse);
    }
}
