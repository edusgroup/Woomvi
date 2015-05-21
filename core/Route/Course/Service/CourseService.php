<?php

namespace Site\Route\Course\Service;


class CourseService
{
    private $courseDao;

    const GRAMMAR = 'grammar';
    const PENDULUM = 'pendulum';
    const TRASH_MISTAKE = 'trashMistake';
    const QUESTION_ANSWER = 'question';
    const VIDEO = 'video';
    const GET_ABSTRACT = 'getabstract';
    const CARD = 'verbs';
    const SPEAKING = 'speaking';
    const EXAM = 'exam';

    /**
     * @param \Site\Route\Course\Dao\CourseDao $courseDao
     */
    public function __construct($courseDao)
    {
        $this->courseDao = $courseDao;
    }

    /*public function getCategoryNum($categoryUrl)
    {
        $data = $this->courseDao->getCategoryNum($categoryUrl);
        if (!$data) {
            return null;
        }
        return $data['num'];
    }*/

    /**
     * Получаем имя файла с HTML, по объяснению грамматики
     *
     * @param string $courseName название курса
     *
     * @return null|string Имя файла или null, если ни чего не нашлось
     */
    public function getGrammarFile($courseName)
    {
        $data = $this->courseDao->getGrammarFile($courseName);
        if (!$data) {
            return null;
        }

        return 'grammar/' . chunk_split($data['id'], 2, '/') . 'data.txt';
    }

    /**
     * Получаем список вопросов по TrashMistake
     *
     * @param string $groupName название группы вопросов
     */
    public function getTrashMistakeData($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->courseDao->getTrashMistakeData($groupName);
        if (!$data) {
            return null;
        }

        return iterator_to_array($data);
    }

    public function getCourseData($courseName)
    {
        /** @var array $data */
        $data = $this->courseDao->getCourseData($courseName);
        if (!$data) {
            return null;
        }

        return $data;
    }

    public function getQuestionList($courseName)
    {
        /** @var array $data */
        $data = $this->courseDao->getQuestionList($courseName);
        if (!$data) {
            return null;
        }

        return $data;
    }

    public function getPendulumList($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->courseDao->getPendulumList($groupName);
        if (!$data) {
            return null;
        }

        return iterator_to_array($data);
    }

    public function getOpenCategory($courseData, $categoryList)
    {
        foreach ([
                     self::PENDULUM,
                     self::TRASH_MISTAKE,
                     self::QUESTION_ANSWER,
                     self::GET_ABSTRACT,
                     self::SPEAKING,
                     self::CARD,
                     self::VIDEO,
                     self::EXAM
                 ] as $type) {
            foreach ($courseData['data'][$type] as $key => &$item) {
                if ($this->isDemo($type, $key)) {
                    $item = ['info' => $item, 'isInit' => true, 'isOpen' => true];
                    continue;
                }

                $isOpen = isset($categoryList[$type][$key]);
                $item = ['info' => $item, 'isInit' => $isOpen];
                if ($isOpen) {
                    $item['isOpen'] = $categoryList[$type][$key]['open'];
                }
            }
        }

        return $courseData;
    }

    public function getEventsByName($name, $userId, $fields = [])
    {
        $list = $this->courseDao->getEventsByName($name, $userId, $fields);
        if (!$list) {
            return null;
        }

        return array_map(function ($item) {
            return $item;
        }, $list['course']);
    }

    public function isDemo($type, $key)
    {
        //return $key == 'be-have';
        return in_array(
            [$type, $key],
            [
                [self::GRAMMAR, 'be-have'],
                [self::VIDEO, 'christmas-tree'],
                ['category', 'be-have'],
                [self::GET_ABSTRACT, 'eat-that-frog']
            ]
        );
    }

    /*public function addGrammarEvent($courseName, $userId)
    {
        $this->courseDao->nextLevel($courseName, $userId);
    }*/

    public function openNextLevel($type, $key, $userId)
    {
        $item = $this->courseDao->getNextLevelItem($type, $key);
        if (!$item) {
            return false;
        }

        $newType = $item[$type][$key]['type'];
        $newKey = $item[$type][$key]['key'];

        $data = $this->courseDao->getEventDataByName($newType, $newKey, $userId);
        if (!$data) {
            $this->courseDao->addEvent($newType, $newKey, $userId, $match = []);
        }

        return true;
    }
}
