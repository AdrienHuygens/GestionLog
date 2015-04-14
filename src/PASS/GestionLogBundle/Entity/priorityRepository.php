<?php
namespace PASS\GestionLogBundle\Entity;
/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 * 
 *  -> 'devicereportedtime,'
                . ' facility, fromhost, message, syslogtag,'
                . ' nom from PASSGestionLogBundle:systemevents '
                . 'Inner Join PASSGestionLogBundle:priority On  systemevents.priority = priority.syslogid;')
                        
 */
use Doctrine\ORM\EntityRepository;



class priorityRepository extends EntityRepository  {

      public function getPriority(){
        
          return $this->createQueryBuilder('prio')
                        ->select('prio.id,prio.nom')
                        ->getQuery()->execute();
                        ;
    }
    public function getPriorityMin(){
        
          return $this->createQueryBuilder('prio')
                        ->select('prio')
                        ->where("prio.id <= 3")
                        ->getQuery()->execute();
                        ;
    }
   
}