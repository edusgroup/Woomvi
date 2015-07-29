<?php

namespace Site\Route\Course\Dao;

use Flame\Abstracts\Db\Dao;

class MaterialDao extends Dao
{
    const TABLE_SITE_VIDEO = 'siteVideo';
    const TABLE_SITE_TESTING_LIST = 'siteTestingList';
    const TABLE_SITE_GET_ABSTRACT = 'siteGetAbstract';
    const TABLE_SITE_CARD = 'siteCard';
    const TABLE_SITE_SPEAKING = 'siteSpeaking';

    /**
     *
     *
     * @param string $videoId Id
     *
     * @return array
     */
    public function getVideoData($videoId)
    {
        return $this->driver->table(self::TABLE_SITE_VIDEO)->selectFirst([], ['id' => $videoId]);
    }

    public function getTestList($testId, $group)
    {
        return $this->driver
            ->table(self::TABLE_SITE_TESTING_LIST)
            ->selectFirst([], ['_id' => $testId, 'group' => $group]);
    }

    /**
     * Получаем все доступные книги для абстрактов
     *
     * @param array $choosedCourse
     */
    public function getAvailableBookList($choosedCourse)
    {
        return $this->driver->table(self::TABLE_SITE_GET_ABSTRACT)->selectAll(
            [],
            ['id' => ['$nin' => $choosedCourse]]
        );
    }

    /**
     *
     *
     * @param string $abstractId ID
     *
     * @return array
     */
    public function getGetAbstractData($abstractId)
    {
        return $this->driver->table(self::TABLE_SITE_GET_ABSTRACT)->selectFirst([], ['id' => $abstractId]);
    }

    public function getCardData($cardId)
    {
        return $this->driver->table(self::TABLE_SITE_CARD)->selectFirst([], ['id' => $cardId]);
    }

    public function getSpeakingData($speakingId)
    {
        return $this->driver->table(self::TABLE_SITE_SPEAKING)->selectFirst([], ['id' => $speakingId]);
    }

}
