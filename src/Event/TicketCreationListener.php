<?php
namespace App\Event;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Ticket;

class TicketCreationListener
{
    private $prefix = 'TL';

    public function postPersist(LifecycleEventArgs $args)
    {
        $ticket = $args->getObject();

        if (!$ticket instanceof Ticket) {
            return;
        }
        
        $id = $ticket->getId();
        $code = $this->prefix.$id;
        $ticket->setCode($code);

        $em = $args->getEntityManager();
        $em->persist($ticket);
        $em->flush();
    }
}
