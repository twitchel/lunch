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

use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;

/**
 * FoodOrder\Item controller.
 *
 * @Route("/orders/{order}/items")
 */
class ItemController extends Controller
{
    /**
     * @DI\Inject
     */
    private $session;

    /**
     * @param Request The request
     */
    private $request;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * @DI\Inject("ninja_lunch.order_item_repo")
     */
    private $repo;

    /**
     * @DI\Inject("ninja_lunch.order_repo")
     */
    private $orderRepo;


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
    }

    /**
     * Lists all FoodOrder\Item entities.
     *
     * @Route("/", name="order_items")
     * @Template()
     */
    public function indexAction($order)
    {
        $entities = $this->repo->findAll();

        return array(
            'entities' => $entities,
            'order'    => $this->getOrder()
        );
    }

    /**
     * Lists all FoodOrder\Item entities.
     *
     * @Template()
     */
    public function listAction($order)
    {
        $page = $this->get('request')->query->get('page', 1);
        $perPage = 10;

        $entities = $this->repo->getPaginatedList($order, $page, $perPage);

        return array(
            'paginator' => $entities,
        );
    }

    /**
     * Get a simple report for ordering
     *
     * @Template()
     */
    public function reportAction($order){
        $report = $this->repo->getForOrderReport($order);

        return array(
            'report' => $report,
            'order'    => $this->getOrder()
        );
    }

    /**
     * Finds and displays a FoodOrder\Item entity.
     *
     * @Route("/{id}/show", name="order_items_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->getItem($id);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'order'      => $this->getOrder(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a new FoodOrder\Item entity.
     *
     * @Route("/create", name="order_items_create")
     * @Method("POST")
     * @Template("NinjaLunchBundle:FoodOrder\Item:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = $this->getOrder()->createItem();
        $form = $this->createForm(new ItemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $entity->setUser($this->getUser());
            $this->em->persist($entity);
            $this->em->flush();
            $this->aclManager->grant($entity);;
            $this->session->getFlashBag()->add('success', 'Your item was created and added to the order!');

            return $this->redirect($this->generateUrl('order_items_show', array('id' => $entity->getId())));
        }

        $this->session->getFlashBag()->add('warning', 'There were errors with the form!');

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FoodOrder\Item entity.
     *
     * @Route("/{item}/edit", name="order_items_edit")
     * @Template()
     * @Security\SecureParam(name="item", permissions="EDIT")
     */
    public function editAction(Item $item)
    {
        $editForm = $this->createForm(new ItemType(), $item);
        $deleteForm = $this->createDeleteForm($item->getId());

        return array(
            'entity'      => $item,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FoodOrder\Item entity.
     *
     * @Route("/{item}/update", name="order_items_update")
     * @Method("POST")
     * @Template("NinjaLunchBundle:FoodOrder\Item:edit.html.twig")
     * @Security\SecureParam(name="item", permissions="EDIT")
     */
    public function updateAction(Request $request, Item $item)
    {
        $deleteForm = $this->createDeleteForm($item->getId());
        $editForm = $this->createForm(new ItemType(), $item);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($item);
            $this->em->flush();
            $this->session->getFlashBag()->add('success', 'Your item was updated!');

            return $this->redirect($this->generateUrl('order_items_edit', array('item' => $item->getId())));
        }
        $this->session->getFlashBag()->add('warning', 'There were errors with the form!');

        return array(
            'entity'      => $item,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Finds and displays a form for the Item entity.
     *
     * @Template()
     */
    public function formAction($id = null) {
        if(!$id){
            $entity = $this->getOrder()->createItem();
        } else {
            $entity = $this->getItem($id);
        }

        $form   = $this->createForm(new ItemType(), $entity);

        return array(
            'form'   => $form->createView(),
            'entity' => $entity,
            'order'  => $this->getOrder()
        );
    }

    /**
     * Finds and displays a form for the Item entity.
     *
     * @Template()
     */
    public function deleteFormAction($id) {
        $form   = $this->createDeleteForm($id);
        $entity = $this->getItem($id);

        return array(
            'form'   => $form->createView(),
            'order'  => $this->getOrder(),
            'entity'  => $this->getOrder()
        );
    }

    /**
     * Deletes a FoodOrder\Item entity.
     *
     * @Route("/{item}/delete", name="order_items_delete")
     * @Security\SecureParam(name="item", permissions="EDIT")
     * @Method("POST")
     */
    public function deleteAction(Request $request, Item $item)
    {
        $form = $this->createDeleteForm($item->getId());
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->remove($item);
            $this->em->flush();
            $this->session->getFlashBag()->add('success', 'Your item was deleted!');
        }else{
            $this->session->getFlashBag()->add('warning', 'Your item was not deleted!');
        }

        return $this->redirect($this->generateUrl('order_items'));
    }

    private function getOrder(){
        return $this->orderRepo->find($this->request->get('order'));
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
