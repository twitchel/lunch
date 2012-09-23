<?php

namespace Ninja\Lunch\LunchBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ninja\Lunch\LunchBundle\Entity\FoodItem;
use Ninja\Lunch\LunchBundle\Form\FoodItemType;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;
/**
 * FoodItem controller.
 *
 * @Route("/admin/food")
 * @Security\PreAuthorize("hasRole('ROLE_ADMIN')")
 */
class FoodItemController extends Controller
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
     * @DI\Inject("ninja_lunch.food_item_repo")
     */
    private $repo;

    /**
     * Lists all FoodItem entities.
     *
     * @Route("/", name="admin_food")
     * @Template()
     */
    public function indexAction()
    {
        $entities = $this->repo->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a FoodItem entity.
     *
     * @Route("/{id}/show", name="admin_food_show")
     * @Template()
     */
    public function showAction($id)
    {
        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FoodItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new FoodItem entity.
     *
     * @Route("/new", name="admin_food_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FoodItem();
        $form   = $this->createForm(new FoodItemType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new FoodItem entity.
     *
     * @Route("/create", name="admin_food_create")
     * @Method("POST")
     * @Template("NinjaLunchBundle:FoodItem:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new FoodItem();
        $form = $this->createForm(new FoodItemType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            $this->session->getFlashBag()->add('success', 'The FoodItem has been created!');

            return $this->redirect($this->generateUrl('admin_food_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FoodItem entity.
     *
     * @Route("/{id}/edit", name="admin_food_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FoodItem entity.');
        }

        $editForm = $this->createForm(new FoodItemType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing FoodItem entity.
     *
     * @Route("/{id}/update", name="admin_food_update")
     * @Method("POST")
     * @Template("NinjaLunchBundle:FoodItem:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $entity = $this->repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FoodItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new FoodItemType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $this->em->persist($entity);
            $this->em->flush();

            $this->session->getFlashBag()->add('success', 'The FoodItem has been updated!');

            return $this->redirect($this->generateUrl('admin_food_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a FoodItem entity.
     *
     * @Route("/{id}/delete", name="admin_food_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $entity = $this->repo->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FoodItem entity.');
            }

            $this->em->remove($entity);
            $this->em->flush();

            $this->session->getFlashBag()->add('success', 'The FoodItem has been deleted!');
        }

        return $this->redirect($this->generateUrl('admin_food'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
