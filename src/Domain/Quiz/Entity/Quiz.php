<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Quiz
{
    private int $id;

    private string $name;

    private bool $active = false;

    private string $email;

    /**
     * @var DateTimeInterface|null
     *
     */
    private ?DateTimeInterface $startTime;

    /**
     * @var DateTimeInterface|null
     */
    private ?DateTimeInterface $endTime;

    /**
     * @var Collection<int, Question>
     */
    private Collection $questions;

    private int $queue;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function getStartTime(): ?DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(?DateTimeInterface $startTime): void
    {
        $this->startTime = $startTime;
    }

    public function getEndTime(): ?DateTimeInterface
    {
        return $this->endTime;
    }

    public function setEndTime(?DateTimeInterface $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return ArrayCollection<int, Question>|Collection<int, Question>
     */
    public function getQuestions(): ArrayCollection|Collection
    {
        return $this->questions;
    }

    public function setQuestions(Question $question): void
    {
        $this->questions->add($question);
    }

    public function getQueue(): int
    {
        return $this->queue;
    }

    public function setQueue(int $queue): void
    {
        $this->queue = $queue;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
