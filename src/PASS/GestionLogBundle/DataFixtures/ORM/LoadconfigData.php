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


class LoadconfigData implements FixtureInterface {

    public function load(ObjectManager $manager) {

      $conf = new \PASS\GestionLogBundle\Entity\config();
      $conf->setNom("ligne");
           $conf->setQuantiter(0);
             
      $conf->setId(0);
        
        
            $manager->persist($conf);
        
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
