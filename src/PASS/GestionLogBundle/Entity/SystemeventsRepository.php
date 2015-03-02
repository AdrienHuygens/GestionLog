<?php
namespace PASS\GestionLogBundle\Entity;
/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
use Doctrine\ORM\EntityRepository;


class SystemeventsRepository extends EntityRepository  {

     public function getAllLog() {
        return $this->createQueryBuilder('l')
                        ->select(array('l','p'))
                        ->leftJoin('l.syslogId', 'p')
                        
                        ->getQuery()->execute();
    }
    
}