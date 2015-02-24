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

class builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'PASS_AuthentificationLog_homepage'));
        $menu->addChild('Home2', array('route' => 'PASS_AuthentificationLog_homepage'));

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