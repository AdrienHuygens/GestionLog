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


class LoadRoleData extends AbstractFixture implements OrderedFixtureInterface{
    
    public function load(ObjectManager $manager){
        
        $tab  = array(
            array('Default','Role par default attribuer à tous' ,'ROLE_DEFAULT',null),
            array ('Admin: Tout les droits', 'Role Administrateur', 'ROLE_ADMIN',"ROLE_ADMIN"),
            array ('Gestion utilisateur: Creation d\'un utilisateur', 'Création d\'un utilisateur', 'ROLE_USER_C','ROLE_USER'),
            array ('Gestion utilisateur: Voir les utilisateurs', 'lecture d\'un utilisateur', 'ROLE_USER_R','ROLE_USER'),
            array ('Gestion utilisateur: Modifier des utilisateurs', 'modification d\'un utilisateur', 'ROLE_USER_U','ROLE_USER'),
            array ('Gestion utilisateur: Supprimer des utilisateurs', 'Supression d\'un utilisateur', 'ROLE_USER_D','ROLE_USER'),
            
            array ('Gestion Groupe: Creation d\'un groupe', 'Création d\'un utilisateur', 'ROLE_GROUPE_C','ROLE_GROUPE'),
            array ('Gestion Groupe: Voir les groupes', 'lecture d\'un utilisateur', 'ROLE_GROUPE_R','ROLE_GROUPE'),
            array ('Gestion Groupe: Modifier des groupes', 'modificatop, d\'un utilisateur', 'ROLE_GROUPE_U','ROLE_GROUPE'),
            array ('Gestion Groupe: supprimer un groupe', 'Supression d\'un groupe', 'ROLE_GROUPE_D','ROLE_GROUPE'),
            
            array ('Gestion LDAP: Importer des utilisateurs', 'Importer des utilisateurs ldap en bdd', 'ROLE_LDAP_I','ROLE_LDAP'),
            
             array ('Gestion Configuration: Voir la configuration', 'Permet de voir le system de configuration', 'ROLE_CONFIGURATION_R','ROLE_CONFIGURATION'),
             array ('Gestion Configuration: Modifier la configuration', 'Permet de modifier la configuration', 'ROLE_CONFIGURATION_U','ROLE_CONFIGURATION'),
            
            array ('Gestion Groupe d\'ordinateur: Crée les groupes', 'permet de creer les groupe d\'ordinateur', 'ROLE_GROUPE_ORDI_C','ROLE_GROUPE_ORDI'),
            array ('Gestion Groupe d\'ordinateur: Voir les groupes', 'permet de voir les groupe d\'ordinateur', 'ROLE_GROUPE_ORDI_R','ROLE_GROUPE_ORDI'),
            array ('Gestion Groupe d\'ordinateur: modifier les groupes', 'permet de modifier les groupe d\'ordinateur', 'ROLE_GROUPE_ORDI_U','ROLE_GROUPE_ORDI'),
            array ('Gestion Groupe d\'ordinateur: supprimer les groupes', 'permet de supprimer les groupe d\'ordinateur', 'ROLE_GROUPE_ORDI_D','ROLE_GROUPE_ORDI'),
            
             array ('Voir les Logs', 'permet de voir les log', 'ROLE_LOG_R','ROLE_LOG'),
            array ('Voir les Stats', 'permet de voir les stats', 'ROLE_STAT_R','ROLE_STAT'),
             array ('Gestion des droits sur les utilisateurs et groupes', 'permet modifier les droits utilisateur', 'ROLE_DROIT_U','ROLE_DROIT'),
             
        );
        
        $ref = new Role();
         $ref2 = new Role();
        
        foreach ($tab as $newTab){
            $role = new Role();
            
            $role->setNom($newTab[0]);
             $role->setDescription($newTab[1]);
            $role->setRole($newTab[2]);
           $role->setType($newTab[3]);
            
            $manager->persist($role);
            
            if($newTab[2]=="ROLE_DEFAULT"){
                $ref = $role;
            }
             if($newTab[2]=="ROLE_ADMIN"){
                $ref2 = $role;
            }
        }
        
        $manager->flush();
        
        $this->addReference('ROLE_DEFAULT',$ref);
         $this->addReference('ROLE_ADMIN',$ref2);
    }
    
    public function getOrder(){
        return 1;
    }
    
}