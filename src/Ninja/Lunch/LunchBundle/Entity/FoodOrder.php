<?php

namespace Ninja\Lunch\LunchBundle\Entity;

use Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Ninja\Lunch\LunchBundle\Entity\FoodOrder
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass = "Ninja\Lunch\LunchBundle\Entity\FoodOrderRepository" )
 */
class FoodOrder
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="date", unique=true)
     */
    private $date;

    /**
     * @var boolean $locked If an order is no longer accepting items
     *
     * @ORM\Column(name="is_locked", type="boolean")
     */
    private $locked = false;

    /**
     * @ORM\OneToMany(targetEntity="Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item", mappedBy="order")
     */
    private $items;

    /**
     * Set the date to be today
     */
    public function __construct(){
        $this->date = new \DateTime();
        $this->items = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return FoodOrder
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set locked
     *
     * @param boolean $locked
     * @return FoodOrder
     */
    public function setLocked($locked = true)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked
     *
     * @return boolean If the order is locked
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Set items
     *
     * @param array $items
     * @return FoodOrder
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Get price
     *
     * @return float The price of this order
     */
    public function getPrice()
    {
        $price = 0;
        foreach($this->getItems() as $item) {
            $price += $item->getPrice();
        }

        return $price;
    }

    /**
     * Get amount paid
     *
     * @return float The amount paid for this order
     */
    public function getAmountPaid()
    {
        $price = 0;
        foreach($this->getItems() as $item) {
            $price += $item->getAmountPaid();
        }

        return $price;
    }

    /**
     * Get amount owing
     *
     * @return float The amount of money owing on this order
     */
    public function getAmountOwing()
    {
        return $this->getPrice() - $this->getAmountPaid();
    }

    /**
     * Get items
     *
     * @return ArrayCollection
     */
    public function getParticipants()
    {
        $found = array();

        return $this->items->filter(function($order) use (&$found) {
            if(in_array($order->getUser()->getId(), $found)) {
                return false;
            }

            $found[] = $order->getUser()->getId();

            return true;
        })->map(function($order) {
            return $order->getUser();
        });
    }

    public function createItem(){
        $item = new Item();
        $item->setOrder($this);

        return $item;
    }

    public function __toString(){
        return sprintf('Order for %s', $this->getDate()->format('Y-m-d'));
    }
}
