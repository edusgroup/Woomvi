<?php

namespace Site\Route\Course\Dao;

class CourseDao
{
    const GRAMMAR_GROUP = 'course';

    public $driver;

    /**
     * @param \Flame\Abstracts\Db\Driver $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function getGrammarFile($courseName)
    {
        return $this->driver->table('courses')->selectFirst(['id'], ['url' => $courseName]);
    }
}