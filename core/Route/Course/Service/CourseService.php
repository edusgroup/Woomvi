<?php

namespace Site\Route\Course\Service;


class CourseService
{
    private $courseDao;

    /**
     * @param \Site\Route\Course\Dao\CourseDao $courseDao
     */
    public function __construct($courseDao)
    {
        $this->courseDao = $courseDao;
    }

    /*public function getCategoryNum($categoryUrl)
    {
        $data = $this->courseDao->getCategoryNum($categoryUrl);
        if (!$data) {
            return null;
        }
        return $data['num'];
    }*/

    public function getGrammarFile($courseName)
    {
        $data = $this->courseDao->getGrammarFile($courseName);
        if (!$data) {
            return null;
        }
        return 'grammar/' . chunk_split($data['id'], 2, '/') . 'data.txt';
    }
}

