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

        $vars['cardData'] = $materialService->getCardData($itemName);
        $this->ifNullInvokeError4xx($vars['cardData'], 'Card ' . htmlspecialchars($itemName) . ' not found');

        return new Html('route/course/card/item.twig', $vars, $this);
    }
}
