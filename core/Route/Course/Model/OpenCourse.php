<?php

namespace Site\Route\Course\Model;

class OpenCourse
{
    private $name;
    private $list;

    public function __construct($name, array $list)
    {
        $this->name = $name;
        $this->list = $list;
    }

    public function isInArray($item)
    {
        return in_array($item, $this->list);
    }

    public function getName()
    {
        return $this->name;
    }
}
