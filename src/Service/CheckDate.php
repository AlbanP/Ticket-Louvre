<?php
namespace App\Service;

class CheckDate
{
    public function check($dateVisit, $today)
    {
        // Check Before today at 18h
        $start = clone $dateVisit;
        $start->add(new \DateInterval('PT18H'));
        if ($start < $today) {
            return false;
        }
        // Check After 6 month at midnight
        $end = clone $today;
        $end->add(new \DateInterval('P6M'));
        $end->setTime(23, 59, 59); 
        if ($dateVisit > $end) {
            return false;
        }
        // Check day is Sunday or Thesday
        $day = $dateVisit->format('l');
        if ($day == "Sunday" || $day == "Tuesday") {
            return false;
        }

        //
        if ($today->format('m') > 7) {
            $year = $today->format('Y') + 1;
        } else {
            $year = $today->format('Y');
        }
 
        $easterDate  = easter_date($year);
        $easterDay   = date('j', $easterDate);
        $easterMonth = date('n', $easterDate);
        $easterYear   = date('Y', $easterDate);
 
        $holidays = array(
            // Dates fixes
            mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
            mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
            mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
            mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
            mktime(0, 0, 0, 8,  15, $year),  // Assomption
            mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
            mktime(0, 0, 0, 11, 11, $year),  // Armistice
            mktime(0, 0, 0, 12, 5,  $year),  // 5 dec (close)
            mktime(0, 0, 0, 12, 25, $year),  // Noel
 
            // Dates variables
            mktime(0, 0, 0, $easterMonth, $easterDay + 2,  $easterYear), //Lundi de Pâques
            mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear), //Jeudi de l'Ascension
            mktime(0, 0, 0, $easterMonth, $easterDay + 51, $easterYear), //Lundi de Pentecôte
        );

        foreach($holidays as $holiday) {
            $day = new \DateTime();
            $day->setTimestamp($holiday);
            if ($dateVisit == $day) {

                return false;
            }
        }

        return true;
    }
    
    public function getEasterDateYearCurrent()
    {
        $today = new \datetime();
        if ($today->format('n') > 7) {
            $easterDate = easter_date($today->format('Y') + 1) + 43200;
        } else {
            $easterDate = easter_date($today->format('Y')) + 43200;
        }

        return $easterDate;
    }
}