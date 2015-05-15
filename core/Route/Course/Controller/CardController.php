<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class CardController extends BaseController
{
    /**
     * ��������� ����������� ��������
     *
     * @param string $path ������ ���� �� URL
     * @param string $cardName �������� ��������
     *
     * @return Html �������
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $cardName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['cardData'] = $materialService->getCardData($cardName);
        $this->ifNullInvokeError4xx($vars['cardData'], 'Card ' . htmlspecialchars($cardName) . ' not found');

        return new Html('route/course/card/item.twig', $vars, $this);
    }
}
