<?php

namespace Ninja\Lunch\LunchBundle\Menu;

use Knp\Menu\Silex\Voter\RouteVoter as BaseRouteVoter;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class RouteVoter extends BaseRouteVoter
{
    public function __construct($container) {
        $request = $container->get('request');
        $this->setRequest($request);
    }

}
