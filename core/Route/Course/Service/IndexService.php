<?php

namespace Site\Route\Course\Service;

use Flame\Classes\Http\Exception\Error4xx;
use Flame\Classes\Http\Response\Html;
use Site\Common\Classes\User;

class IndexService
{
    private $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Проверяет существует ли такой курс, открыт ли он или это демо доступ.
     *
     * @param string $courseName Название курса
     * @param User $user Модель пользователя
     *
     * @return array|Html Html если доступ закрыт, array с данными
     *
     * @throws Error4xx Если курс не найден
     */
    public function checkRight($courseName, User $user)
    {
        $courseData = $this->courseService->getCourseData($courseName);
        if (!$courseData) {
            throw new Error4xx('Error access denied. ' . __METHOD__);
        }

        $isDemoCategory = $this->courseService->isDemo('category', $courseName);

        // Получаем все открытые блоки для пользователя и если есть выбранную книгу
        $bookKey = 'course.' . CourseService::GET_ABSTRACT . '.' . $courseName;
        $openCourse = $this->courseService->getEventsByName(
            '',
            $user->getId(),
            [$bookKey => 1, 'course' => 1]
        );
        unset($bookKey);

        // Если грамматика курса не открыта, значит курс закрыт
        if (!isset($openCourse[CourseService::GRAMMAR][$courseName]) && !$isDemoCategory) {
            die('Not open yet');
            return new Html();
        }

        return ['courseData' => $courseData, 'openCourse' => $openCourse];
    }
}
