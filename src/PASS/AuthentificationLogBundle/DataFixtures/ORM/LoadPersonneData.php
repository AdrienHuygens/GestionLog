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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

class LoadPersonneData extends AbstractFixture implements \Doctrine\Common\DataFixtures\FixtureInterface, ContainerAwareInterface,  OrderedFixtureInterface  {

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
            array(0, 'Admin', 'abcde',true,false,false),
            
        );
             $newTabs = array( 'Default',"Groupe Par default",false,True,false);
            
            $groupe = new \PASS\AuthentificationLogBundle\Entity\Groupe();
            $groupe->setNom($newTabs[0]);
            $groupe->addRole($this->getReference('ROLE_DEFAULT'));
            $groupe->setDescription($newTabs[1]);
            $groupe->setActif($newTabs[3]);
            $groupe->setLdap($newTabs[2]);
            $groupe->setSupprimable($newTabs[4]);
            
             $manager->persist($groupe);
      
        
            
            foreach ($tab as $newTab) {
            $type = new \PASS\AuthentificationLogBundle\Entity\Personne($newTab[5]);
            $type->setActif($newTab[3]);
            $type->setLdap($newTab[4]);
            
             $encoder = $this->container
                ->get('security.encoder_factory')
                ->getEncoder($type);

            $type->setMdp($encoder->encodePassword($newTab[2],$type->getSalt()));
            //$type->setMdp($newTab[2]);
            $type->setUsername($newTab[1]);
            $type->addRole($this->getReference('ROLE_ADMIN'));
            $type->addGroupe($groupe);
            $type->addGroupe($this->getReference('ALLADMIN'));
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 3;
    }

}
