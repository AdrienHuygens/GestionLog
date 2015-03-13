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
            array(0,'Urgence', 'System inutilisable','#000000','#FFFFFF' ),
            array(1, 'Alerte', "Action qui devrai être traiter immediatement",'#8A0808','#FFFFFF'),
            array(2, 'Critique', "Condition critique",'#E30303','#000000'),
            array(3, 'Erreur', "Condition d'erreur",'#F46A00','#000000'),
            array(4, 'Attention', "Condition de detresse",'#F4F400','#000000'),
            array(5, 'notice', "Message normal mais signaler",'#40FF00','#000000'),
            array(6, 'Info', "Message d'information",'#40FF00','#000000'),
            array(7, 'Debuf', "Message de debeugage",'#FBEFF5','#000000'),
            
        );

        foreach ($tab as $newTab) {
           
           
            $type = new \PASS\GestionLogBundle\Entity\priority();
             $type->setId($newTab[0]);
            $type->setnom($newTab[1]);
            $type->setDescription($newTab[2]);
            $type->setCouleur($newTab[3]);
            $type->setCouleurText($newTab[4]);
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
