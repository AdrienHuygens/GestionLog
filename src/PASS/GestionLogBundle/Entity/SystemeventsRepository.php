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
use PASS\GestionLogBundle\Entity\Systemevents;

class SystemeventsRepository extends EntityRepository  {

     public function getAllLog() {
        return $this->createQueryBuilder('systemevent')
                        ->select('systemevent.id,systemevent.devicereportedtime , '
                                . 'systemevent.fromhost, systemevent.message, systemevent.syslogtag,'
                                . 'priority.nom,priority.couleur, facility.nom as nomf')
                        
                        ->join('systemevent.priority', 'priority')
                        ->join('systemevent.facility', 'facility')
                        ->addOrderBy('systemevent.devicereportedtime', 'DESC')
                       
                        ->getQuery()->execute();
    }
   
}