<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;

class GetAbstractController extends BaseController
{
    /**
     * Обработка отображения
     *
     * @param string $path Полный путь из URL
     * @param string $itemName Название абстракта
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::GET_ABSTRACT, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['abstractData'] = $materialService->getGetAbstractData($itemName);
        $this->ifNullInvokeError4xx($vars['abstractData'], 'AbstractData ' . htmlspecialchars($itemName) . ' not found');

        return new Html('route/course/getAbstract/item.twig', $vars, $this);
    }
}
