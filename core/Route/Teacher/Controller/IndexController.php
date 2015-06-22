<?php

namespace Site\Route\Teacher\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use Site\Common\Controller\BaseController;
use Site\Route\Teacher\Service\TeacherService;

class IndexController extends BaseController
{
    public function indexAction($path, $teacherId)
    {
        $renderVarible['lessonsLength'] = TeacherService::LESSONS_LENGTH;
        return new Html('route/teacher/content.twig', $renderVarible, $this);
    }
}
