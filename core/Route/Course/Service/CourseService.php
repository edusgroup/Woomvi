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

    public function getCategoryNum($categoryUrl)
    {
        $data = $this->courseDao->getCategoryNum($categoryUrl);
        if (!$data) {
            return null;
        }
        return $data['num'];
    }

    public function getArticleFilename($categoryNum, $itemName)
    {
        $data = $this->courseDao->getArticleFilename($categoryNum, $itemName);
        if (!$data) {
            return null;
        }
        return 'article/data/' . chunk_split($data['id'], 2, '/') . 'data.txt';
    }
}

