<?php

declare(strict_types=1);

namespace App\Infrastructure\Entity\Quiz;

use App\Infrastructure\Entity\Timestamps;
use Doctrine\ORM\Mapping as ORM;
use App\Domain\Quiz\Entity\Answer as AnswerDomain;
use App\Domain\Quiz\Entity\Question as QuestionDomain;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "answers")]
class Answer extends AnswerDomain
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

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', options: [
        "default" => 0
    ])]
    private bool $correct;

    /**
     * @var QuestionDomain
     */
    #[ORM\ManyToOne(targetEntity: "Question", inversedBy:"answers")]
    private QuestionDomain $question;

    /**
     * @var int
     */
    #[ORM\Column(type: 'integer', options: [
        "default" => 1
    ])]
    private int $queue;

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
     * @return bool
     */
    public function isCorrect(): bool
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct): void
    {
        $this->correct = $correct;
    }

    public function getQuestion(): QuestionDomain
    {
        return $this->question;
    }

    public function setQuestion(QuestionDomain $question): void
    {
        $this->question = $question;
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
