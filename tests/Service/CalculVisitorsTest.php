<?php
namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\CalculVisitors;
use App\Entity\Ticket;

class CalculVisitorsTest extends WebTestCase
{
    private $calculVisitor;

    public function setUp()
    {
        $this->calculVisitors = new CalculVisitors();
    }

    /**
    * @dataProvider rateProvider
    */
    public function testCalculRate($birthday, $reduction, $expected)
    {
        $dateVisit = new \DateTime('30-07-2018');
        $result = $this->calculVisitors->calculRate($birthday, $reduction, $dateVisit);

        $this->assertSame($expected, $result);
    }

    public function rateProvider()
    {
        return [
            'Standard'  => [new \DateTime('30-07-1973'), false, 1600],
            'Child'  => [new \DateTime('30-07-2010'), false, 800],
            'Senior'  => [new \DateTime('30-07-1935'), false, 1200],
            'Reduction'  => [new \DateTime('30-07-1980'), true, 1000],
            'Free'  => [new \DateTime('30-07-2016'), false, 0]
        ];
    }
}