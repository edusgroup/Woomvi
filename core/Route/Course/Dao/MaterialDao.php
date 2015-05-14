<?php

namespace Site\Route\Course\Dao;

class MaterialDao extends \Flame\Abstracts\Db\Dao
{
    const TABLE_SITE_VIDEO = 'siteVideo';
    const TABLE_SITE_GETABSCTRACT = 'siteGetAbstract';
    const TABLE_SITE_CARD = 'siteCard';
    const TABLE_SITE_SPEAKING = 'siteSpeaking';

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