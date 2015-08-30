<?php

namespace Site\Route\Course\Dao;

use Flame\Abstracts\Db\Dao;

class CourseDao extends Dao
{
    const GRAMMAR_GROUP = 'course';

    const TABLE_SITE_TRASH_MISTAKE = 'siteTrashMistake';
    const TABLE_SITE_QUESTION = 'siteQuestion';
    const TABLE_SITE_COURSE = 'courses';
    const TABLE_SITE_PENDULUM = 'sitePendulum';
    const TABLE_SITE_EVENTS = 'siteEvents';

    /**
     * Получаем ID файла, по которуму дальше его можно искать
     *
     * @param string $courseName Название файла
     *
     * @return array Массив с ID по файлу
     */
    public function getGrammarFile($courseName)
    {
        return $this->driver->table('courses')->selectFirst(['id'], ['url' => $courseName]);
    }

    public function getMainBlock()
    {

    }

    /**
     * Получаем список вопросов по TrashMistake
     *
     * @param string $groupName название группы вопросов
     *
     * @return array Массив с данными о TrashMistake
     */
    public function getTrashMistakeData($groupName)
    {
        return $this->driver->table(self::TABLE_SITE_TRASH_MISTAKE)->selectFirst([], ['id' => $groupName]);
    }

    public function getCourseData($courseName)
    {
        return $this->driver->table(self::TABLE_SITE_COURSE)->selectFirst([], ['url' => $courseName]);
    }

    public function getQuestionList($groupName)
    {
        $list = $this->driver->table(self::TABLE_SITE_QUESTION)->selectFirst([], ['group' => $groupName]);
        if (!$list) {
            return [];
        }

        return $this->mapping($list['list'], [
            // '#key' => 'id',
            'questionText' => 'question',
            'answerText' => 'answer'
        ]);
    }

    public function getPendulumList($groupName)
    {
        return $this->driver->table(self::TABLE_SITE_PENDULUM)->selectFirst(
            ['list' => 1],
            ['group' => $groupName]
        );
    }

    public function getEventsByName($name, $userId, $fields = [])
    {
        $name = $name ? '.' . $name : '';

        return $this->driver->table(self::TABLE_SITE_EVENTS)->selectFirst(
            $fields,
            ['course' . $name => ['$exists' => true], 'userId' => $userId]
        );
    }

    public function getCourseName($groupName, $blockName)
    {
        return $this->driver->table(self::TABLE_SITE_COURSE)->selectFirst(
            ['url' => 1, '_id' => 0],
            ['data.' . $groupName. '.' . $blockName => ['$exists' => true]]
        );
    }

    public function getNextLevelItem($type, $key)
    {
        return $this->driver->table('siteLevelSequence')->selectFirst(
            ["$type.$key" => 1],
            ["$type.$key" => ['$exists' => 1]]
        );
    }


    public function getEventDataByName($type, $key, $userId)
    {
        return $this->driver->table(self::TABLE_SITE_EVENTS)->selectFirst(
            ["$type.$key" => 1],
            ["course.$type.$key" => ['$exists' => 1], 'userId' => $userId]
        );
    }

    public function addEvent($type, $key, $userId, $match = [])
    {
        $match['time'] = time();
        $match['open'] = false;

        $data = ['$set' => ["course.$type.$key" => $match]];
        return $this->driver->table(self::TABLE_SITE_EVENTS)->update($data, ['userId' => $userId]);
    }

    public function setChoosenBook($bookId, $courseName, $userId)
    {
        $data['course.getabstract.' . $courseName . '.name'] = $bookId;
        return $this->driver->table(self::TABLE_SITE_EVENTS)->update(['$set' => $data], ['userId' => $userId]);
    }
}
