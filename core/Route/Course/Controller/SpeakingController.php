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

        $vars['speakingData'] = $materialService->getSpeakingData($itemName);
        $this->ifNullInvokeError4xx($vars['speakingData'], 'Speaking ' . htmlspecialchars($itemName) . ' not found');

        return new Html('route/course/speaking/item.twig', $vars, $this);
    }
}
