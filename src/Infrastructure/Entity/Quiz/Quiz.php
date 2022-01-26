<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Quiz;

use App\Domain\Quiz\Entity\Question as QuestionDomain;
use App\Infrastructure\Entity\Timestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Quiz\Entity\Quiz as QuizDomain;
use DateTimeInterface;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "quizzes")]
class Quiz extends QuizDomain
{
    use Timestamps;

    /**
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 150)]
    private string $name;

    /**
     * @var boolean
     */
    #[ORM\Column(type: 'boolean', options: [
        "default" => 0
    ])]
    private bool $active = false;

    #[ORM\Column(type: 'string', length: 180)]
    private string $email;

    /**
     * @var DateTimeInterface|null
     *
     */
    #[ORM\Column(name:"start_time", type: 'datetime', nullable: true)]
    private ?DateTimeInterface $startTime;

    /**
     * @var DateTimeInterface|null
     */
    #[ORM\Column(name:"end_time", type: 'datetime', nullable: true)]
    private ?DateTimeInterface $endTime;

    /**
     * @var Collection<int, QuestionDomain>
     */
    #[ORM\OneToMany(mappedBy: "quiz", targetEntity: "Question", cascade: ["persist"])]
    private Collection $questions;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: [
        "default" => 1
    ])]
    private int $queue;

    public function __construct()
    {
        parent::__construct();
        $this->questions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getStartTime(): ?DateTimeInterface
    {
        return $this->startTime;
    }

    /**
     * @param DateTimeInterface|null $startTime
     */
    public function setStartTime(?DateTimeInterface $startTime): void
    {
        $this->startTime = $startTime;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getEndTime(): ?DateTimeInterface
    {
        return $this->endTime;
    }

    /**
     * @param DateTimeInterface|null $endTime
     */
    public function setEndTime(?DateTimeInterface $endTime): void
    {
        $this->endTime = $endTime;
    }

    /**
     * @return ArrayCollection<int, QuestionDomain>|Collection<int, QuestionDomain>
     */
    public function getQuestions(): ArrayCollection|Collection
    {
        return $this->questions;
    }

    public function setQuestions(QuestionDomain $question): void
    {
        $this->questions->add($question);
    }

    /**
     * @return int
     */
    public function getQueue(): int
    {
        return $this->queue;
    }

    /**
     * @param int $queue
     */
    public function setQueue(int $queue): void
    {
        $this->queue = $queue;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
