<?php

namespace Site\Route\Course\Model;

class OpenGetAbstractCourse
{
    private $lessonName;
    private $bookName;

    public function __construct($lessonName, $bookName)
    {
        $this->lessonName = $lessonName;
        $this->bookName = $bookName;
    }

    public function getLessonsName()
    {
        return $this->lessonName;
    }

    public function getBookName()
    {
        return $this->bookName;
    }
}
