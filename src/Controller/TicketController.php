<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CheckDate;
use App\Service\CalculVisitors;
use App\Service\CheckNbVisitor;
use App\Entity\Ticket;
use App\Entity\Calendar;
use App\Entity\Rate;
use App\Form\TicketType;

class TicketController extends Controller
{
    /**
    * @Route({"en" : "/", "fr" : "/"}, name="home", requirements={"_locale": "en|fr"})
    */
    public function index(Request $request, CheckDate $checkDate)
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
                if (!$checkDate->check($dateVisit, $dateTimeParis)) {
                    $this->addFlash('notice', "Bad date, please choose another date");

                    return $this->redirectToRoute('home');
                }
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

        $easterDate = $checkDate->getEasterDateYearCurrent();

        return $this->render('ticket/index.html.twig', array(
            'local' => $locale,
            'timeZoneOffset' => $timeZoneOffsetParis,
            'dateVisit' => $dateVisit,
            'list_rate' => $listRate,
            'list_days' => $listDays,
            'easter_date'=> $easterDate
        ));
    }

    /**
    * @Route({"en" : "/visitors", "fr" : "/visiteurs"}, name="selectVisitor", requirements={"_locale": "en|fr"})
    */
    public function selectVisitor(Request $request, CalculVisitors $calculVisitors, CheckNbVisitor $checkNbVisitor)
    {
        $locale = $request->getLocale();
        $ticket = $request->getSession()->get('ticket');
        $dateVisit = $ticket->getDateVisit();
        $nbVisitorDay = $checkNbVisitor->calculNbVisitorDay($dateVisit, $ticket->getNbVisitor());

        if (! $dateVisit) {
            return $this->redirectToRoute('home');
        }
        // Create form for the visitors
        $form = $this->get('form.factory')->create(TicketType::class, $ticket);
        // When the visitor select pay
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Check if have 1 or more visitor(s)
            $ticketVisitor = $calculVisitors->getCalculVisitors($ticket);
            if ($ticket->getVisitors()->isEmpty()) {

                $this->getFlashBag()->add('notice', "Sorry, but avaible place is full, try another day");
                return $this->redirectToRoute('selectVisitor');
            }
            $request->getSession()->set('ticket', $ticketVisitor);

            return $this->redirectToRoute('payment');
        }

        return $this->render('ticket/selectVisitor.html.twig', array(
                'form' => $form->createView(),
                'ticket' => $ticket,
                'local' => $locale,
                'nbVisitorDay' => $nbVisitorDay
            ));
    }
    /*
     * Change the locale for the current user
     *
     * @param String $language
     * @return array
     *
     * @Route("/{language}", name="setlocale")
     * @Template()
    */
    public function setLocale($language = null)
    {
        if($language != null) {
            $this->get('session')->set('_locale', $language);
        }
    
        $url = $this->container->get('request')->headers->get('referer');
        if(empty($url))
        {
            $url = $this->container->get('router')->generate('index');
        }
    
        return new RedirectResponse($url);
   }
}
