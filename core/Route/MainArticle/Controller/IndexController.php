<?php

namespace Site\Route\MainArticle\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;

class IndexController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $articleName)
    {
        /** @var \Site\Common\Service\ArticleService $articleService */
        $articleService = $this->fabric('articleService');
        $fileName = $articleService->getFile($articleName, 'common');
        if (!$fileName) {
            $this->invokeError4xx();
        }

        $fileName = $this->getFileFormData($fileName);
        $vars['contentFile'] = $fileName;

        return new Html('route/mainArticle/content.twig', $vars, $this);
    }

    public function pricesAction()
    {
        return new Html('route/mainArticle/prices.twig', [], $this);
    }

    public function contactAction()
    {
        return new Html('route/mainArticle/contact.twig', [], $this);
    }

    public function aboutAction()
    {
        return new Html('route/mainArticle/about.twig', [], $this);
    }
}

