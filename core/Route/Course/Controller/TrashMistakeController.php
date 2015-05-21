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
        $response = $this->checkRight(CourseService::TRASH_MISTAKE, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');

        $vars['trashMistakeList'] = $courseService->getTrashMistakeData($itemName);
        $this->ifNullInvokeError4xx($vars['trashMistakeList']);

        return new Html('route/course/trash-mistake/item.twig', $vars, $this);
    }

    public function nextLevelAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::TRASH_MISTAKE, $itemName);
        if ($response !== null) {
            return $response;
        }
        /** @var CourseService $courseService */
        $courseService = $this->fabric('course.service');
        /** @var User $user */
        $user = $this->getUser($this->fabric('user.dao'));

        $item = $courseService->openNextLevel(CourseService::TRASH_MISTAKE, $itemName, $user->getId());
        $this->ifNullInvokeError4xx($item);

        echo 2;
    }
}
