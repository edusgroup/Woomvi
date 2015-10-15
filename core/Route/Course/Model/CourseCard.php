<?php

namespace Site\Route\Course\Model;

class CourseCard
{
    private $name;
    private $url;
    private $caption;
    private $isOpen = false;

    /**
     * @param string $name Название
     * @param string $url URL
     * @param string $caption Заголовок
     */
    public function __construct($name, $url, $caption)
    {
        $this->name = $name;
        $this->url = $url;
        $this->caption = $caption;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setOpenStatus($status)
    {
        $this->isOpen = $status;
    }

    public function isOpen()
    {
        return $this->isOpen;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
