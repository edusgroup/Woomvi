<?php

namespace Site\Route\Course\Model;

class CourseData
{
    private $courseData;
    private $openCourse;

    /**
     * @param $courseData
     * @param OpenCourse[]|OpenGetAbstractCourse[] $openCourse
     */
    public function __construct($courseData, $openCourse)
    {
        $this->courseData = $courseData;
        $this->openCourse = $openCourse;
    }

    /**
     * @return CourseItem
     */
    public function getCourseData()
    {
        return $this->courseData;
    }

    /**
     * @return OpenCourse[]|OpenGetAbstractCourse[]
     */
    public function getOpenCourse()
    {
        return $this->openCourse;
    }
}
