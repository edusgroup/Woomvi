<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class IndexController extends \Site\Common\Controller\BaseController
{
    public function indexAction()
    {
        return new Html('route/course/content.twig', [], $this);
    }

    public function categoryAction($path, $courseName)
    {
        $vars['courseName'] = $courseName;
        return new Html('route/course/list.twig', $vars, $this);
    }

    public function itemAction($path, $categoryUrl, $itemName)
    {   echo 1;
        exit;
        /** @var \Site\Route\Course\Service\CourseService $courseService */
        $courseService = $this->fabric('couseService');

        /** @var int $categoryNum*/
        // Ищем номер категории
        $categoryNum = $courseService->getCategoryNum($categoryUrl);
        // Если ни чего не найдено, то вызываем 404 ошибку
        if (!$categoryNum) {
            $this->invokeError4xx();
        }

        /** @var string $filename */
        // Получаем имя файла для отображения
        $filename = $courseService->getArticleFilename($categoryNum, $itemName);
        // Если ни чего не найдено, то вызываем 404 ошибку
        if (!$filename) {
            $this->invokeError4xx();
        }

        $var['contentFile'] = $this->getFileFormData($filename);

        return new Html('route/grammar/item.twig', $var, $this);
    }
}

