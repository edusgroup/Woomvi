<?php

namespace Site\Common\Modules\Tandem\Controller;

use Flame\Abstracts\Module;
use \Site\Common\Classes\User as UserModel;

class TandemModule extends Module
{
    const MODULE_NAME = 'tandem';

    public function getList(UserModel $user, $type)
    {
        $params = [];

        $params['list'][] = [
            'date' => ['date' => 'today', 'weekday' => 'пятница'],
            'list' => [
                ['id' => '24', 'name' => 'Прочитать грамматику "Be-have"', 'url' => '/grammar/be-have/'],
                ['id' => '26', 'name' => 'Писать', 'url' => '/getabstract/be-have/eat-that-frog/']
            ],
            'active' => true
        ];

        $params['type'] = $type;

        return $this->getResponse('module/tandem/list.twig', $params);
    }
}
