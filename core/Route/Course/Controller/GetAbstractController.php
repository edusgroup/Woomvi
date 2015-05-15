<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class GetAbstractController extends BaseController
{
    /**
     * Обработка отображения
     *
     * @param string $path Полный путь из URL
     * @param string $abstractName Название абстракта
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $abstractName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['abstractData'] = $materialService->getGetAbstractData($abstractName);
        $this->ifNullInvokeError4xx($vars['abstractData'], 'AbstractData ' . htmlspecialchars($abstractName) . ' not found');

        return new Html('route/course/getAbstract/item.twig', $vars, $this);
    }
}
