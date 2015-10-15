<?php

namespace Site\Common\Modules\WordSpeed\Service;

use Site\Common\Modules\WordSpeed\Dao\WordSpeedDao;

class WordSpeedService
{
    private $wordSpeedDao;

    public function __construct(WordSpeedDao $wordSpeedDao)
    {
        $this->wordSpeedDao = $wordSpeedDao;
    }

    public function getWordList($name)
    {
        $list = $this->wordSpeedDao->getWordList($name);
        if (!$list) {
            return [];
        }

        $list = iterator_to_array($list)[$name];
        return $list['list'];
    }
}
