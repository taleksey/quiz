<?php

declare(strict_types=1);

namespace App\Domain\Quiz\ValueObject;

class QuizResult
{
    private const DEFAULT_DATE_TIME = '2020-01-01 00:00:00';

    private \DateTime $startDate;

    /**
     * @var array<int, QuizAnswer>
     */
    private array $answers;

    private string $rawStartDate;

    /**
     * @param array<int|string, bool|string> $result
     */
    public function __construct(array $result)
    {
        $this->rawStartDate = $result['startDate'] ?? self::DEFAULT_DATE_TIME;

        try {
            $dateTime = new \DateTime($this->rawStartDate);
        } catch (\Exception) {
            $dateTime = new \DateTime('NOW');
        }
        $this->startDate = $dateTime;

        unset($result['startDate']);

        $answers = array_map(static function (int $key, bool $answer) {
            return new QuizAnswer($key, $answer);
        }, array_keys($result), $result);

        $this->answers = $answers;
    }

    public function getStartDate(): \DateTime
    {
        return $this->startDate;
    }

    /**
     * @return array<int, QuizAnswer>
     */
    public function getAnswers(): array
    {
        return $this->answers;
    }

    public function isAnswers(): bool
    {
        return ! empty($this->getAnswers());
    }

    public function getTotalCorrectAnswers(): int
    {
        $correctAnswers = array_filter($this->getAnswers(), static function ($answer) {
            return $answer->isCorrectAnswer();
        });

        return count($correctAnswers);
    }

    public function getLastPosition(): int
    {
        if (! $this->isAnswers()) {
            return 0;
        }

        $answers = $this->getAnswers();

        usort($answers, static function (QuizAnswer $first, QuizAnswer $second) {
            return $first->getPosition() <=> $second->getPosition();
        });

        $lastAnswer = end($answers);

        return $lastAnswer->getPosition();
    }

    /**
     * @return array <int|string, string|bool>
     */
    public function toArray(): array
    {
        $array = [
            'startDate' => $this->rawStartDate,
        ];

        foreach ($this->getAnswers() as $answer) {
            $array[$answer->getPosition()] = $answer->isCorrectAnswer();
        }

        return $array;
    }
}
