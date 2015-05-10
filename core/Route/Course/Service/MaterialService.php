<?php

namespace Site\Route\Course\Service;

class MaterialService
{
    private $materialDao;

    /**
     * @param \Site\Route\Course\Dao\MaterialDao $materialDao
     */
    public function __construct($materialDao)
    {
        $this->materialDao = $materialDao;
    }

    /**
     * Получаем информацию по видео
     *
     * @param string $videoId Id видео
     * @return null|string Информация по видео или null, если ни чего не нашлось
     */
    public function getVideoData($videoId)
    {
        $data = $this->materialDao->getVideoData($videoId);
        if (!$data) {
            return null;
        }
        return $data;
    }

    public function getGetAbstractData($absctractId)
    {
        $data = $this->materialDao->getGetAbstractData($absctractId);
        if (!$data) {
            return null;
        }
        return $data;
    }

    public function getCardData($groupName)
    {
        /** @var \MongoCursor $data */
        $data = $this->materialDao->getCardData($groupName);
        if (!$data) {
            return null;
        }

        return $data;
    }

    public function getSpeakingData($speaingId)
    {
        /** @var \MongoCursor $data */
        $data = $this->materialDao->getSpeakingData($speaingId);
        if (!$data) {
            return null;
        }

        return $data;
    }


}
