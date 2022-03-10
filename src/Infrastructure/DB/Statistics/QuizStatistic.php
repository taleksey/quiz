<?php

declare(strict_types=1);

namespace App\Infrastructure\DB\Statistics;

use App\Domain\Statistics\Entity\Customer as CustomerDomain;
use App\Domain\Statistics\Entity\Quiz as QuizDomain;
use App\Domain\Statistics\Entity\QuizStatistic as QuizStatisticDomain;
use App\Infrastructure\DB\Timestamps;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity, ORM\HasLifecycleCallbacks]
#[ORM\Table(name: "statistic")]
class QuizStatistic extends QuizStatisticDomain
{
    use Timestamps;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Customer::class, cascade: ["merge"])]
    #[ORM\JoinColumn(name: "customer_id", referencedColumnName: "id")]
    protected CustomerDomain $customer;

    #[ORM\ManyToOne(targetEntity: Quiz::class, cascade: ["merge"])]
    #[ORM\JoinColumn(name: "quiz_id", referencedColumnName: "id")]
    protected QuizDomain $quiz;

    #[ORM\Column(name: "total_correct_answers", type: "integer")]
    private int $totalCorrectAnswers;

    #[ORM\Column(type: "integer")]
    private int $totalQuestions;

    #[ORM\Column(type: "integer")]
    private int $spendSecondsQuiz;

    /**
     * @var array<int, string>
     */
    #[ORM\Column(type: 'json')]
    private array $rawAnswers = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCustomer(): CustomerDomain
    {
        return $this->customer;
    }

    public function setCustomer(CustomerDomain $customer): void
    {
        $this->customer = $customer;
    }

    public function getQuiz(): QuizDomain
    {
        return $this->quiz;
    }

    public function setQuiz(QuizDomain $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function getTotalCorrectAnswers(): int
    {
        return $this->totalCorrectAnswers;
    }

    public function setTotalCorrectAnswers(int $totalCorrectAnswers): void
    {
        $this->totalCorrectAnswers = $totalCorrectAnswers;
    }

    public function getTotalQuestions(): int
    {
        return $this->totalQuestions;
    }

    public function setTotalQuestions(int $totalQuestions): void
    {
        $this->totalQuestions = $totalQuestions;
    }

    /**
     * @return array<int, string>
     */
    public function getRawAnswers(): array
    {
        return $this->rawAnswers;
    }

    /**
     * @param array<int, string> $rawAnswers
     * @return void
     */
    public function setRawAnswers(array $rawAnswers): void
    {
        $this->rawAnswers = $rawAnswers;
    }

    /**
     * @return int
     */
    public function getSpendSecondsQuiz(): int
    {
        return $this->spendSecondsQuiz;
    }

    /**
     * @param int $spendSecondsQuiz
     */
    public function setSpendSecondsQuiz(int $spendSecondsQuiz): void
    {
        $this->spendSecondsQuiz = $spendSecondsQuiz;
    }
}
