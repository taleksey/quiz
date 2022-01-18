<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "questions")]
class Question
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
    #[ORM\Column(type: 'string', length: 255)]
    private string $text;

    #[ORM\ManyToOne(targetEntity: "Quiz", inversedBy:"questions")]
    #[ORM\JoinColumn(name: "quiz_id", referencedColumnName: "id")]
    private Quiz $quiz;

    /**
     * @var Collection<int, Answer>
     */
    #[ORM\OneToMany(mappedBy: "question", targetEntity: "Answer", cascade: ["persist"])]
    private Collection $answers;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: [
        "default" => 1
    ])]
    private int $queue;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getQuiz()
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     */
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

    /**
     * @param Answer $answer
     */
    public function setAnswer(Answer $answer): void
    {
        $this->answers->add($answer);
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
