<?php

namespace Site\Common\Dao;

class ArticleDao extends \Flame\Abstracts\Db\Dao
{
    public function getFile($artileId, $group)
    {
        return $this->driver->table('article')->selectFirst([], ['url' => $artileId, 'group' => $group]);
    }
}