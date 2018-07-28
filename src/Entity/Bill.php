<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bill
 *
 * @ORM\Table(name="bill")
 * @ORM\Entity(repositoryClass="App\Repository\BillRepository")
 */
class Bill
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_bill", type="datetime")
     */
    private $dateBill;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=255)
     */
    private $transactionId;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

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
     * Set dateBill
     *
     * @param \DateTime $dateBill
     *
     * @return Bill
     */
    public function setDateBill($dateBill)
    {
        $this->dateBill = $dateBill;

        return $this;
    }

    /**
     * Get dateBill
     *
     * @return \DateTime
     */
    public function getDateBill()
    {
        return $this->dateBill;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Bill
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set transactionId
     *
     * @param string $transactionId
     *
     * @return Bill
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Get transactionId
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }
}
