<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class CardController extends BaseController
{
    /**
     * Обработка отображения карточки
     *
     * @param string $path Полный путь из URL
     * @param string $itemName Название карточки
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::CARD, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $userLevelComplexity = $this->user->getLevelOfComplexity();

        $cardList = $materialService->getCardData($itemName, $userLevelComplexity);
        $this->ifNullInvokeError4xx($cardList, 'Card ' . htmlspecialchars($itemName) . ' not found');

        $params['cardList'] = $cardList;
        unset($cardList);

        $params['cardName'] = $itemName;

        return new Html('route/course/card/content.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::CARD);
    }
}
