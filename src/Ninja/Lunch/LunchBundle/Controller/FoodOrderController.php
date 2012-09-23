<?php

namespace Ninja\Lunch\LunchBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ninja\Lunch\LunchBundle\Entity\FoodOrder;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;

/**
 * FoodOrder controller.
 *
 * @Route("/orders")
 */
class FoodOrderController extends Controller
{
    /**
     * @DI\Inject
     */
    private $session;

    /**
     * @DI\Inject
     */
    private $request;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * @DI\Inject("ninja_lunch.order_repo")
     */
    private $repo;

    /**
     * Lists all FoodOrder entities.
     *
     * @Route("/", name="orders")
     * @Template()
     */
    public function indexAction()
    {
        $page = $this->request->get('page', 1);
        $entities = $this->em->getRepository('NinjaLunchBundle:FoodOrder')->getPaginatedList($page, 10);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Lists all FoodOrder entities.
     *
     * @Route("/today.{_format}", defaults={"_format" = "html"}, name="orders_current")
     * @Template()
     */
    public function todaysOrderAction($_format)
    {
        $order = $this->repo->getCurrent();
        return $this->forward('NinjaLunchBundle:FoodOrder:show', array('id' => $order->getId(), '_format' => $_format));
    }

    /**
     * Finds and displays a FoodOrder entity.
     *
     * @Route("/{id}/show.{_format}", defaults={"_format" = "html"}, name="orders_show")
     * @Template()
     */
    public function showAction($id, $_format)
    {
        $entity = $this->getOrder($id);

        return $this->render(sprintf('NinjaLunchBundle:FoodOrder:show.%s.twig', $_format), array(
            'entity' => $entity
        ));
    }

    /**
     * Locks an order
     *
     * @Route("/{id}/lock", name="orders_lock")
     * @Template()
     */
    public function lockAction($id)
    {
        $entity = $this->getOrder($id);

        $entity->setLocked();

        $this->em->persist($entity);
        $this->em->flush();

        $this->session->getFlashBag()->add('success', 'The order was locked!');

        return $this->redirect($this->generateUrl('orders_show', array('id' => $entity->getId())));
    }

    /**
     * Get an order from it's ID
     *
     * @param $id integer The orders id
     * @throws NotFoundException Thrown if the order is not found
     */
    private function getOrder($id){
        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find that order entity');
        }

        return $entity;
    }
}
