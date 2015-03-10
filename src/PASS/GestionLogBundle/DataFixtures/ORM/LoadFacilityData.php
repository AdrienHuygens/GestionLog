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
use PASS\GestionLogBundle\Entity\facility;

class LoadFacilityData implements \Doctrine\Common\DataFixtures\FixtureInterface {

    public function load(ObjectManager $manager) {

        $tab = array(
            array(0, 'Kernel', 'Messages du Noyau'),
            array(1, 'User', "Messages de niveau de l'utilisateur"),
            array(2, 'Mail', "Messages du system de mail"),
            array(3, 'Daemon', "Message d'un service"),
            array(4, 'Auth', "Messages de securiter et authorisation"),
            array(5, 'syslog', "Messages generer par syslog"),
            array(6, 'Ipr', "Messages du system d'impression"),
            array(7, 'news', "Messages du system de réseau"),
            array(8, 'uucp', "Messages system UUCP"),
            array(9, 'cron', "Messages du service clock"),
            array(10, 'security', "Messages sur la sécurité et les autorisations"),
            array(11, 'ftp', "Messages du service ftp"),
            array(12, 'ntp', "Messages du system NTP"),
            array(13, 'logaudit', "Messages des log audit"),
            array(14, 'logalert', "Logs des alerte "),
            array(15, 'horloge', "Messages du service d'horloge 2"),
            array(16, 'local0', "local use 0"),
            array(17, 'local1', "local use 1"),
            array(18, 'local2', "local use 2"),
            array(19, 'local3', "local use 3"),
            array(20, 'local4', "local use 4"),
            array(21, 'local5', "local use 5"),
            array(22, 'local6', "local use 6"),
            array(23, 'local7', "local use 7")
        );

        foreach ($tab as $newTab) {
            $type = new facility();
            $type->setId($newTab[0]);
            $type->setnom($newTab[1]);
            $type->setDescription($newTab[2]);
            $manager->persist($type);
        }
        $manager->flush();
    }

    public function getOrder() {
        return 1;
    }

}
