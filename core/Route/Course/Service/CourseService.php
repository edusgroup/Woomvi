<?php

namespace Site\Route\Course\Service;

use Flame\Classes\Model\Collection;
use Site\Route\Course\Dao\CourseDao;
use Site\Route\Course\Model\CourseCard;
use Site\Route\Course\Model\CourseData;
use Site\Route\Course\Model\CourseItem;
use Site\Route\Course\Model\OpenCourse;
use Site\Route\Course\Model\OpenGetAbstractCourse;
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

        $cardList = array_map(function ($cardItem) {
            if ($cardItem['name'] === 'getabstract') {
                return new CourseCard($cardItem['name'], '', '');
            }
            return new CourseCard($cardItem['name'], $cardItem['url'], $cardItem['caption']);
        }, $data['data']);

        $data = new CourseItem(
            $data['url'],
            $data['id'],
            $data['name'],
            $cardList
        );

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

    /**
     * Получаем список открытых карточек модуля
     *
     * @param CourseData $courseData Данные модуля
     * @param string $courseName Название курса
     * @return mixed
     */
    public function getOpenCategory(CourseData $courseData, $courseName)
    {
        $openedCourse = $courseData->getOpenCourse();

        foreach ($courseData->getCourseData()->getCourseCardList() as $item) {
            // Если это демо доступ, то он всегда открыт
            if ($this->isDemo($item->getName(), $item->getUrl())) {
                $item->setOpenStatus(true);
                continue;
            }

            $isOpen = false;
            if (isset($openedCourse[$item->getName()])) {
                $openItem = $openedCourse[$item->getName()];

                if ($openItem instanceof OpenGetAbstractCourse) {
                    $isOpen = true;
                    $item->setUrl($openItem->getBookName());
                } else {
                    $isOpen = $openedCourse[$item->getName()]->isInArray($item->getUrl());
                }
            }

            $item->setOpenStatus($isOpen);
        }

        return $courseData->getCourseData();
    }

    public function getEventListByName($groupName, $courseType, $userInnerId)
    {
        $name = $groupName . '.' . $courseType;
        $list = $this->courseDao->getEventsByName($name, $userInnerId);
        if (!$list || !isset($list[$groupName][$courseType])) {
            return [];
        }

        return $list[$groupName][$courseType];
    }

    /**
     * @param string $groupName
     * @param string $userInnerId
     * @param array $fields
     * @return OpenCourse[]|OpenGetAbstractCourse[]
     */
    public function getAllEventsByGroupName($groupName, $userInnerId)
    {
        $list = $this->courseDao->getEventsByName($groupName, $userInnerId);
        if (!$list) {
            return [];
        }

        $list = $list[$groupName];

        $result = [];
        foreach ($list as $key => $item) {
            if ($key === self::GET_ABSTRACT) {
                $result[$key] = new OpenGetAbstractCourse($key, $item);
                continue;
            }
            $result[$key] = new OpenCourse($key, $item);
        }

        return $result;
    }

    public function getCourseGroupName($courseType, $itemName)
    {
        $data = $this->courseDao->getCourseGroupName($courseType, $itemName);
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
                // [self::VIDEO, 'christmas-tree'],
                ['category', 'be-have'],
                [self::GET_ABSTRACT, 'eat-that-frog']
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
    /*public function openNextLevel($type, $key, $userId)
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
    }*/

    public function setChoosenBook($bookId, $courseName, $userId)
    {
        $this->courseDao->setChoosenBook($bookId, $courseName, $userId);
    }
}
