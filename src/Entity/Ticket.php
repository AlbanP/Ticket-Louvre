<?php
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity(repositoryClass="App\Repository\TicketRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Ticket
{
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", nullable=true)
    */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Visitor", mappedBy="ticket", cascade={"persist"})
    */
    private $visitors;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Bill", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
    */
    private $bill = null;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
    */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="half_day", type="boolean")
     */
    private $halfDay = false;

    /**
     * @var date
     *
     * @ORM\Column(name="date_visit", type="date")
     */
    private $dateVisit;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_visitor", type="integer")
     */
    private $nbVisitor = null;

    public function __construct()
    {
        $this->visitors = new ArrayCollection();
    }

    /**
     * Add visitor
     *
     * @param \App\Entity\Visitor $visitor
    */
    public function addVisitor(Visitor $visitor)
    {
        $this->visitors[] = $visitor;
        $visitor->setTicket($this);

        return $this;
    }

    /**
     * Update visitor
     *
     * @param \App\Entity\Visitor $visitor
    */
    public function updateVisitor(Visitor $visitor)
    {
        $visitor->setTicket($this);

        return $this;
    }

    /**
     * Remove visitor
     *
     * @param \App\Entity\Visitor $visitor
    */
    public function removeVisitor(Visitor $visitor)
    {
        $this->visitors->removeElement($visitor);
    }

    /**
     * Get visitors
     *
     * @param \App\Entity\Visitor $visitor
    */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set halfDay
     *
     * @param boolean $halfDay
     *
     * @return Ticket
     */
    public function setHalfDay($halfDay)
    {
        $this->halfDay = $halfDay;

        return $this;
    }

    /**
     * Get halfDay
     *
     * @return bool
     */
    public function getHalfDay()
    {
        return $this->halfDay;
    }

    /**
     * Set bill
     *
     * @param \App\Entity\Bill $bill
     *
     * @return Ticket
     */
    public function setBill(\App\Entity\Bill $bill)
    {
        $this->bill = $bill;

        return $this;
    }

    /**
     * Get bill
     *
     * @return \AppBundle\Entity\Bill
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Ticket
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set nbVisitor
     *
     * @param integer $nbVisitor
     *
     * @return Ticket
     */
    public function setNbVisitor($nbVisitor)
    {
        $this->nbVisitor = $nbVisitor;

        return $this;
    }

    /**
     * Get nbVisitor
     *
     * @return integer
     */
    public function getNbVisitor()
    {
        return $this->nbVisitor;
    }

    /**
     * Set dateVisit
     *
     * @param \DateTime $dateVisit
     *
     * @return Ticket
     */
    public function setDateVisit($dateVisit)
    {
        $this->dateVisit = $dateVisit;

        return $this;
    }

    /**
     * Get dateVisit
     *
     * @return \DateTime
     */
    public function getDateVisit()
    {
        return $this->dateVisit;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Ticket
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Ticket
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}
