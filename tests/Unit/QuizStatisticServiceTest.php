<?php

namespace App\Tests\Unit;

use App\Domain\Statistics\Service\QuizStatisticService;
use App\Infrastructure\Manager\Statistic\StatisticManager;
use App\Presentation\Hydrator\Statistic\StatisticHydrator;
use PHPUnit\Framework\TestCase;

class QuizStatisticServiceTest extends TestCase
{
    /**
     * @var array<int, array<string, string|int>>
     */
    private array $checkTimes = [
        [
            'startDate' => '2021-01-01 00:00:00',
            'stopDate' => '2021-01-01 00:00:11',
            'subSeconds' => 11
        ],
        [
            'startDate' => '2020-01-01 00:00:00',
            'stopDate' => '2021-01-01 00:00:00',
            'subSeconds' => 31622400
        ],
        [
            'startDate' => '2000-01-01 00:00:00',
            'stopDate' => '2021-01-01 00:00:00',
            'subSeconds' => 662774400
        ],
        [
            'startDate' => '2000-01-17 00:00:00',
            'stopDate' => '2005-01-01 00:00:00',
            'subSeconds' => 156470400
        ],
        [
            'startDate' => '2021-01-17 00:00:00',
            'stopDate' => '2021-01-23 00:00:00',
            'subSeconds' => 518400
        ]
    ];

    public function testConvertToSeconds(): void
    {
        $statisticHydrator = $this->createMock(StatisticHydrator::class);

        $manager = $this->createMock(StatisticManager::class);
        $quizStatisticService = new QuizStatisticService($statisticHydrator, $manager);

        $quizStatisticServiceReflection = new \ReflectionClass(QuizStatisticService::class);
        $method = $quizStatisticServiceReflection->getMethod('convertToSeconds');
        $method->setAccessible(true);

        foreach ($this->checkTimes as $checkTime) {
            $startDate = new \DateTime($checkTime['startDate']);
            $stopDate = new \DateTime($checkTime['stopDate']);
            $sub = $stopDate->diff($startDate);
            $result = $method->invokeArgs($quizStatisticService, [$sub]);
            $this->assertEquals($checkTime['subSeconds'], $result);
        }
    }
}
