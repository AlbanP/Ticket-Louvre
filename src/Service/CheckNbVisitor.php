<?php
namespace App\Service;

use App\Repository\CalendarRepository;

class CheckNbVisitor
{
    private $calendarRepository;
    private $maxVisitorDay = 1000;

    public function __construct(CalendarRepository $calendarRepository)
    {
        $this->calendarRepository = $calendarRepository;
    }

    public function calculNbVisitorDay($dateVisit, $nbVisitor)
    {
        if (! $nbVisitor) $nbVisitor = 0;

        $calendar = $this->calendarRepository->findOneByDay($dateVisit);

        $nbVisitorDay = $this->maxVisitorDay - $nbVisitor;
        if ($calendar) $nbVisitorDay = $nbVisitorDay - $calendar->getNbVisitor();

        return $nbVisitorDay;
    }
}