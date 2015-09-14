<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Flame\Classes\RequestHttp;
use Site\Common\Controller\BaseController;
use Site\Route\Course\Service\CourseService;
use Site\Route\Course\Service\MaterialService;

/**
 * Контроллер обработки данных по книгам
 *
 * @package Site\Route\Course\Controller
 * @author Kozlenko Vitaliy
 * @version 1.1
 */
class GetAbstractController extends BaseController
{
    /**
     * Обработка отображения
     *
     * @param string $path Полный путь из URL
     * @param string $courseName Название курса
     * @param string $itemName Название абстракта
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $courseName, $itemName)
    {
        // Проверка прав доступа
        $response = $this->checkRight(CourseService::GET_ABSTRACT, $itemName, $courseName);
        if ($response !== null) {
            return $response;
        }

        /** @var MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $bookInfo = $materialService->getBookInfo($itemName);
        $this->ifNullInvokeError4xx(
            $bookInfo,
            'BookInfo ' . htmlspecialchars($itemName) . ' not found'
        );

        $params['abstractName'] = $itemName;
        $params['downloadId'] = sprintf($bookInfo['fileId'], $this->user->getLevelOfComplexity());

        $filename = 'book/' . $itemName . '/book-lvl' . $this->user->getLevelOfComplexity() . '.html';
        $params['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/course/getAbstract/item.twig', $params, $this);
    }

    public function nextLevelAction($path, $courseName)
    {
        return parent::nextLevel($path, $courseName, CourseService::GET_ABSTRACT);
    }

    public function testingAction($path, $itemName)
    {
        // Проверка прав доступа
        $response = $this->checkRight(CourseService::GET_ABSTRACT, $itemName);
        if ($response !== null) {
            return $response;
        }

        /** @var MaterialService $materialService */
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
