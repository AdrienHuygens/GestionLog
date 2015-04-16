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
    /*public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }*/

    
    public function createBreadcrumbMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        
       $secu = $this->container->get('security.context');
       
       
        $menu->setChildrenAttribute('class', ' nav navbar-nav');
        
       if ($secu->isGranted('ROLE_DEFAULT')) {
        $menu->addChild('Accueil', array('route' => 'PASS_ACCUEIL2'));
       }
        else $menu->addChild('Accueil', array('route' => 'PASS_ACCUEIL'));
           
        if ($secu->isGranted('ROLE_LOG_R') ||$secu->isGranted('ROLE_ADMIN')) {
        $menu->addChild('Gestion log', array('route' => 'PASS_AffichageLog'));
        }
        if ($secu->isGranted('ROLE_USER_C')||$secu->isGranted('ROLE_USER_R') || $secu->isGranted('ROLE_GROUPE_C')
            || $secu->isGranted('ROLE_GROUPE_R') || $secu->isGranted('ROLE_LDAP_I') || $secu->isGranted('ROLE_GROUPE_ORDI_R')
                || $secu->isGranted('ROLE_GROUPE_ORDI_C')|| $secu->isGranted('ROLE_STAT') ||$secu->isGranted('ROLE_ADMIN')||$secu->isGranted('ROLE_MEMORY')) {
        $menu->addChild('Admin',array('uri'=>'#','label'=>'Administration ▼'))->setAttribute('class', 'dropdown');
        $menu['Admin']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['Admin']->setLinkAttribute('role', 'button');
          $menu['Admin']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['Admin']->setChildrenAttribute('role', 'menu');
            $menu['Admin']->setChildrenAttribute('class', 'dropdown-menu');
          if ($secu->isGranted('ROLE_USER_C') || $secu->isGranted('ROLE_USER_R')|| $secu->isGranted('ROLE_ADMIN')||$secu->isGranted('ROLE_MEMORY'))
          $menu['Admin']->addChild('Gestion Utilisateur', array('route' => 'PASS_GestionUtilisateur'))->setAttribute('divider_append', true);
          if ($secu->isGranted('ROLE_GROUPE_C') || $secu->isGranted('ROLE_GROUPE_R')|| $secu->isGranted('ROLE_ADMIN')){
          $menu['Admin']->addChild('Gestion Groupe', array('route' => 'PASS_GestionGroupe'));
          }
          if ($secu->isGranted('ROLE_GROUPE_ORDI_C') || $secu->isGranted('ROLE_GROUPE_ORDI_R')|| $secu->isGranted('ROLE_ADMIN'))
          $menu['Admin']->addChild('Gestion Groupe d\'ordinateur', array('route' => 'PASS_GroupeOrdinateurListing'));
         //$menu['Admin']->addChild('Gestion Role', array('uri' => '#'));
          if($secu->isGranted('ROLE_STAT_R') || $secu->isGranted('ROLE_ADMIN'))
          $menu['Admin']->addChild('Statistique', array('route' => 'PASS_AffichageStat'));   
        }
        
       if ($secu->isGranted('ROLE_CONFIGURATION_R') || $secu->isGranted('ROLE_CONFIGURATION_U')|| $secu->isGranted('ROLE_ADMIN'))
       {
      
       $menu->addChild('Configuration',array('uri'=>'#','label'=>'Configuration ▼'))->setAttribute('class', 'dropdown ');
        $menu['Configuration']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['Configuration']->setLinkAttribute('role', 'button');
          $menu['Configuration']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['Configuration']->setChildrenAttribute('role', 'menu');
           
           $menu['Configuration']->setChildrenAttribute('class', 'dropdown-menu')->addChild('Base de donnée', array('route' => 'PASS_Configuration'))->setAttribute('divider_append', true);
             $menu['Configuration']->addChild('LDAP', array('route' => 'PASS_ConfigurationLdap'));
             $menu['Configuration']->addChild('Mail', array('route' => 'PASS_ConfigurationMail'));
    }
        if($secu->isGranted('ROLE_DEFAULT')){
        $menu->addChild('User',array('uri'=>'#','label'=>'Mon compte ▼'))->setAttribute('class', 'dropdown ');
        $menu['User']->setLinkAttribute('class', 'dropdown-toggle');
         $menu['User']->setLinkAttribute('role', 'button');
          $menu['User']->setLinkAttribute('data-toggle', 'dropdown');
           $menu['User']->setChildrenAttribute('role', 'menu');
                    $menu['User']->setChildrenAttribute('class', 'dropdown-menu');
                if(!$this->container->get('security.context')->getToken()->getUser()->getldap())
                $menu['User']->addChild('Profil', array('route' => 'PASS_MonCompte'))->setAttribute('divider_append', true);
                $menu['User']->addChild('Déconnexion', array('route' => 'logout'));
                
               
   
            }
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