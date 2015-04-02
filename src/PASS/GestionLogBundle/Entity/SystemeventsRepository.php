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
use PASS\GestionLogBundle\Entity\StatServeur;


class SystemeventsRepository extends EntityRepository  {

     public function getAllLog(Filtre $filtre, $repo) {
        
         $query = $this->createQueryBuilder('systemevent')
                        ->select('systemevent.id,systemevent.receivedat,systemevent.devicereportedtime , '
                                . 'systemevent.fromhost, systemevent.message, systemevent.syslogtag,'
                                . 'priority.nom,priority.description,priority.couleur,priority.couleurText,facility.description, facility.nom as nomf')
                        
                        ->join('systemevent.priority', 'priority')
                        ->join('systemevent.facility', 'facility')
                        ->addOrderBy('systemevent.id', 'DESC')
                       
                        
                       
                        ;
         
         $test = $filtre->filtrer($query,$repo);
         
         return $test->getQuery()->execute();
    }
    public function getHost(){
        
          return $this->createQueryBuilder('systemevent')
                        ->select('systemevent.fromhost')
                        ->addGroupBy('systemevent.fromhost')
                       
                        ->getQuery()->execute();
                        ;
    }
    
    public function getlogHost($host){
        
          return $this->createQueryBuilder('systemevent')
                        ->select('systemevent')
                        ->where("systemevent.fromhost = :nom")
                        ->setParameter(':nom', $host)
                        ->getQuery()->execute();
                        ;
    }
    public function getMin(){
        return $this->createQueryBuilder('systemevent')
                        ->select('min(systemevent.id)')
                        
                        ->getQuery()->execute();
                        ;
    }
    
    public function getStat($filtre,$name=null){
        
          $em = $this->createQueryBuilder('systemevent')
                        ->select('priority.id, priority.nom, priority.couleur ,count(systemevent)')
                        ->join('systemevent.priority', 'priority')
                        ->addGroupBy('priority.id')
                        
                        ;
          if ($name !== null) {
              
              foreach ($name as $nom){
              $em->orWhere ("systemevent.fromhost = '". $nom. "'");
                }
              }
               $filtre->gestionDate($em);
                        
                        return $em ->getQuery()->execute();
    }
   
}