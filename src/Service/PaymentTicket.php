<?php
namespace App\Service;

use App\Service\Mailer;
use App\Entity\Ticket;
use App\Entity\Calendar;
use App\Entity\Bill;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Doctrine\ORM\EntityManagerInterface;

class PaymentTicket
{
    private $secretKey = 'sk_test_lZReZR3lqdyyQmSsCnmAUOtQ';
    private $current = 'eur';
    private $description = 'TicketLouvre';
    private $mailer;
    private $session;
    private $em;

    public function __construct(Mailer $mailer, SessionInterface $session, EntityManagerInterface $em)
    {
        $this->mailer = $mailer;
        $this->session = $session;
        $this->em = $em;
    }

    public function stripe($token, $ticket, $array)
    {
        try {
            \Stripe\Stripe::setApiKey($this->secretKey);
            $response = \Stripe\Charge::create(array(
                "amount" => $ticket->getPrice(),
                "currency" => $this->current,
                "source" => $token,
                "description" => $this->description,
                "metadata" => $array
            ));

            $this->saveBill($response, $ticket);
            $this->saveTicketInCalendar($ticket);
            $this->em->flush();

            return true;
              
        } catch(\Stripe\Error\Card $e) {
            // Since it's a decline, \Stripe\Error\Card will be caught
            $response = $e->getMessage();
        } catch (\Stripe\Error\RateLimit $e) {
            // Too many requests made to the API too quickly
            $response = $e->getMessage();
        } catch (\Stripe\Error\InvalidRequest $e) {
            // Invalid parameters were supplied to Stripe's API
            $response = $e->getMessage();
        } catch (\Stripe\Error\Authentication $e) {
            // Authentication with Stripe's API failed
            // (maybe you changed API keys recently)
            $response = $e->getMessage();
        } catch (\Stripe\Error\ApiConnection $e) {
            // Network communication with Stripe failed
            $response = $e->getMessage();
        } catch (\Stripe\Error\Base $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $response = $e->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $response = $e->getMessage();
        }

        $this->mailer->sendError($response, $ticket);
        $this->session->getFlashBag()->add('notice', "Sorry, but an error occurred, try again");

        return false;
    }
    
    protected function saveTicketInCalendar($ticket)
    {
        $dateVisit = $ticket->getDateVisit();
        $nbVisitor = $ticket->getNbVisitor();

        $repository = $this->em->getRepository(Calendar::class);
        $calendar = $repository->findOneByDay($dateVisit);

        if(is_null($calendar)) {
            $calendar = new Calendar();
            $calendar->setDay($dateVisit);
            $calendar->setNbVisitor($nbVisitor);
        } else {
            $nbVisitorDay = $calendar->getNbVisitor() + $nbVisitor;
            $calendar->setNbVisitor($nbVisitorDay);
        }
        $this->em->persist($calendar);
    }

    protected function saveBill($response, $ticket)
    {
        $bill = new Bill();

        $bill->setTransactionId($response['id']);

        $dateBill = new \DateTime();
        $dateBill->setTimestamp($response['created']);
        $bill->setDateBill($dateBill);

        $bill->setPrice($response['amount']);
        $ticket->setBill($bill);

        $this->em->persist($ticket);
    }
}