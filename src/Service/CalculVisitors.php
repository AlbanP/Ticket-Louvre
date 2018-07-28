<?php
namespace App\Service;

use App\Entity\Ticket;
//use App\Entity\Calendar;

class CalculVisitors
{

    public function getCalculVisitors(Ticket $ticket)
    {
        $visitors = $ticket->getVisitors();
        $dateVisit = $ticket->getDateVisit();
        $totalPrice = 0;
        $nbVisitor = 0;
        // Define for each visitor the rate and calul the number of visitor and total price
        foreach ($visitors->toArray() as $visitor) {
            $dateVisit = $ticket->getDateVisit();
            $birthday = $visitor->getBirthday();
            $reduction = $visitor->getReduction();
            //Calcul Rate
            $price = $this->calculRate($birthday, $reduction, $dateVisit);
            $visitor->setRate($price);
            // Update visitor to Ticket (add id ticket to Visitor )
            $ticket->updateVisitor($visitor);
            // Calcul ticket total price and increase number visitor
            $totalPrice += $visitor->getRate();
            $nbVisitor++;
        }
        // Save to ticket number visitor and total price
        $ticket->setNbVisitor($nbVisitor);
        $ticket->setPrice($totalPrice);

        return $ticket;
    }

    public function calculRate($birthday, $reduction, $dateVisit)
    {
        // Diff interval dateVisit-datebirthday
        $dateDiff = $dateVisit->diff($birthday);
        $yearDiff = $dateDiff->format('%y');
        $monthDiff = $dateDiff->format('%m');
        $dayDiff = $dateDiff->format('%d');
        $dateDiff = $yearDiff + ($monthDiff+$dayDiff)/100;
        // Calcul rate
        // Less and equal 4 years
        if ($dateDiff <= 4) {
            $price = 0;
        // Beetwen 4 and equal 12 years
        } elseif ($dateDiff <= 12) {
            $price = 800;
        // If reduction
        } elseif ($reduction == true) {
            $price = 1000;
        // More and equal 60 years
        } elseif ($dateDiff >= 60) {
            $price = 1200;
        // Standart price
        } else {
            $price = 1600;
        }

        return $price;
    }

}