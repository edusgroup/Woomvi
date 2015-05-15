<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class GetAbstractController extends BaseController
{
    /**
     * ��������� �����������
     *
     * @param string $path ������ ���� �� URL
     * @param string $abstractName �������� ���������
     *
     * @return Html �������
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
