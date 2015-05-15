<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;

class SpeakingController extends BaseController
{
    /**
     * @param string $path Полный путь из URL
     * @param string $speakingName Название speaking группы
     *
     * @return Html Респонс
     * @throws \Flame\Classes\Di\Exception\DiException
     */
    public function indexAction($path, $speakingName)
    {
        /** @var \Site\Route\Course\Service\MaterialService $materialService */
        $materialService = $this->fabric('material.service');

        $vars['speakingData'] = $materialService->getSpeakingData($speakingName);
        $this->ifNullInvokeError4xx($vars['speakingData'], 'Speaking ' . htmlspecialchars($speakingName) . ' not found');

        return new Html('route/course/speaking/item.twig', $vars, $this);
    }
}
