<?php

namespace Site\Common\Modules\WordSpeed\Controller;

use Flame\Abstracts\Module;
use Flame\Classes\Http\Response\Html;
use \Site\Common\Classes\User as UserModel;
use Site\Common\Modules\WordSpeed\Service\WordSpeedService;

class WordSpeedModule extends Module
{
    const MODULE_NAME = 'wordspeed';

    public function getBlock(UserModel $user, $name)
    {
        /** @var WordSpeedService $wordspeedService */
        $wordspeedService = $this->getController()->fabric('module.wordspeed.service');

        $params = [];
        $params['wordList'] = $wordspeedService->getWordList($name);

        return $this->getResponse('module/wordspeed/content.twig', $params);
    }
}
