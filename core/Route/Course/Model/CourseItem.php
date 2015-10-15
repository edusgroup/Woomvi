<?php

namespace Site\Route\Course\Model;

class CourseItem
{
    private $url;
    private $id;
    private $name;
    private $courseCardList;

    /**
     * @param string $url
     * @param string $id
     * @param string $name
     * @param CourseCard[] $courseCardList
     */
    public function __construct($url, $id, $name, array $courseCardList)
    {
        $this->url = $url;
        $this->id = $id;
        $this->name = $name;
        $this->courseCardList = $courseCardList;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return CourseCard[]
     */
    public function getCourseCardList()
    {
        return $this->courseCardList;
    }
}
