<?php

namespace Site\Common\Modules\WordSpeed\Dao;

use Flame\Abstracts\Db\Dao;

class WordSpeedDao extends Dao
{
    const TABLE_SITE_WORDSPEED = 'siteWordSpeed';

    public function getWordList($name)
    {
        return $this->driver->table(self::TABLE_SITE_WORDSPEED)->selectAll([], ['_id' => $name]);
    }
}
