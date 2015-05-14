<?php

namespace Site\Route\Course\Dao;

class CourseDao extends \Flame\Abstracts\Db\Dao
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
     * @param $courseName Название файла
     * @return array Массив с ID по файлу
     */
    public function getGrammarFile($courseName)
    {
        return $this->driver->table('courses')->selectFirst(['id'], ['url' => $courseName]);
    }

    /**
     * Получаем список вопросов по TrashMistake
     *
     * @param string $groupName название группы вопросов
     */
    public function getTrashMistakeData($groupName)
    {
        return $this->driver->table(self::TABLE_SITE_TRASH_MISTAKE)->selectAll([], ['group' => $groupName]);
    }

    public function getCourseData($courseName)
    {
        return $this->driver->table(self::TABLE_SITE_COURSE)->selectFirst([], ['url' => $courseName]);
    }

    public function getQuestionList($groupName)
    {
        return $this->driver->table(self::TABLE_SITE_QUESTION)->selectAll([], ['group' => $groupName]);
    }

    public function getPendulumList($groupName)
    {
        return $this->driver->table(self::TABLE_SITE_PENDULUM)->selectAll([], ['group' => $groupName]);
    }

    public function getEventsByName($name, $userId, $fields = [])
    {   $name = $name ? '.'.$name : '';
        return $this->driver->table(self::TABLE_SITE_EVENTS)->selectFirst(
            $fields,
            ['course' . $name => ['$exists' => true], 'userId' => $userId]
        );
    }
}