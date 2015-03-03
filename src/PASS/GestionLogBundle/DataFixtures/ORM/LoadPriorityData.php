<?php

/*
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\GestionLogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;


class LoadPriorityData implements FixtureInterface {

    public function load(ObjectManager $manager) {

        $tab = array(
            array(0,'Urgence', 'System inutilisable','#000000' ),
            array(1, 'Alerte', "Action qui devrai être traiter immediatement",'#8A0808'),
            array(2, 'Critique', "Condition critique",'#FF0000'),
            array(3, 'Erreur', "Condition d'erreur",'#FF8000'),
            array(4, 'Attention', "Condition de detresse",'#FFBF00'),
            array(5, 'notice', "Message normal mais signaler",'#2E9AFE'),
            array(6, 'Info', "Message d'information",'#40FF00'),
            array(7, 'Debuf', "Message de debeugage",'#FBEFF5'),
            
        );

        foreach ($tab as $newTab) {
           
           
            $type = new \PASS\GestionLogBundle\Entity\priority();
             $type->setId($newTab[0]);
            $type->setnom($newTab[1]);
            $type->setDescription($newTab[2]);
            $type->setCouleur($newTab[3]);
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
