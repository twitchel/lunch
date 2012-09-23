<?php

namespace Ninja\Lunch\LunchBundle\Controller\FoodOrder;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ninja\Lunch\LunchBundle\Entity\FoodOrder;
use Ninja\Lunch\LunchBundle\Entity\FoodOrder\Item;
use Ninja\Lunch\LunchBundle\Form\FoodOrder\ItemType;
use Ninja\Lunch\LunchBundle\Form\FoodOrder\PaymentType;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;

/**
 * FoodOrder\Item controller.
 *
 * @Route("/orders/{order}/items/{id}/payment")
 */
class PaymentController extends Controller
{

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     *
     */
    private $request;

    /**
     * @DI\Inject("ninja_lunch.order_item_repo")
     */
    private $repo;

    /**
     * @DI\Inject("ninja_lunch.acl_manager")
     */
    private $aclManager;

    /**
     * @DI\InjectParams({
     *     "router" = @DI\Inject,
     *     "request" = @DI\Inject
     * })
     */
    public function __construct($request, $router){
        $this->request = $request;
        $router->getContext()->setParameter('order', $request->get('order'));
        $router->getContext()->setParameter('item', $request->get('item'));
    }

    /**
     * Lists all FoodOrder\Item entities.
     *
     * @Route(".{_format}", name="order_items_payment_new", defaults={"_format" = "modal"})
     * @Method("GET")
     * @Template()
     */
    public function addAction($order, $id, $_format)
    {
        $entity = $this->getItem($id);
        $form   = $this->createForm(new PaymentType(), $entity);

        return $this->render(sprintf('NinjaLunchBundle:FoodOrder\Payment:add.%s.twig', $_format), array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Lists all FoodOrder\Item entities.
     *
     * @Route(".{_format}", name="order_items_payment_update", defaults={"_format" = "modal"})
     * @Method("POST")
     */
    public function updateAction($order, $id, $_format)
    {
        $entity = $this->getItem($id);
        $form = $this->createForm(new PaymentType(), $entity);

        $form->bind($this->request);

        if($form->isValid()){
            $this->em->persist($entity);
            $this->em->flush();
            $this->session->getFlashBag()->add('success', sprintf('Successfully put $%5.2f towards %s', $entity->getAmountPaid(), $entity->getName()));
            return $this->redirect($this->generateUrl('orders_show', array( 'id' => $entity->getOrder()->getId())));
        }

        return $this->render(sprintf('NinjaLunchBundle:FoodOrder\Payment:add.%s.twig', $_format), array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }


    private function getItem($id){
        return $this->repo->find($id);
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
