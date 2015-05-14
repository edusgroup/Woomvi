<?php

namespace Site\Route\Course\Service;

class GrammarService
{
    private $grammarDao;

    /**
     * @param \Site\Route\Course\Dao\MaterialDao $grammarDao
     */
    public function __construct($grammarDao)
    {
        $this->materialDao = $grammarDao;
    }

    public function setGrammar($userId)
    {
        /** @var \MongoCursor $data */
        /*$data = $this->materialDao->getSpeakingData($speaingId);
        if (!$data) {
            return null;
        }

        return $data;*/
    }


}
