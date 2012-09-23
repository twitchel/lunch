<?php

namespace Ninja\Lunch\LunchBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;

class NavbarMenuBuilder extends AbstractNavbarMenuBuilder
{
    protected $securityContext;
    protected $isLoggedIn;

    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext)
    {
        parent::__construct($factory);

        $this->securityContext = $securityContext;
        $this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function createLeftMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        if(!$this->isLoggedIn) {
            return $menu;
        }

        $menu->setChildrenAttribute('class', 'nav');
        $this->addDivider($menu, true);
        $menu->addChild('Home', array('route' => 'index'));
        $menu->addChild('Orders', array('route' => 'orders'));
        $menu->addChild('Todays Order', array('route' => 'orders_current'));

        return $menu;
    }

    public function createRightMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        if(!$this->isLoggedIn) {
            return $menu;
        }

        $menu->setChildrenAttribute('class', 'nav pull-right');

        $dropdown = $this->createDropdownMenuItem($menu, "Settings");
        $dropdown->addChild('Profile', array('route' => 'fos_user_profile_show'));
        $dropdown->addChild('Logout', array('route' => 'fos_user_security_logout', 'extras' => array('icon' => 'off')));

        return $menu;
    }

    protected function createDropdownModuleItem($module, $menu){
        $attrs = array();

        if($module->getIconClass()){
            $attrs['icon'] = $module->getIconClass();
        }

        return $this->createDropdownMenuItem($menu, ' ' . $module->getTitle(), false, $attrs);
    }
    /**
     * get a preconfigured Dropdown menu where to easily add childs
     *
     * @param string  $title      Title of the item
     * @param boolean $push_right Make if float right default: true
     */
    protected function createDropdownMenuItem(ItemInterface $rootItem, $title, $push_right = true, $icon = array())
    {
        $rootItem
            ->setAttribute('class', 'nav')
        ;
        if ($push_right) {
            $this->pushRight($rootItem);
        }
        $dropdown = $rootItem->addChild($title, array('uri'=>'#'))
            ->setLinkattribute('class', 'dropdown-toggle')
            ->setLinkattribute('data-toggle', 'dropdown')
            ->setAttribute('class', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
        ;
        // TODO: make XSS safe $icon contents escaping
        if (isset($icon['icon'])) {
            $icon = array_merge(array('tag'=>'i'), $icon);
            $dropdown->setLabel(' <'.$icon['tag'].' class="'.$icon['icon'].'"></'.$icon['tag'].'>' . $title)
                     ->setExtra('safe_label', true);
        }

        return $dropdown;
    }
}
