<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Di\Exception\DiException;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;
use Site\Route\Course\Service\MaterialService;

class CheckController extends BaseController
{
    /**
     * Обработка отображения карточки
     *
     * @param string $path Полный путь из URL
     * @param string $itemName Название карточки
     *
     * @return Html Респонс
     * @throws DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::CHECK, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $userLevelComplexity = $this->user->getLevelOfComplexity();
        $data = $materialService->getCheckList($itemName, $userLevelComplexity);
        $checkList = $data['list'];
        $this->ifNullInvokeError4xx($checkList, 'Card ' . htmlspecialchars($itemName) . ' not found');

        $params['checkList'] = $checkList;
        $params['errorCount'] = $data['error-count'];
        unset($checkList);

        return new Html('route/course/check/content.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::CARD);
    }
}
