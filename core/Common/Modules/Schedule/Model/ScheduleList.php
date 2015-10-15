<?php

namespace Site\Common\Modules\Schedule\Model;

use Flame\Abstracts\Model\Read;
use Flame\Classes\Model\Collection;

/**
 * Class ScheduleList
 * @package Site\Common\Modules\Schedule\Model
 *
 * @method int getMaxCountElement() Максимальное количество элементов в общем списке
 */
class ScheduleList extends Read
{
    public function getRules()
    {
        return null;
    }

    public function getId()
    {
        return $this->getInnerId();
    }

    public function getList()
    {

        return new Collection($this->list['list'], new TodoItem());
    }

    public function setMaxCountElement($maxCountElement)
    {
        $this->list['maxCountElement'] = $maxCountElement;
    }
}
