<?php

namespace App\Domain\Quiz\ValueObject;

class QuestionStep
{
    /**
     * @param int $step
     */
    public function __construct(
        private int $step
    ) {
    }

    /**
     * @return int
     */
    public function getStepId(): int
    {
        return $this->step;
    }

    /**
     * @return bool
     */
    public function isFirstStep(): bool
    {
        return 1 === $this->step;
    }

    /**
     * @return int
     */
    public function getPrevStepId(): int
    {
        $prevStep = $this->step - 1;

        return (int) max($prevStep, 1);
    }

    /**
     * @return int
     */
    public function nextStepId(): int
    {
        return $this->step + 1;
    }

    /**
     * @param int $lastStep
     * @return bool
     */
    public function isFinalStep(int $lastStep): bool
    {
        return $lastStep === $this->step ||  $this->step > $lastStep;
    }

    /**
     * @return int
     */
    public function firstStepId(): int
    {
        return 1;
    }
}
