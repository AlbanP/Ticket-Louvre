<?php
namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Service\CheckDate;

class CheckDateTest extends WebTestCase
{
    private $checkDate;

    public function setUp()
    {
        $this->checkDate = new CheckDate();
    }

    /**
    * @dataProvider dateFirstSemesterProvider
    */
    public function testCheckDateFirstSemester($dateVisit, $expected)
    {
        $today = new \DateTime('30-12-2017');
        $result = $this->checkDate->check($dateVisit, $today);

        $this->assertSame($expected, $result);
    }

    public function dateFirstSemesterProvider()
    {
        return [
            'Sunday' => [new \DateTime('31-12-2017'), false],
            'Tuesday'  => [new \DateTime('02-01-2018'), false],
            'Before today'  => [new \DateTime('29-12-2017'), false],
            'After 6 month'  => [new \DateTime('31-06-2018'), false],
            '1 january'  => [new \DateTime('01-01-2018'), false],
            'Monday easter' => [new \DateTime('02-04-2018'), false],
            '1 may'  => [new \DateTime('01-05-2018'), false],
            '8 may' => [new \DateTime('08-05-2018'), false],
            'Ascension' => [new \DateTime('10-05-2018'), false],
            'Pentecôte' => [new \DateTime('20-05-2018'), false]
        ];
    }

    /**
    * @dataProvider dateSecondSemesterProvider
    */
    public function testCheckDateSecondSemester($dateVisit, $expected)
    {
        $today = new \DateTime('30-6-2018');
        $result = $this->checkDate->check($dateVisit, $today);

        $this->assertSame($expected, $result);
    }

    public function dateSecondSemesterProvider()
    {
        return [
            '14 july' => [new \DateTime('14-07-2018'), false],
            'Toussaint'  => [new \DateTime('01-11-2018'), false],
            'Armistice'  => [new \DateTime('11-11-2018'), false],
            '5 december'  => [new \DateTime('05-12-2018'), false],
            '1 january'  => [new \DateTime('01-01-2019'), false]
        ];
    }
}