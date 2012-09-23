<?php

namespace Ninja\Lunch\LunchBundle\Entity\FoodOrder;

use Doctrine\ORM\EntityRepository;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;


/**
 * ItemRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItemRepository extends EntityRepository
{
    private $paginator;

    /**
     * @DI\InjectParams({
     *     "paginator" = @DI\Inject("knp_paginator")
     * })
     */
    public function setPaginator($paginator){
        $this->paginator = $paginator;
    }

    /**
     * Get the recent orders for a user
     *
     * @param integer $id The id of the order
     * @param integer $id The current page
     * @param integer $id The amount of items to show per page
     *
     * @return Paginator The paginator
     */
    public function getPaginatedList($order, $page, $perPage){
        $qb = $this->createQueryBuilder('i');

        $qb
            ->innerJoin('i.order', 'o')
            ->andWhere($qb->expr()->eq('o.id', ':idOrder'))
            ->setParameter('idOrder', $order)
        ;

        return $this->paginator->paginate(
            $qb->getQuery(),
            $page,
            $perPage
        );
    }

    /**
     * Get the recent orders for a user
     *
     * @param integer $id The id of the order
     * @param integer $id The current page
     * @param integer $id The amount of items to show per page
     *
     * @return Paginator The paginator
     */
    public function getFaveouriteForUser($user){
        $qb = $this->createQueryBuilder('i');

        $qb
            ->select('count(i) as total, i as item')
            ->andWhere($qb->expr()->eq('i.user', ':idUser'))
            ->leftJoin('i.item', 'it')
            ->setParameters(array(
                'idUser' => $user
            ))
            ->groupBy('it.id')
            ->orderBy($qb->expr()->desc('total'))
            ->setMaxResults(1)
        ;

        $order = $qb->getQuery()->getOneOrNullResult();

        return $order;
    }

    /**
     * Get the recent orders for a user
     *
     * @param integer $id The id of the user to get
     *
     * @return ArrayCollection A collection of recent Items
     */
    public function findRecentForUser($id) {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->innerJoin('i.user', 'u')
            ->andWhere($qb->expr()->eq('u.id', ':idUser'))
            ->setMaxResults(10)
            ->setParameter('idUser', $id)
            ->orderBy($qb->expr()->desc('i.id'))
        ;

        return $qb->getQuery()->execute();
    }

    /**
     * Get a simple report for an order
     *
     * @param integer $id The id for the order to get the report for
     *
     * @return ArrayCollection The report
     */
    public function getForOrderReport($id) {
        $qb = $this->createQueryBuilder('i');

        $qb
            ->select('count(i) as total, i as item')
            ->andWhere($qb->expr()->eq('i.order', ':idOrder'))
            ->leftJoin('i.item', 'it')
            ->setParameters(array(
                'idOrder' => $id
            ))
            ->groupBy('it.id')
        ;

        $order = $qb->getQuery()->getResult();

        return $order;
    }
}
