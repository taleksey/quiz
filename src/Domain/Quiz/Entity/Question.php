<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Question
{
    private int $id;

    private string $text;

    private Quiz $quiz;

    /**
     * @var Collection<int, Answer>
     */
    private Collection $answers;

    private int $queue;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function setAnswer(Answer $answer): void
    {
        $this->answers->add($answer);
    }

    public function getQueue(): int
    {
        return $this->queue;
    }

    public function setQueue(int $queue): void
    {
        $this->queue = $queue;
    }
}
