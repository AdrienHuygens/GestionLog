<?php

namespace PASS\AuthentificationLogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * RolesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends EntityRepository
{
    
    public function getType(){
        return $this->createQueryBuilder('r')
                        ->select('r')
                        ->where("r.type != '' ")
                        
                        ->getQuery()->execute();
        
        
    }
}