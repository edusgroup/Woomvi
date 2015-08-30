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
    const TABLE_SITE_CHECK = 'siteTestBlock';

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
    public function getAvailableBookList()
    {
        return $this->driver->table(self::TABLE_SITE_GET_ABSTRACT)->selectFirst(
            [],
            ['name' => 'list-of-book']
        );
    }

    /**
     *
     *
     * @param string $abstractId ID
     *
     * @return array
     */
    public function getBookInfo($abstractId)
    {
        return $this->driver->table(self::TABLE_SITE_GET_ABSTRACT)->selectFirst(
            ['list.id.$' => 1],
            ['list.id' => $abstractId]
        );
    }

    public function getCardData($cardId, $userLevelComplexity)
    {
        $level = $this->getLevel($userLevelComplexity);
        return $this->driver->table(self::TABLE_SITE_CARD)->selectFirst(
            $level,
            ['id' => $cardId]
        );
    }

    public function getCheckList($checkTestId, $userLevelComplexity)
    {
        $level = $this->getLevel($userLevelComplexity);
        $level['error-count.' . $userLevelComplexity] = true;
        return $this->driver->table(self::TABLE_SITE_CHECK)->selectFirst(
            $level,
            ['id' => $checkTestId]
        );
    }

    public function getSpeakingData($speakingId, $userLevelComplexity)
    {
        $level = $this->getLevel($userLevelComplexity);
        return $this->driver->table(self::TABLE_SITE_SPEAKING)->selectFirst(
            $level,
            ['id' => $speakingId]
        );
    }

    private function getLevel($level)
    {
        switch ($level) {
            case 1:
                return ["list.level-1" => true];
            case 2:
                return ["list.level-1" => true, "list.level-2" => true];
        }

        return [];
    }
}
