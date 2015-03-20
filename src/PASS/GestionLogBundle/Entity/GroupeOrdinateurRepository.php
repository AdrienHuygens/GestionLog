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
use PASS\GestionLogBundle\Entity\Filtre;

class GroupeOrdinateurRepository extends EntityRepository  {

     
    public function getGroupe(){
        
          return $this->createQueryBuilder('groupe')
                        ->select('groupe.id,groupe.nom')
                       
                        ->getQuery()->execute();
                        ;
    }
    public function getNameGroupe(){
        
          return $this->createQueryBuilder('groupe')
                        ->select('groupe')
                       
                        ->getQuery()->execute();
                        ;
    }
   
   
}