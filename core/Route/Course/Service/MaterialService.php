<?php

namespace Site\Route\Course\Service;

use Site\Route\Course\Dao\MaterialDao;

class MaterialService
{
    private $materialDao;

    /**
     * @param MaterialDao $materialDao
     */
    public function __construct($materialDao)
    {
        $this->materialDao = $materialDao;
    }

    /**
     * Получаем информацию по видео
     *
     * @param string $videoId Id видео
     *
     * @return null|string Информация по видео или null, если ни чего не нашлось
     */
    public function getVideoData($videoId)
    {
        $data = $this->materialDao->getVideoData($videoId);
        if (!$data) {
            return [];
        }

        return $data;
    }

    public function getTestList($videoId, $group)
    {
        $data = $this->materialDao->getTestList($videoId, $group);
        if (!$data) {
            return [];
        }

        return $data['list'];
    }

    public function getGetAbstractData($absctractId)
    {
        $data = $this->materialDao->getGetAbstractData($absctractId);
        if (!$data) {
            return [];
        }

        return $data;
    }

    public function getCardData($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->materialDao->getCardData($groupName);
        if (!$data) {
            return [];
        }

        return $data;
    }

    /**
     * Получаем все доступные книги для абстрактов
     *
     * @param array $openCourse
     *
     * @return array Список книг
     */
    public function getAvailableBookList($openCourse)
    {
        $choosedCourse = [];
        foreach ($openCourse[CourseService::GET_ABSTRACT] as $item) {
            $choosedCourse[] = $item['name'];
        }

        $data = $this->materialDao->getAvailableBookList($choosedCourse);
        if (!$data) {
            return [];
        }

        return iterator_to_array($data);
    }

    public function getSpeakingData($speakingId)
    {
        /** @var \MongoCursor $data */
        $data = $this->materialDao->getSpeakingData($speakingId);
        if (!$data) {
            return [];
        }

        return $data;
    }
}
