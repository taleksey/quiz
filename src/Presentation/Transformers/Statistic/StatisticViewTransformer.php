<?php

declare(strict_types=1);

namespace App\Presentation\Transformers\Statistic;

use App\Domain\Customer\Entity\Customer;
use App\Domain\Statistics\Entity\QuizStatistic as QuizStatisticDomain;

class StatisticViewTransformer
{
    /**
     * @param array<int, QuizStatisticDomain> $statistics
     * @return array<int, array<string, string>>
     */
    public function transform(array $statistics): array
    {
        return array_map(static function (QuizStatisticDomain $statistic) {
            return [
                'firstName' => $statistic->getCustomer()->getFirstName(),
                'lastName' => $statistic->getCustomer()->getLastName(),
            ];
        }, $statistics);
    }

    /**
     * @param Customer $customer
     * @return array<string, string>
     */
    public function transformCustomer(Customer $customer): array
    {
        return [
            'firstName' => $customer->getFirstName(),
            'lastName' => $customer->getLastName(),
        ];
    }
}
