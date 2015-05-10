<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class CardController extends \Site\Common\Controller\BaseController
{
    public function indexAction($path, $cardName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['cardData'] = $materialService->getCardData($cardName);
        $this->ifNullInvokeError4xx($vars['cardData'], 'Card ' . htmlspecialchars($cardName) . ' not found');

        return new Html('route/course/card/item.twig', $vars, $this);
    }
}
