<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Di\Exception\DiException;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

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
        $response = $this->checkRight(CourseService::CARD, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $params[''] = '';

        return new Html('route/course/card/content.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::CARD);
    }
}
