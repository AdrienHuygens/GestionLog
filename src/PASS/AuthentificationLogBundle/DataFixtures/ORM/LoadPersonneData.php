<?php

/*
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\AuthentificationLogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PASS\GestionLogBundle\Entity\facility;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadPersonneData implements \Doctrine\Common\DataFixtures\FixtureInterface, ContainerAwareInterface {

     private $container;
/**
* {@inheritDoc}
*/
public function setContainer(ContainerInterface $container = null) {
$this->container = $container;
}
    
    
    public function load(ObjectManager $manager) {

        $tab = array(
            /* id,username, mdp, actif, ldap , groupe, supprimable  */
            array(0, 'adrien', 'abcde',true,false,'Admin',false),
            
        );
             $newTabs = array( 'Default', 'ROLE_DEFAULT',"Groupe Par default",false,True,false);
            
            $groupe = new \PASS\AuthentificationLogBundle\Entity\Groupe();
            $groupe->setNom($newTabs[0]);
            $groupe->setRole($newTabs[1]);
            $groupe->setDescription($newTabs[2]);
            $groupe->setActif($newTabs[4]);
            $groupe->setLdap($newTabs[3]);
            $groupe->setSupprimable($newTabs[5]);
            
             $manager->persist($groupe);
      
        
            
            foreach ($tab as $newTab) {
            $type = new \PASS\AuthentificationLogBundle\Entity\Personne($newTab[6]);
            $type->setActif($newTab[3]);
            $type->setLdap($newTab[4]);
            
             $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($type);

            $type->setMdp($encoder->encodePassword($newTab[2],$type->getSalt()));
            //$type->setMdp($newTab[2]);
            $type->setUsername($newTab[1]);
            
            $type->addGroupe($groupe);
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
