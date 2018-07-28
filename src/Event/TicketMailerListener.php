<?php
namespace App\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Service\Mailer;
use App\Entity\Ticket;

class TicketMailerListener
{
  /**
   * @var Mailer
   */
    private $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $ticket = $args->getObject();

        if (!$ticket instanceof Ticket) {
            return;
        }

        $this->mailer->sendTicket($ticket);
    }
}
