<?php

namespace Site\Route\Shop\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;

class IndexController extends \Site\Common\Controller\BaseController
{
    const ARTICLE_SHOP_GROUP = 'shop';

    public function indexAction()
    {
        return new Html('route/shop/content.twig', [], $this);
    }

    public function itemAction($path, $itemName)
    {
        /** @var \Site\Common\Service\ArticleService $articleService */
        $articleService = $this->fabric('articleService');
        $fileName = $articleService->getFile($itemName, self::ARTICLE_SHOP_GROUP);
        if (!$fileName) {
            $this->invokeError4xx();
        }

        $fileName = $this->getFileFormData($fileName);
        $vars['contentFile'] = $fileName;

        return new Html('route/shop/item.twig', $vars, $this);
    }
}

