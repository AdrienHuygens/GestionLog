<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\AuthentificationLogBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;



class Builder extends ContainerAware
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    
    public function createBreadcrumbMenu(\Symfony\Component\HttpFoundation\Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', ' nav navbar-nav');
        $menu->addChild('Home', array('route' => 'PASS_CreationUtilisateur'));
        $menu->addChild('Home2', array('route' => 'PASS_CreationGroupe'));
        
        $menu->addChild('User',array('uri'=>'#','label'=>'Compte ▼', 'img'=>'test'))->setAttribute('class', 'dropdown');
        $menu['User']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['User']->setLinkAttribute('role', 'button');
          $menu['User']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['User']->setChildrenAttribute('role', 'menu');
      
           
        $menu['User']->setChildrenAttribute('class', 'dropdown-menu')->addChild('Profile', array('uri' => '#'))->setAttribute('divider_append', true);
        $menu['User']->addChild('Logout', array('uri' => '#'));
        
        
       
        // access services from the container!
        //$em = $this->container->get('doctrine')->getManager();
        // findMostRecent and Blog are just imaginary examples
       // $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();

        //$menu->addChild('Latest Blog Post', array(
         //   'route' => 'blog_show',
          //  'routeParameters' => array('id' => $blog->getId())
       // ));

        // you can also add sub level's to your menu's as follows
       // $menu['About Me']->addChild('Edit profile', array('route' => 'edit_profile'));

        // ... add more children

        return $menu;
    }
    
}