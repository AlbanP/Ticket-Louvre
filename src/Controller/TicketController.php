<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ticket;
use App\Entity\Calendar;
use App\Entity\Rate;

class TicketController extends Controller
{
    /**
     * @Route({"en" : "/", "fr" : "/"}, name="home", requirements={"_locale": "en|fr"})
    */
    public function index(Request $request)
    {
        //Define DateTime Paris and Offset / UTC
        $locale = $request->getLocale();
        $dateTimeParis = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $timeZoneOffsetParis = $dateTimeParis->getOffset();

        // If the visitor has slecteted dateVisit and day or half-day
        if ($request->isMethod('POST')) {
            $dateVisit = $request->request->get('date-select');
            $dateVisit = new \DateTime($dateVisit);
            $half_day = $request->request->get('half-day-button');

            if (! empty($dateVisit)) {
                $ticket = new Ticket();
                // Add date visit to ticket
                $ticket->setDateVisit($dateVisit);
                // If visitor select half-day -> true
                if (isset($half_day)) {
                    $ticket->setHalfDay(true);
                }
                // Save ticket to session 
                $request->getSession()->set('ticket', $ticket);
                
                return $this->redirectToRoute('selectVisitor');
            }
            $ticket = new Ticket();
            $this->addFlash('notice', "Please choose a date");
        }

        // 
        $ticket = $request->getSession()->get('ticket');
        if (is_null($ticket)){
            $dateVisit = null;
        } else {
            //$dateVisit = new \DateTime();
            $dateVisit = $ticket->getDateVisit();
        }
        // find rate and save to session
        $repository = $this->getDoctrine()->getRepository(Rate::class);
        $listRate = $repository->findAll();
        
        $repository = $this->getDoctrine()->getRepository(Calendar::class);
        $listDays = $repository->showDays();

        $easterDate = easter_date();

        return $this->render('ticket/index.html.twig', array(
            'local' => $locale,
            'timeZoneOffset' => $timeZoneOffsetParis,
            'dateVisit' => $dateVisit,
            'list_rate' => $listRate,
            'list_days' => $listDays,
            'easter_date'=> $easterDate
        ));
    }
}
