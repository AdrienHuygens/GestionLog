<?php
namespace PASS\GestionLogBundle\Entity;
/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 * 
 *  -> 'devicereportedtime,'
                . ' Facility, fromhost, message, syslogtag,'
                . ' nom from PASSGestionLogBundle:systemevents '
                . 'Inner Join PASSGestionLogBundle:priority On  systemevents.priority = priority.syslogid;')
                        
 */
use Doctrine\ORM\EntityRepository;
use PASS\GestionLogBundle\Entity\Filtre;
use PASS\GestionLogBundle\Entity\StatServeur;
use Doctrine\ORM\Tools\Pagination\Paginator;


class SystemeventsRepository extends EntityRepository  {

     public function getAllLog(Filtre $filtre, $repo,$page=1, $maxperpage=40) {
        
         $query = $this->createQueryBuilder('systemevent')
                        ->select('systemevent.id,systemevent.receivedat,systemevent.devicereportedtime , '
                                . 'systemevent.fromhost, systemevent.message, systemevent.syslogtag,'
                                . 'priority.nom,priority.description,priority.couleur,priority.couleurText,Facility.description, Facility.nom as nomf')
                        
                        ->join('systemevent.priority', 'priority')
                        ->join('systemevent.Facility', 'Facility')
                        ->addOrderBy('systemevent.id', 'DESC')
                       
                        
                       
                        ;
         
         $test = $filtre->filtrer($query,$repo);
         
         
         $test->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);
         return $test->getQuery()->execute();
        
    }
    
    public function getDateLog($date) {
        
         $query = $this->createQueryBuilder('systemevent')
                        
                        ->select(
                                 'systemevent.fromhost, count(systemevent.priority),priority.nom,priority.id as prioId, priority.couleur'
                                //. 'priority.nom,priority.description,priority.couleur,priority.couleurText,Facility.description, Facility.nom as nomf')
                        )
                        ->join('systemevent.priority', 'priority')
                       
                        ->where('systemevent.devicereportedtime <= :date')
                        ->setParameter(':date', $date)
                        ->groupBy('systemevent.fromhost, priority.id')
                       
                        
                       
                        ;
  
         return $query->getQuery()->execute();
        
    }
      public function DeleteLog($date){
        
        $query = $this->createQueryBuilder('systemevent')
                        ->delete()
                       
                        ->where('systemevent.devicereportedtime <= :date')
                        ->setParameter(':date', $date);
        return $query->getQuery()->execute();
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
                 
              $em->orWhere ("systemevent.fromhost = '".$nom."'")
                      
                      ;
              
                }
              }
               $filtre->gestionDate($em);
                        
                        return $em ->getQuery()->execute();
    }
    
   
      public function countTotal(Filtre $filtre,$repo )
    {
        $q = $this->_em->createQueryBuilder('systemevent')
            ->select('Count(systemevent)')
             ->from("PASSGestionLogBundle:Systemevents", "systemevent")
             ->join('systemevent.priority', 'priority')
                        ->join('systemevent.Facility', 'Facility')
        ;
 
        $test = $filtre->filtrer($q,$repo);
 
          return $test->getQuery()->getSingleScalarResult();
    }
   
}