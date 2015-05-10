<?php

namespace Site\Route\Course\Dao;

class MaterialDao
{
    const TABLE_SITE_VIDEO = 'siteVideo';
    const TABLE_SITE_GETABSCTRACT = 'siteGetAbstract';
    const TABLE_SITE_CARD = 'siteCard';
    const TABLE_SITE_SPEAKING = 'siteSpeaking';

    public $driver;

    /**
     * @param \Flame\Abstracts\Db\Driver $driver
     */
    public function __construct($driver)
    {
        $this->driver = $driver;
    }

    public function getVideoData($videoId)
    {
        return $this->driver->table(self::TABLE_SITE_VIDEO)->selectFirst([], ['id' => $videoId]);
    }

    public function getGetAbstractData($abstractId)
    {
        return $this->driver->table(self::TABLE_SITE_GETABSCTRACT)->selectFirst([], ['id' => $abstractId]);
    }

    public function getCardData($cardId)
    {
        return $this->driver->table(self::TABLE_SITE_CARD)->selectFirst([], ['id' => $cardId]);
    }

    public function getSpeakingData($speaingId)
    {
        return $this->driver->table(self::TABLE_SITE_SPEAKING)->selectFirst([], ['id' => $speaingId]);
    }

}