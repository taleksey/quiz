<?php
declare(strict_types=1);
namespace App\Tests\Functional\Controller;

use App\Infrastructure\DB\Customer\Customer;
use App\Infrastructure\Manager\Customer\CustomerManager;
use App\Infrastructure\Repository\Registration\CustomerRepository;
use DOMDocument;
use DOMElement;
use DOMException;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Field\ChoiceFormField;
use Symfony\Component\DomCrawler\Field\InputFormField;
use Symfony\Component\HttpFoundation\Response;

class QuizControllerTest extends WebTestCase
{
    private const USER_FIRSTNAME = 'FirstName';
    private const USER_LASTNAME = 'LastName';
    private const USER_NICK_NAME = 'test';
    private const USER_EMAIL = 'test@example.com';
    private const USER_PASSWORD = 'TestPassword';
    /**
     * @var array<string, string>
     */
    private array $quiz = [
        'name' => 'Test Quiz',
    ];

    public function testHomepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'There are list quizzes.');
        $listTdElementsWithQuizzes = $crawler->filter('table.table-success')->filter('tr')->each(function (Crawler $tr) {
            return $tr->filter('td')->each(function ($td) {
                return $td->text();
            });
        });

        $nameQuizzes = (array_column(array_filter($listTdElementsWithQuizzes), 0));
        $this->assertContains('Ocean', $nameQuizzes);
        $this->assertContains('Geography', $nameQuizzes);
    }

    public function testUserDoesNotHaveAccessToQuizPage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/quiz/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $loginForm = $crawler->filterXPath('//form[contains(@id, "form_login")]');
        $this->assertEquals(1, $loginForm->count());
    }

    public function testCreateNewQuizWithOutAuthorization(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertEquals(0,  $buttonCrawlerNode->count());
        $messageWithTextWhyPageDoNotAvailable = $crawler->filterXPath('//p[@class="forbidden-message"]');
        $this->assertGreaterThan(0, $messageWithTextWhyPageDoNotAvailable->count());
    }

    public function testAuthorizationCustomerWithBadToken(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/auth');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'authorization[token]' => 'BadToken',
        ]);

        $client->submit($form);
        $this->assertResponseRedirects();
        $crawler = $client->followRedirect();
        $messageWithTextThatTokenIsWrong = $crawler->filterXPath('//div[contains(@class, "alert alert-secondary")]');
        $this->assertGreaterThan(0, $messageWithTextThatTokenIsWrong->count());
    }

        public function testAuthorizationCustomerWithGoodToken(): void
    {
        $client = static::createClient();
        $crawler = $this->authorize($client);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $messageWithTextThatTokenIsWrong = $crawler->filterXPath('//p[@class="authorization-success"]');
        $this->assertGreaterThan(0, $messageWithTextThatTokenIsWrong->count());
    }

    public function testCreateNewQuizWithMissingAnswers(): void
    {
        $client = static::createClient();

        $client = $this->loggedIn($client);
        $this->authorize($client);
        $quiz = $this->getQuizWithoutQuestions($client);
        $client = $client->jsonRequest('POST', '/quiz/create', $quiz);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $result = array_filter($client->filter('html > body > p')->extract(['_text']), static function($pHtmlElement) {
            return str_contains($pHtmlElement, 'ERROR: Set questions');
        });

        $this->assertCount(1, $result);
    }

    /**
     * @return void
     * @throws DOMException
     */
    public function testCreateNewQuizWithMissingAnswersValue(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $this->authorize($client);
        $quiz = $this->getQuizWithMissingAnswersValue($client);
        $client = $client->jsonRequest('POST', '/quiz/create', $quiz);

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $result = array_filter($client->filter('html > body > p')->extract(['_text']), static function($pHtmlElement) {
            return str_contains($pHtmlElement, 'answers:') && str_contains($pHtmlElement, 'ERROR: This value should not be blank');
        });

        $this->assertCount(1, $result);
    }

    /**
     * @return void
     * @throws DOMException
     */
    public function testCreateNewQuizWithMissingSetCorrectAnswers(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $this->authorize($client);
        $quiz = $this->quizWithMissingSetCorrectAnswer($client);
        $client = $client->jsonRequest('POST', '/quiz/create', $quiz);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

        $result = array_filter($client->filter('html > body > p')->extract(['_text']), static function($pHtmlElement) {
            return str_contains($pHtmlElement, 'ERROR: You have to select correct answer');
        });

        $this->assertCount(1, $result);
    }

    public function testCreateNewQuizGood(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $this->authorize($client);

        $quiz = $this->getGoodQuizValues($client);
        $client = $client->jsonRequest('POST', '/quiz/create', $quiz);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $result = array_filter($client->filter('html > body > p')->extract(['_text']), static function($pHtmlElement) {
            return str_contains($pHtmlElement, 'OK');
        });

        $this->assertCount(1, $result);
    }

    private function authorize(KernelBrowser $client): Crawler
    {
        $crawler = $client->request('GET', '/auth');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'authorization[token]' => self::getContainer()->getParameter('app.authorizationKey'),
        ]);
        return $client->submit($form);
    }

    private function loggedIn(KernelBrowser $client): KernelBrowser
    {
        $customer = new Customer();
        $email = self::USER_EMAIL;
        $customer->setNickName(self::USER_NICK_NAME);
        $customer->setFirstName(self::USER_FIRSTNAME);
        $customer->setLastName(self::USER_LASTNAME);
        $customer->setEmail($email);
        $customer->setPassword(self::USER_PASSWORD);
        $customerManager = static::getContainer()->get(CustomerManager::class);
        $customerManager->create($customer);
        $testUser = $customerManager->getCustomerByEmail($email);

        return $client->loginUser($testUser);
    }

    private function getCsrfToken(KernelBrowser $client): string
    {
        $crawler = $client->request('GET', '/quiz/new');
        $scripts = array_values(array_filter($crawler->filterXPath('//script')->extract(['_text'])));
        foreach ($scripts as $script) {
            if(str_contains($script, 'window.csrfToken')) {
                $lines = array_values(array_filter(explode(PHP_EOL, $script)));
                foreach ($lines as $line) {
                    if (! str_contains($line, 'window.csrfToken')) {
                        continue;
                    }
                    $csrfToken = trim(explode('=', $line)[1]);
                    return trim($csrfToken, '";');
                }
            }
        }

        return '';
    }

    /**
     * @param KernelBrowser $client
     * @return array<string, string>
     */
    private function getQuizWithoutQuestions(KernelBrowser $client): array
    {
        $secretToken= $this->getContainer()->getParameter('app.secretToken');
        $quiz = $this->quiz;
        $quiz['token'] = $secretToken;
        $quiz['csrfToken'] = $this->getCsrfToken($client);

        return $quiz;
    }

    /**
     * @param KernelBrowser $client
     * @return array <string, string|array<string, string|array<int, array<string, string|bool>>>>
     */
    private function getQuizWithMissingAnswersValue(KernelBrowser $client): array
    {
        $quiz = $this->getGoodQuizValues($client);
        $quiz['questions'][0]['answers'][1]['text'] = '';

        return $quiz;
    }

    /**
     * @param KernelBrowser $client
     * @return array <string, string|array<string, string|array<int, array<string, string|bool>>>>
     */
    private function quizWithMissingSetCorrectAnswer(KernelBrowser $client): array
    {
        $quiz = $this->getGoodQuizValues($client);
        $quiz['questions'][0]['answers'][0]['correct'] = false;

        return $quiz;
    }

    /**
     * @param KernelBrowser $client
     * @return array <string, string|array<string, string|array<int, array<string, string|bool>>>>
     */
    private function getGoodQuizValues(KernelBrowser $client): array
    {
        $quiz = $this->getQuizWithoutQuestions($client);

        $quiz['questions'] = [
            [
                'text' => 'First Question',
                'answers' => [
                [
                    'text' => 'First Answer',
                    'correct' => true
                ],
                [
                    'text' => 'Second Answer',
                    'correct' => false
                ]]
            ]
        ];

        return $quiz;
    }
}
