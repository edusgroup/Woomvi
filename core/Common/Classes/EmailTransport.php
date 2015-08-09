<?php

namespace Site\Common\Classes;

use Flame\Classes\StreamReader\FabricStreamReader;
use Site\Common\Struct\Email;

class EmailTransport
{
    /** @var \Swift_Mailer Swift Mailer */
    private $mailer;

    /** @var \Twig_Environment Твиг шаблонизатор */
    private $twig;

    /** @var string Название домена */
    private $httpHost;

    private $siteRoot;
    private $lang;

    public function __construct($host, $port, $siteRoot, $httpHost, $lang)
    {
        $this->siteRoot = $siteRoot;
        $this->lang = $lang;

        $swiftTransport = new \Swift_SmtpTransport($host, $port);

        $this->mailer = new \Swift_Mailer($swiftTransport);

        $loader = new \Twig_Loader_Filesystem($siteRoot . 'tpl/email/');
        $this->twig = new \Twig_Environment($loader);

        $this->httpHost = $httpHost;
    }

    public function send($template, Email $emailData)
    {
        $tplLangUri = $this->siteRoot . 'tpl/lang/' . $this->lang . '/email/' . $template . '.json';

        $fabricStreamReader = new FabricStreamReader();
        $reader = $fabricStreamReader->get($tplLangUri);

        $template = $this->twig->loadTemplate($template . '.twig');

        $params['imgHost'] = $this->httpHost;
        $params['email'] = $emailData;
        $params['lang'] = json_decode($reader->getData());
        $html = $template->render($params);

        $subject = isset($params['lang']->subject) ? $params['lang']->subject : 'Not set subject';
        $swiftMessage = new \Swift_Message($subject);

        $swiftMessage->setFrom(['vk@wumvi.com' => 'Wumvi Lola'])
            ->setTo($emailData->email)
            ->setBody($html, 'text/html');

        try {
            $msgResponse = $this->mailer->send($swiftMessage, $failures);

            var_dump($msgResponse, $failures);
            if ($msgResponse) {
                return $failures;
            }
        } catch (\Exception $ex) {
            var_dump($ex->getMessage());
            return $ex->getMessage();
        }

        return false;
    }
}
