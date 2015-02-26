<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\GeneralLogBundle\Menu;

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
        $menu->addChild('Acceui', array('route' => 'PASS_CreationUtilisateur'));
        $menu->addChild('Gestion log', array('route' => 'PASS_CreationGroupe'));
        /* 
        $menu->addChild('User',array('uri'=>'#','label'=>'Mon compte ▼'))->setAttribute('class', 'dropdown');
        $menu['User']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['User']->setLinkAttribute('role', 'button');
          $menu['User']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['User']->setChildrenAttribute('role', 'menu');
      
           
                $menu['User']->setChildrenAttribute('class', 'dropdown-menu')->addChild('Profile', array('uri' => '#'))->setAttribute('divider_append', true);
                $menu['User']->addChild('Déconnexion', array('uri' => '#'));
                */
         
        $menu->addChild('Admin',array('uri'=>'#','label'=>'Administration ▼'))->setAttribute('class', 'dropdown');
        $menu['Admin']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['Admin']->setLinkAttribute('role', 'button');
          $menu['Admin']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['Admin']->setChildrenAttribute('role', 'menu');
           
          $menu['Admin']->setChildrenAttribute('class', 'dropdown-menu')->addChild('Gestion Utilisateur', array('uri' => '#'))->setAttribute('divider_append', true);
          $menu['Admin']->addChild('Gestion Groupe', array('uri' => '#'));
          $menu['Admin']->addChild('Gestion Role', array('uri' => '#'));
             
          
       $menu->addChild('Configuration', array('route' => 'PASS_CreationGroupe'));
       
       
        $menu->addChild('User',array('uri'=>'#','label'=>'Mon compte ▼'))->setAttribute('class', 'dropdown ');
        $menu['User']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['User']->setLinkAttribute('role', 'button');
          $menu['User']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['User']->setChildrenAttribute('role', 'menu');
      
           
                $menu['User']->setChildrenAttribute('class', 'dropdown-menu')->addChild('Profile', array('uri' => '#'))->setAttribute('divider_append', true);
                $menu['User']->addChild('Déconnexion', array('uri' => '#'));
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