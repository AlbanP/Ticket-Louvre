<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Calendar
 *
 * @ORM\Table(name="calendar", indexes={@ORM\Index(name="search_day", columns={"day"})})
 * @ORM\Entity(repositoryClass="App\Repository\CalendarRepository")
 */
class Calendar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="day", type="date")
     */
    private $day;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_visitor", type="integer")
     */
    private $nbVisitor;


    /**
     * Get id
     *
     * @return int
     */
    public function getId(){
        return $this->id;
    }

    public function getDay(){
        return $this->day;
    }

    public function setDay($day){
        $this->day = $day;

        return $this;
    }

    public function getNbVisitor(): ?int {
        return $this->nbVisitor;
    }

    public function setNbVisitor(int $nbVisitor): self {
        $this->nbVisitor = $nbVisitor;

        return $this;
    }
}
