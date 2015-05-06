<?php

namespace Site\Route\Course\Dao;

class CourseDao
{
    const CATEGORY_GROUP = 'course';

    public $driver;

    /**
     * @param \Flame\Abstracts\Db\Driver $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function getCategoryNum($categoryUrl)
    {
        return $this->driver->table('categoryList')->selectFirst(['num'], ['url' => $categoryUrl, 'group' => self::CATEGORY_GROUP]);
    }

    public function getArticleFilename($categoryNum, $itemName)
    {
        return $this->driver->table('article')->selectFirst(['id'], ['url' => $itemName, 'categoryNum' => $categoryNum]);
    }
}