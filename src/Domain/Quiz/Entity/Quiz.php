<?php

namespace App\Domain\Quiz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeInterface;
use Doctrine\ORM\PersistentCollection;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[Table(name: "quizzes")]
class Quiz
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

    /**
     * @var Customer
     */
    #[ORM\ManyToOne(targetEntity: "Customer", inversedBy:"quizzes")]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id")]
    private Customer $customer;

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
     * @var Collection
     */
    #[ORM\OneToMany(mappedBy: "quiz", targetEntity: "Question")]
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
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return PersistentCollection
     */
    public function getQuestions(): PersistentCollection
    {
        return $this->questions;
    }

    /**
     * @param Question $question
     */
    public function setQuestions(Question $question): void
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
}
