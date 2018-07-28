<?php
namespace App\Service;

use App\Entity\Ticket;

class Mailer
{
    /**
    * @var \Swift_Mailer
    */
    private $mailer;
    private $templating;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendError($e ,Ticket $ticket)
    {
        $message = (new \Swift_Message('Ticket Louvre - Erreur Stripe'))
        ->setFrom('contact@picomweb.fr')
        ->setTo('a.painchault@gmail.com')
        ->setBody(
            $this->templating->render(
            'emails/error.html.twig',
            array(
                'ticket' => $ticket,
                'error' => $e
            )),
        'text/html');

        $this->mailer->send($message);
    }

    public function sendTicket(Ticket $ticket)
    {
        $message = (new \Swift_Message('Ticket Louvre'))
        ->setFrom('ticket@louvre.fr')
        ->setTo($ticket->getEmail())
        ->setBody(
            $this->templating->render(
            'emails/ticket.html.twig',
            array('ticket' => $ticket)
        ),
        'text/html');

        $this->mailer->send($message);
    }
}