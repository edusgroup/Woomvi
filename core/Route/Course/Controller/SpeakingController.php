<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class SpeakingController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $itemName Название speaking группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::SPEAKING, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $cardList = $materialService->getSpeakingData($itemName);
        $this->ifNullInvokeError4xx($cardList, 'Speaking ' . htmlspecialchars($itemName) . ' not found');

        $vars['cardList'] = $cardList;
        unset($cardList);

        return new Html('route/course/speaking/item.twig', $vars, $this);
    }
}
