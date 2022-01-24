<?php

namespace App\Tests\Functional\Controller;

use App\Tests\Data\TestCustomer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationAndLoginControllerTest extends WebTestCase
{
    private const USER_EMAIL = 'new_customer@example.com';
    private const USER_PASSWORD = 'NewPassword';
    private const USER_NICKNAME = 'newCustomerTest';

    public function testLoginWithBadCorrectUser(): void
    {
        $testCustomer = new TestCustomer();
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            'email' => $testCustomer->getEmail(),
            'password' => 'Fake'
        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();
        $messageWithTextThatTokenIsWrong = $crawler->filterXPath('//div[contains(@class, "alert alert-danger")]');

        $this->assertGreaterThan(0, $messageWithTextThatTokenIsWrong->count());
        $this->assertStringContainsString('Invalid', $messageWithTextThatTokenIsWrong->text());
    }


    public function testRegistrationCustomer(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/registration');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form([
            'registration_form[nickName]' => self::USER_NICKNAME,
            'registration_form[firstName]' => 'First',
            'registration_form[lastName]' => 'Last',
            'registration_form[email]' => self::USER_EMAIL,
            'registration_form[plainPassword]' => self::USER_PASSWORD,
            'registration_form[agreeTerms]' => 1

        ]);
        $client->submit($form);
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('/login', $crawler->getUri());
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $this->assertEquals(0, $buttonCrawlerNode->count());
    }
}
