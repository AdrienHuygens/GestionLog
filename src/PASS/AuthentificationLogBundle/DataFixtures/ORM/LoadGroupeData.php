<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */
namespace PASS\AuthentificationLogBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use PASS\AuthentificationLogBundle\Entity\Role;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use PASS\AuthentificationLogBundle\Entity\Groupe;

class LoadGroupeData extends AbstractFixture implements OrderedFixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $tab  = array(
            
            array( 'AllAdmin',"Groupe de tout les droits",false,True,false),
        );
        
         
            
        
        $ref = new Role();
         
        
        foreach ($tab as $newTabs){
            $groupe = new Groupe();
            $groupe->setNom($newTabs[0]);
            $groupe->addRole($this->getReference('ROLE_ADMIN'));
            $groupe->setDescription($newTabs[1]);
            $groupe->setActif($newTabs[3]);
            $groupe->setLdap($newTabs[2]);
            $groupe->setSupprimable($newTabs[4]);
            
             $manager->persist($groupe);
            
             if($newTabs[0]=="AllAdmin"){
                $ref = $groupe;
            }
        }
        
        $manager->flush();
        
        
         $this->addReference('ALLADMIN',$ref);
    }
    
    public function getOrder(){
        return 2;
    }
    
}