<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TicketAjaxControllerTest extends WebTestCase
{
    /*
    * @test
    * @group func_ticketAjaxController
    */
    public function testTicketAjaxControllerfail()
    {
        $client = static::createClient();

        $client->request('GET', '/calculRate');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertContains("Erreur : Is not Ajax request", $client->getResponse()->getContent());
    }
    /*
    * @test
    * @group func_ticketAjaxController
    */
    public function testTicketAjaxController()
    {
        $client = static::createClient();

        $client->request('POST', '/calculRate', ['birthday'=> '2000-01-01' , 'reduction'=> false, 'dateVisit'=> '2018-05-30', 'nbVisitor' => 1]);
        //var_dump($client->getResponse()->getContent());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('{"price":1600,"birthday":"2000-01-01T00:00:00+00:00","nbVisitorsDay":999}', $client->getResponse()->getContent());
    }
}