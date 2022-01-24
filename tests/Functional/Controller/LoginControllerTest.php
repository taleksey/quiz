<?php

namespace App\Tests\Functional\Controller;

use App\Infractructure\ValueObject\TestCustomer;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
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


    public function testLoginWithCorrectUser(): void
    {
        $testCustomer = new TestCustomer();
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $form = $buttonCrawlerNode->form([
            'email' => $testCustomer->getEmail(),
            'password' => $testCustomer->getPassword()
        ]);
        $client->submit($form);
        $crawler = $client->request('GET', '/quiz/new');
        $buttonCrawlerNode = $crawler->selectButton('Login');
        $this->assertEquals(0, $buttonCrawlerNode->count());
    }


}
