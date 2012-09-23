<?php

namespace Ninja\Lunch\LunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation as Security;

use Ninja\Lunch\LunchBundle\Form\User\ProfileType;
/**
 * @Route("/user")
 */
class UserController extends Controller
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
     * @DI\Inject("fos_user.user_manager")
     */
    private $repo;

    /**
     * @DI\Inject("ninja_lunch.order_item_repo")
     */
    private $itemRepo;

    /**
     * @Route("/profile/{username}", name="user_profile")
     * @Template()
     * @Method("GET")
     */
    public function profileAction($username)
    {
        $user = $this->repo->findUserByUsername($username);

        return array('user' => $user);
    }

    /**
     * @Route("/profile", name="fos_user_profile_show")
     * @Method("GET")
     */
    public function currentUserProfileAction()
    {
        $user = $this->getUser();

        return $this->forward('NinjaLunchBundle:User:profile', array('username' => $user->getUsername()));
    }

    /**
     * @Route("/details/{username}", name="user_profile_details")
     * @Template()
     */
    public function detailsAction($username)
    {
        $user = $this->repo->findUserByUsername($username);
        $faveourite = $this->itemRepo->getFaveouriteForUser($user->getId());

        return array('user' => $user, 'faveourite' => $faveourite);
    }

    /**
     * @Route("/profile/{username}/edit", name="user_profile_edit")
     * @Template()
     * @Method("GET")
     * @Security\PreAuthorize("hasRole('ROLE_SUPER_ADMIN') or (hasRole('ROLE_USER') and token.getUsername() == #username)")
     */
    public function editAction($username)
    {
        $user = $this->repo->findUserByUsername($username);
        $form = $this->createForm(new ProfileType(), $user);


        return array(
            'user' => $user,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/profile/{username}", name="user_profile_update")
     * @Method("POST")
     * @Template()
     */
    public function updateAction($username)
    {
        $user = $this->repo->findUserByUsername($username);
        $form = $this->createForm(new ProfileType(), $user);

        $form->bind($this->request);

        if($form->isValid()) {
            $this->repo->updateUser($user);

            return $this->redirect($this->generateUrl('user_profile_details', array(
                'username' => $username
            )));
        }

        return array('user' => $user);
    }

    /**
     * @Route("/recent-orders/{id}", name="user_recent_orders")
     * @Template()
     */
    public function recentOrdersAction($id)
    {
        $repo = $this->itemRepo;

        $entities = $repo->findRecentForUser($id);

        return array('entities' => $entities);
    }
}
