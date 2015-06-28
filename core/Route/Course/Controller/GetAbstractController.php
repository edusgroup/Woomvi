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

        $params['abstractData'] = $materialService->getGetAbstractData($itemName);
        $this->ifNullInvokeError4xx(
            $params['abstractData'],
            'AbstractData ' . htmlspecialchars($itemName) . ' not found'
        );

        $params['abstractName'] = $itemName;

        return new Html('route/course/getAbstract/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::GET_ABSTRACT);
    }

    public function testingAction($path, $itemName)
    {
        $response = $this->checkRight(CourseService::GET_ABSTRACT, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $params['testList'] = $materialService->getTestList($itemName, CourseService::GET_ABSTRACT);
        $this->ifNullInvokeError4xx(
            $params['testList'],
            'Test list for getAbstract "' . htmlspecialchars($itemName) . '" not found'
        );

        $params['getabstractName'] = $itemName;

        return new Html('route/course/getAbstract/testing.twig', $params, $this);
    }
}
