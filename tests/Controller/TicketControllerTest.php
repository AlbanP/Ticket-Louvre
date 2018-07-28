<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketControllerTest extends WebTestCase
{
    /**
     * @test 
    */
    public function testIndexBadDate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->filter('.calendar-form')->form();
        $form['date-select'] = '01-01-2018';
        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();


        $this->assertSame('Bad date, please choose another date', $crawler->filter('.error-card')->text());
    }
}