<?php

namespace Site\Route\Test\Controller;

use Flame\Classes\RequestHttp;
use Flame\Classes\Http\Response\Json;
use Flame\Classes\Http\Response\Html;
use Flame\Classes\StreamReader\FabricStreamReader;
use Site\Common\Classes\EmailTransport;
use Site\Common\Controller\BaseController;
use Site\Common\Struct\Email;

class IndexController extends BaseController
{
    public function indexAction()
    {
        /** @var EmailTransport $emailService */
        $emailService = $this->fabric('email.transport');

        $email = new Email();
        $email->email = 'timevk@gmail.com';
        $emailService->send('new-user', $email);

        echo 1;
    }
}
