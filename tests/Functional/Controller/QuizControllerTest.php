<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

class QuizControllerTest extends WebTestCase
{
    public function testHomepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $createdNameQuizzes = ['Ocean', 'Geography'];
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'There are list quizzes.');

        $listTdElementsWithQuizzes = $crawler->filter('table.table-success')->filter('tr')->each(function (Crawler $tr){
            return $tr->filter('td')->each(function ($td) {
                return $td->text();
            });
        });

        $nameQuizzes = (array_column(array_filter($listTdElementsWithQuizzes), 0));
        $this->assertEquals($nameQuizzes, $createdNameQuizzes);

    }
}
