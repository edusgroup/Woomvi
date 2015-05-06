<?php

namespace Site\Route\Course\Controller;

use Flame\Classes\Http\Response\String;
use Flame\Classes\Http\Response\Html;

class GrammarController extends \Site\Common\Controller\BaseController
{
    public function indexAction()
    {
        return new Html('route/course/content.twig', [], $this);
    }
}

