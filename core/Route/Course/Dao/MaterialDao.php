<?php

namespace Site\Route\Course\Dao;

use Flame\Abstracts\Db\Dao;

class MaterialDao extends Dao
{
    const TABLE_SITE_VIDEO = 'siteVideo';
    const TABLE_SITE_GET_ABSTRACT = 'siteGetAbstract';
    const TABLE_SITE_CARD = 'siteCard';
    const TABLE_SITE_SPEAKING = 'siteSpeaking';

    /**
     * Получаем данные по видео по ID
     *
     * @param string $videoId Id видео
     *
     * @return array Массив с данными
     */
    public function getVideoData($videoId)
    {
        return $this->driver->table(self::TABLE_SITE_VIDEO)->selectFirst([], ['id' => $videoId]);
    }

    /**
     * Получаем данные по GetAbstract по ID
     *
     * @param string $abstractId ID гетбастракта
     *
     * @return array Массив с данными
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
