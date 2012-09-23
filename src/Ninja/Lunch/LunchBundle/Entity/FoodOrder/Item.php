<?php

namespace Ninja\Lunch\LunchBundle\Entity\FoodOrder;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ninja\Lunch\LunchBundle\Entity\FoodOrder\ItemRepository")
 */
class Item
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
     * @var string $comments
     *
     * @ORM\Column(name="comments", type="text", nullable=true)
     */
    private $comments;

    /**
     * @var string $amountPaid
     *
     * @ORM\Column(name="amount_paid", type="float")
     */
    private $amountPaid = 0;

    /**
     * @var string $comments
     *
     * @ORM\ManyToOne(targetEntity="Ninja\Lunch\LunchBundle\Entity\User", inversedBy="items")
     */
    private $user;

    /**
     * @var string $comments
     *
     * @ORM\ManyToOne(targetEntity="Ninja\Lunch\LunchBundle\Entity\FoodItem")
     */
    private $item;

    /**
     * @var string $comments
     *
     * @ORM\ManyToOne(targetEntity="Ninja\Lunch\LunchBundle\Entity\FoodOrder", inversedBy="items")
     */
    private $order;

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
     * Proxy for getItem -> getName
     *
     * @return string The name of the FoodItem
     * @see Item::getItem
     */
    public function getName()
    {
        return $this->getItem() ? $this->getItem()->getName() : null;
    }

    /**
     * Proxy for getItem -> getPrice
     *
     * @return float The price of the item
     * @see Item::getItem
     */
    public function getPrice()
    {
        return $this->getItem() ? $this->getItem()->getPrice() : null;
    }

    /**
     * Set comments
     *
     * @param string $comments
     * @return Item
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set item
     *
     * @param string $item
     * @return Item
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set order
     *
     * @param string $order
     * @return Item
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return string
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set amountPaid
     *
     * @param string $amountPaid
     * @return Item
     */
    public function setAmountPaid($amountPaid)
    {
        $this->amountPaid = $amountPaid;

        return $this;
    }

    /**
     * Get amountPaid
     *
     * @return string
     */
    public function getAmountPaid()
    {
        return $this->amountPaid;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Item
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    public function getAmountOwing() {
        return $this->getPrice() - $this->getAmountPaid();
    }

    public function __toString(){
        return (string) $this->getItem();
    }
}
