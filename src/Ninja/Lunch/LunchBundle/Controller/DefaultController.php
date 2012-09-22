<?php

namespace Ninja\Lunch\LunchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/profiles/{username}", name="user_profile")
     * @Template()
     */
    public function indexAction($username)
    {
        return array('name' => $username);
    }
}
