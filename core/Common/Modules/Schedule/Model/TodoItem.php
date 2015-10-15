<?php

namespace Site\Common\Modules\Schedule\Model;

use Flame\Abstracts\Model\Read;

/**
 * Class TodoItem
 * @package Site\Common\Modules\Schedule\Model
 *
 * @method string getName() Название задачи
 * @method string getId() ID задачи
 * @method string getUrl() URL для генератора
 * @method string[] getParams() Набор параметров для генератора
 */
class TodoItem extends Read
{
}
