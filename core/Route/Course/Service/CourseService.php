<?php

namespace Site\Route\Course\Service;


class CourseService
{
    private $courseDao;

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
}
