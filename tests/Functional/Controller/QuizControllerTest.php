<?php
declare(strict_types=1);
namespace App\Tests\Functional\Controller;

use App\Infractructure\Repository\Registration\CustomerRepository;
use App\Tests\Data\TestCustomer;
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

    /**
     * @return void
     * @throws DOMException
     */
    public function testCreateNewQuizWithMissingAnswers(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $this->authorize($client);
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'quiz[name]' => 'Test Quiz',
        ]);
        $dom = new DOMDocument('1.0', 'utf-8');
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][text]', 'First Question')));
        $client->submit($form);

        $this->assertResponseIsUnprocessable();
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
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'quiz[name]' => 'Test Quiz',
        ]);
        $dom = new DOMDocument('1.0', 'utf-8');
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][text]', 'First Question')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][0][correct]', '0', false)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][0][text]', 'First Answer')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][1][correct]', '1', false)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][1][text]', '')));

        $client->submit($form);

        $this->assertResponseIsUnprocessable();
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
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'quiz[name]' => 'Test Quiz',
        ]);
        $dom = new DOMDocument('1.0', 'utf-8');
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][text]', 'First Question')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][0][correct]', '0', false)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][0][text]', 'First Answer')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][1][correct]', '1', false)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][1][text]', 'Second Answer')));
        $client->submit($form);

        $this->assertResponseIsUnprocessable();
    }

    /**
     * @return void
     * @throws DOMException
     */
    public function testCreateNewQuizGood(): void
    {
        $client = static::createClient();
        $client = $this->loggedIn($client);
        $this->authorize($client);
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form([
            'quiz[name]' => 'Test Quiz',
        ]);
        $dom = new DOMDocument('1.0', 'utf-8');
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][text]', 'First Question')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][0][correct]', '0', false)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][0][text]', 'First Answer')));

        $form->set(new ChoiceFormField($this->createRadioInputElement($dom, 'quiz[questions][0][answers][1][correct]', '1', true)));
        $form->set(new InputFormField($this->createInputElement($dom, 'quiz[questions][0][answers][1][text]', 'Second Answer')));
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        $this->assertResponseRedirects('/quiz/created');
    }

    /**
     * @param DOMDocument $dom
     * @param string $name
     * @param string $value
     * @return DOMElement
     * @throws DOMException
     */
    private function createInputElement(DOMDocument $dom, string $name, string $value=''): DOMElement
    {
        $inputElement = $dom->createElement('input');
        $inputElement->setAttribute('name', $name);
        $inputElement->setAttribute('value', $value);

        return $inputElement;
    }

    /**
     * @param DOMDocument $dom
     * @param string $name
     * @param string $value
     * @param bool $checked
     * @return DOMElement
     * @throws DOMException
     */
    private function createRadioInputElement(DOMDocument $dom, string $name, string $value, bool $checked): DOMElement
    {
        $inputElement = $dom->createElement('input');
        $inputElement->setAttribute('type', 'radio');
        $inputElement->setAttribute('name', $name);
        $inputElement->setAttribute('value', $value);
        if ($checked) {
            $inputElement->setAttribute('checked', 'checked');
        }
        return $inputElement;
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
        $testCustomer = new TestCustomer();
        /** @var CustomerRepository $customerRepository */
        $customerRepository = static::getContainer()->get(CustomerRepository::class);
        $testUser = $customerRepository->getCustomerByEmail($testCustomer->getEmail());

        return $client->loginUser($testUser);
    }
}
