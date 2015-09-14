<?php

namespace Site\Route\Course\Service;

use Flame\Classes\Model\Collection;
use Flame\Classes\Utils\ArrayHelp;
use Site\Route\Course\Dao\MaterialDao;
use Site\Route\Course\Model\Card;
use Site\Route\Course\Model\CheckTest;
use Site\Route\Course\Model\Speaking;

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

    public function getBookInfo($absctractId)
    {
        $data = $this->materialDao->getBookInfo($absctractId);
        if (!$data) {
            return [];
        }

        return $data['list'][0];
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
        if (isset($openCourse[CourseService::GET_ABSTRACT])) {
            foreach ($openCourse[CourseService::GET_ABSTRACT] as $item) {
                $choosedCourse[] = $item['name'];
            }
        }

        $data = $this->materialDao->getAvailableBookList();
        if (!$data) {
            return [];
        }

        $data = array_filter($data['list'], function ($item) use ($choosedCourse) {
            return !in_array($item['id'], $choosedCourse);
        });

        return $data;
    }

    public function getSpeakingData($speakingId, $userLevelComplexity)
    {
        $data = $this->materialDao->getSpeakingData($speakingId, $userLevelComplexity);
        if (!$data) {
            return null;
        }
        $data = $this->levelComplexityMerge($data['list']);
        return new Collection($data, new Speaking());
    }

    public function getCardData($groupName, $userLevelComplexity)
    {
        $data = $this->materialDao->getCardData($groupName, $userLevelComplexity);
        if (!$data) {
            return null;
        }

        $data = $this->levelComplexityMerge($data['list']);
        return new Collection($data, new Card());
    }

    public function getCheckList($checkTestId, $userLevelComplexity)
    {
        $data = $this->materialDao->getCheckList($checkTestId, $userLevelComplexity);
        if (!$data) {
            return null;
        }

        $return['error-count'] = $data['error-count'][$userLevelComplexity];

        $data = $this->levelComplexityMerge($data['list']);
        $data = (new ArrayHelp())->shuffleAssoc($data);

        $return['list'] = new Collection($data, new CheckTest());

        return $return;
    }

    private function levelComplexityMerge($listFull)
    {
        $result = [];
        foreach ($listFull as $itemList) {
            $result = array_merge($result, $itemList);
        }

        return $result;
    }
}
