<?php

namespace Site\Route\Course\Dao;

class TestingDao extends \Flame\Abstracts\Db\Dao
{
    const GRAMMAR = 'grammar';

    public function addGrammarEvent($courseName, $userId)
    {
        $data = $this->getEventDataByName($courseName, self::GRAMMAR, $userId);
        if (!$data) {
            $this->addEvent(self::GRAMMAR, $userId, $courseName);
        }
    }

    private function addEvent($eventName, $userId, $courseName, $match = [])
    {
        $match['time'] = time();
        $match['notice'] = false;
        $data['course'][$courseName][$eventName] = $match;
        $data['userId'] = $userId;

        return $this->driver->table(self::TABLE_SITE_EVENTS)->update($data, ['userId' => $userId]);
    }
}
