<?php

namespace Ninja\CMS\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;
use Ninja\CMS\CoreBundle\Module\Provider;

class SidebarMenuBuilder extends AbstractNavbarMenuBuilder
{
    protected $securityContext;
    protected $isLoggedIn;

    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, Provider $moduleProvider)
    {
        parent::__construct($factory);

        $this->securityContext = $securityContext;
        $this->moduleProvider = $moduleProvider;
        $this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }


    public function createSidebarMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-list well');

        $modules = $this->moduleProvider->getModules();
        foreach($modules as $module){
            $menu->addChild($module->getTitle(), array('attributes' => array('class' => 'nav-header')));
            $module->bindMenu($menu);
            $this->addDivider($menu);
        }

        return $menu;
    }

    protected function addContentMenu($menu) {
        $contentMenu = $this->factory->createItem('Assets', array('route' => 'dashboard', 'extras' => array('icon' => 'book')));
        $contentMenu->addChild('List', array('route' => 'auth_asset', 'extras' => array('icon' => 'list')));
        $contentMenu->addChild('New', array('route' => 'auth_asset_new', 'extras' => array('icon' => 'pencil')));

        $menu->addChild($contentMenu);

        return $menu;
    }
}
