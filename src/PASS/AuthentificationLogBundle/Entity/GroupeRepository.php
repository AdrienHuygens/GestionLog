<?php

namespace PASS\AuthentificationLogBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GroupeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GroupeRepository extends EntityRepository
{
    function getAllGroupe() {
        return $this->createQueryBuilder('p')
                        ->select('p')
                        ->orderBy("p.nom")
                        ->getQuery()->execute();
    }
    
    
}
