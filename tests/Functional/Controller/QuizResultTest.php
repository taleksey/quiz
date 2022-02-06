<?php

namespace App\Tests\Functional\Controller;

use App\Infrastructure\DataFixtures\QuizFixtures;
use App\Tests\DB\ResultInTopThreeData;
use App\Tests\DB\ResultNotInTopThreeData;
use App\Tests\DB\ResultOnForthPositionWithDifferentCorrectAnswersData;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;

class QuizResultTest extends WebTestCase
{
    private const USER_NICKNAME= 'quiz_test';
    private const USER_FIRSTNAME= 'QuizFirst';
    private const USER_LASTNAME= 'QuizLast';
    private const USER_EMAIL = 'quiz_test@example.com';
    private const USER_PASSWORD = '123456';
    /**
     * @var array<int, int>
     */
    private array $correctAnswersForOceanQuiz = [
        2, 1, 2, 1, 1
    ];

    public function testResultInTopThree(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
        $resultInTopThreeFixtures = new ResultInTopThreeData();
        $resultInTopThreeFixtures->load($entityManager);
        $crawler = $this->returnResultPage($client);
        $rawListFirstName = $crawler->filter('table.table-result')->filter('tr')->each(function (Crawler $tr) {
            $tdWithFirstName = $tr->filter('td')->eq(0);
            if($tdWithFirstName->count()) {
                return $tdWithFirstName->text();
            }
            return null;
        });
        $listFirstName = array_values(array_filter($rawListFirstName));
        $this->assertContains(self::USER_FIRSTNAME, $listFirstName);

        $key = array_search(self::USER_FIRSTNAME, $listFirstName);
        $this->assertEquals(1, $key);
    }


    public function testResultOnFifthPosition(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
        $resultNotInTopThreeData = new ResultNotInTopThreeData();
        $resultNotInTopThreeData->load($entityManager);
        $crawler = $this->returnResultPage($client);
        $lastRowInStatisticTable = $crawler->filter('table.table-result')->filter('tr')->last();
        $th = $lastRowInStatisticTable->filter('th');
        $position = (int) $th->text();
        $this->assertEquals(5, $position);
        $this->assertEquals(self::USER_FIRSTNAME, $lastRowInStatisticTable->filter('td')->eq(0)->text());
    }


    public function testResultOnForthPositionWithDifferentCorrectAnswers(): void
    {
        $client = static::createClient();
        $entityManager = static::getContainer()
            ->get('doctrine')
            ->getManager();
        $ResultOnForthPositionWithDifferentCorrectAnswersFixtures = new ResultOnForthPositionWithDifferentCorrectAnswersData();

        $ResultOnForthPositionWithDifferentCorrectAnswersFixtures->load($entityManager);
        $crawler = $this->returnResultPage($client);
        $lastRowInStatisticTable = $crawler->filter('table.table-result')->filter('tr')->last();
        $th = $lastRowInStatisticTable->filter('th');
        $position = (int) $th->text();
        $this->assertEquals(4, $position);
        $this->assertEquals(self::USER_FIRSTNAME, $lastRowInStatisticTable->filter('td')->eq(0)->text());
    }

    private function returnResultPage(KernelBrowser $client): Crawler
    {
        $crawler = $client->request('GET', '/');
        $listTdElementsWithQuizzes = $crawler->filter('table.table-success')->filter('tr')->each(function (Crawler $tr) {
            $first = $tr->filter('td')->first();
            if($first->count() && $first->text() === QuizFixtures::FIRST_QUIZ) {
                return $first->filter('a')->attr('href');
            }
            return null;
        });
        $linkOcean = array_values(array_filter($listTdElementsWithQuizzes))[0] ?? '';
        $this->assertNotEmpty($linkOcean);

        $registrationAndLoginControllerTest = new RegistrationAndLoginControllerTest();
        $registrationAndLoginControllerTest->registration($client, [
            'nickName' => self::USER_NICKNAME,
            'firstName' => self::USER_FIRSTNAME,
            'lastName' => self::USER_LASTNAME,
            'email' => self::USER_EMAIL,
            'plainPassword' => self::USER_PASSWORD,
        ]);

        $crawler = $client->request('GET', '/login');
        $registrationAndLoginControllerTest->login($client, $crawler, [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD
        ]);

        $client->request('GET', $linkOcean);
        $rawSteps = $linkOcean . '/question/';
        foreach ($this->correctAnswersForOceanQuiz as $key => $correctAnswerNumber) {
            $currentStep = $rawSteps . ($key + 1);
            $crawler = $client->request('GET', $currentStep);
            $buttonForm = $crawler->selectButton('Next');
            $form = $buttonForm->form();
            /**
             * @var string $formKey
             * @var ChoiceFormField $formField
             */
            foreach ($form->all() as $formKey => $formField) {
                $values = $formField->availableOptionValues();
                $correctValue = $values[$correctAnswerNumber - 1];
                $formField->setValue($correctValue);
            }
            $client->submit($form);
            sleep(1);
        }
        $resultStep = $linkOcean . '/result';

        return $client->request('GET', $resultStep);
    }
}
