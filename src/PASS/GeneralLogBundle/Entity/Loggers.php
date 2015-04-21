<?php 

/*
Copyright 2015 Version 1.0.0
Pour le Pass, projet gestion de log.
@author Huygens Adrien
contact adrien.huygens@gmail.com
*/
/* 
    Created on : 11-févr.-2015, 10:35:25
    Author     : adrien
*/
namespace PASS\GeneralLogBundle\Entity;
use PASS\GestionLogBundle\Entity\Systemevents;

class Loggers{
    /**
     * 
     * @param type $em => $this->getDoctrine()->getManager();
     * @param type $string => message du log
     * @param type $priorityId => id de la prioriter
     * @param type $facilityId => fid de la faciliter
     */
    
    public function __construct($em,$string,$priorityId = 1,$facilityId = 10) {
    
  $fac= $em->getRepository("PASS\GestionLogBundle\Entity\Facility")->find($facilityId);
       $prio= $em->getRepository("PASS\GestionLogBundle\Entity\priority")->find($priorityId);
        $sysEvent = new Systemevents();
        $sysEvent->setFromhost("GDL-APPLICATION")
                ->setDevicereportedtime(new \DateTime('NOW'))
                ->setReceivedat(new \DateTime('NOW'))
                ->setFacility($fac)
                ->setPriority($prio)
                ->setSyslogtag("Application:GDL")
                ->setMessage($string)
        
        ;
       
        $em->persist($sysEvent);
        $em->flush();
        
    }
    
    
    
    
    
}