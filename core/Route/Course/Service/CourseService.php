<?php

namespace Site\Route\Course\Service;

use Flame\Classes\Model\Collection;
use Site\Route\Course\Dao\CourseDao;
use Site\Route\Course\Model\QuestionAnswer;

class CourseService
{
    private $courseDao;
    private $materialService;

    const GRAMMAR = 'grammar';
    const PENDULUM = 'pendulum';
    const TRASH_MISTAKE = 'trashMistake';
    const QUESTION_ANSWER = 'question';
    const VIDEO = 'video';
    const GET_ABSTRACT = 'getabstract';
    const CARD = 'verbs';
    const SPEAKING = 'speaking';
    const EXAM = 'exam';
    const CHECK = 'check';

    /**
     * @param CourseDao $courseDao
     */
    public function __construct(CourseDao $courseDao, MaterialService $materialService)
    {
        $this->courseDao = $courseDao;
        $this->materialService = $materialService;
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
     * @param string $courseName Название курса
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
     *
     * @return array
     */
    public function getTrashMistakeData($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->courseDao->getTrashMistakeData($groupName);
        if (!$data) {
            return [];
        }

        array_walk($data['list'], function (&$item) {
            $item['wrongText'] = preg_replace_callback('/\#([^#]+)\#(\d+#)?/', function ($matches) {
                $input = '<input type="text" name="text" class="select-text" value="' . $matches[1] . '"';

                $size = isset($matches[2]) ? $matches[2] : null;
                if ($size) {
                    $size = trim($size, '#');
                    $input .= ' size="' . $size . '" maxlength="' . $size . '"';
                }
                return $input . '/>';
            }, $item['wrongText']);
        });

        return $data;
    }

    /**
     * @param $courseName
     * @return array
     */
    public function getCourseData($courseName)
    {
        /** @var array $data */
        $data = $this->courseDao->getCourseData($courseName);
        if (!$data) {
            return [];
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

        return new Collection($data, new QuestionAnswer());
    }

    public function getPendulumList($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->courseDao->getPendulumList($groupName);
        if (!$data) {
            return null;
        }

        return $data['list'];
    }

    public function getOpenCategory($courseData, $openCourse, $courseName)
    {
        $typeList = [
            self::PENDULUM,
            self::TRASH_MISTAKE,
            self::QUESTION_ANSWER,
            // self::GET_ABSTRACT,
            self::SPEAKING,
            self::CARD,
            self::VIDEO,
            self::EXAM
        ];

        if (isset($openCourse[self::GET_ABSTRACT][$courseName])) {
            $blockName = $openCourse[self::GET_ABSTRACT][$courseName]['name'];
            /*$bookInfo = $this->materialService->getBookInfo($blockName);*/

            $courseData['data'][self::GET_ABSTRACT] = [
                $blockName => [
                    'name' => 'none' // $bookInfo['title']
                ]
            ];
        }

        foreach ($typeList as $type) {
            foreach ($courseData['data'][$type] as $key => &$item) {
                if ($this->isDemo($type, $key)) {
                    $item = ['info' => $item, 'isInit' => true, 'isOpen' => true];
                    continue;
                }

                $isOpen = isset($openCourse[$type][$key]);
                $item = ['info' => $item, 'isInit' => $isOpen];
                if ($isOpen) {
                    $item['isOpen'] = $openCourse[$type][$key]['open'];
                }
            }
        }
        unset($typeList);

        return $courseData;
    }

    /**
     * @param string $name
     * @param string $userId
     * @param array $fields
     * @return array|null
     */
    public function getEventsByName($name, $userId, $fields = [])
    {
        $list = $this->courseDao->getEventsByName($name, $userId, $fields);
        if (!$list) {
            return [];
        }

        return array_map(function ($item) {
            return $item;
        }, $list['course']);
    }

    public function getCourseName($groupName, $blockName)
    {
        $data = $this->courseDao->getCourseName($groupName, $blockName);
        if (!$data) {
            return null;
        }
        return $data['url'];
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
                // [self::GET_ABSTRACT, 'eat-that-frog']
            ]
        );
    }

    /*public function addGrammarEvent($courseName, $userId)
    {
        $this->courseDao->nextLevel($courseName, $userId);
    }*/

    /**
     * Открывает новый уровень в курсе
     *
     * @param string $type Тип курса
     * @param string $key Название курса
     * @param string $userId UserId
     * @return bool Результат открытия
     *
     * $courseService->openNextLevel(CourseService::GRAMMAR, $courseName, $user->getId());
     */
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

    public function setChoosenBook($bookId, $courseName, $userId)
    {
        $this->courseDao->setChoosenBook($bookId, $courseName, $userId);
    }
}
