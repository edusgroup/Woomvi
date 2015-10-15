<?php

namespace Site\Common\Modules\Schedule\Model;

use Flame\Classes\Model\Collection;

class ScheduleCollection extends Collection
{
    private $maxCountElement;
    private $taskCheckedList;

    public function setMaxCountElement($max)
    {
        $this->maxCountElement = $max;
    }

    public function getMaxCountElement()
    {
        return $this->maxCountElement;
    }

    public function setTaskListChecked($list)
    {
        $this->taskCheckedList = $list;
    }

    public function getTaskListChecked()
    {
        return $this->taskCheckedList;
    }
}
