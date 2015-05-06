<?php

namespace Site\Common\Dao;

class ArticleDao
{
    public $driver;

    /**
     * @param \Flame\Abstracts\Db\Driver $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function getFile($artileId, $group)
    {
        return $this->driver->table('article')->selectFirst([], ['url' => $artileId, 'group' => $group]);
    }
}