<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class RegistrationAndLoginControllerTest extends WebTestCase
{
    private const USER_EMAIL = 'new_customer@example.com';
    private const USER_PASSWORD = 'NewPassword';
    private const USER_NICKNAME = 'newCustomerTest';

    public function testLoginWithBadCorrectUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            'email' => self::USER_EMAIL,
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
        $this->registration($client, [
            'nickName' => self::USER_NICKNAME,
            'firstName' => 'First',
            'lastName' => 'Last',
            'email' => self::USER_EMAIL,
            'plainPassword' => self::USER_PASSWORD,
        ]);
        $crawler = $client->followRedirect();
        $this->assertStringContainsString('/login', $crawler->getUri());

        $this->login($client, $crawler, [
            'email' => self::USER_EMAIL,
            'password' => self::USER_PASSWORD
        ]);
        $crawler = $client->request('GET', '/quiz/new');

        $headerLinks = $crawler->filter('.nav-link')->each(function (Crawler $crawler){
            return $crawler->attr('href');
        });
        $this->assertContains('/logout', $headerLinks);
    }

    /**
     * @param KernelBrowser $client
     * @param array<string, string> $rawCustomers
     * @return void
     */
    public function registration(KernelBrowser $client, array $rawCustomers): void
    {
        $crawler = $client->request('GET', '/registration');
        $buttonCrawlerNode = $crawler->selectButton('Register');
        $form = $buttonCrawlerNode->form([
            'registration_form[nickName]' => $rawCustomers['nickName'],
            'registration_form[firstName]' => $rawCustomers['firstName'],
            'registration_form[lastName]' => $rawCustomers['lastName'],
            'registration_form[email]' => $rawCustomers['email'],
            'registration_form[plainPassword]' => $rawCustomers['plainPassword'],
            'registration_form[agreeTerms]' => 1
        ]);
        $client->submit($form);
    }

    /**
     * @param KernelBrowser $client
     * @param Crawler $crawler
     * @param array<string, string> $rawCustomer
     * @return void
     */
    public function login(KernelBrowser $client, Crawler $crawler, array $rawCustomer): void
    {
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            'email' => $rawCustomer['email'],
            'password' => $rawCustomer['password']
        ]);
        $client->submit($form);
    }
}
