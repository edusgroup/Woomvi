<?php

namespace Site\Route\Course\Service;

class TestingService
{
    private $testingDao;

    /**
     * @param \Site\Route\Course\Dao\TestingDao $testingDao
     */
    public function __construct($testingDao)
    {
        $this->testingDao = $testingDao;
    }

    public function addGrammarEvent($courseName, $userId)
    {
        $this->testingDao->addGrammarEvent($courseName, $userId);
    }
}
