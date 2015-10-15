<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Classes\User;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class TrashMistakeController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $itemName Название группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');


        $response = $this->checkRight(CourseService::TRASH_MISTAKE, $itemName);
        if ($response !== null) {
            return $response;
        }



        $params['trashMistakeList'] = $courseService->getTrashMistakeData($itemName);
        $this->ifNullInvokeError4xx($params['trashMistakeList']);

        $params['trashMistakeName'] = $itemName;

        return new Html('route/course/trash-mistake/item.twig', $params, $this);
    }

    /*public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::TRASH_MISTAKE);
    }*/
}
