<?php

namespace PASS\GestionLogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * StatistiqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StatistiqueRepository extends EntityRepository
{
    
    
    public function getStat($filtre,$name=null){
        
          $em = $this->createQueryBuilder('stat')
                        ->select('stat')
                        
                        ->addGroupBy('stat.id')
                        
                        ;
          if ($name !== null) {
              
              foreach ($name as $nom){
              $em->orWhere ("stat.serveur = '".$nom."'")
                      ;
              
                }
              }
               $filtre->gestionDate($em,'stat.date');
                        
                        return $em ->getQuery()->execute();
    }
}