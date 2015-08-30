<?php

namespace Site\Route\Course\Model;

use Flame\Abstracts\Model\Read;

/**
 * Class Card
 * @package Site\Route\Course\Model
 * @method string getText()
 * @method string getList()
 */
class CheckTest extends Read
{
    public function getAnswerId()
    {
        return $this->get('aid');
    }
}
