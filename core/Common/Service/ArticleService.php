<?php

namespace Site\Common\Service;

class ArticleService
{
    public $articleDao;

    /**
     * @param \Site\Common\Dao\ArticleDao $articleDao
     */
    public function __construct($articleDao)
    {
        $this->articleDao = $articleDao;
    }

    public function getFile($artileId, $group)
    {
        $data = $this->articleDao->getFile($artileId, $group);
        if (!$data) {
            return null;
        }

        return 'article/data/' . chunk_split($data['id'], 2, '/') . 'data.txt';
    }
}
