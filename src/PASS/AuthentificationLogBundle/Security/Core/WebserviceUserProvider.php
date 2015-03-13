<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\AuthentificationLogBundle\Security\Core;
 
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use PASS\AuthentificationLogBundleBundle\Service\Service;
use PASS\Authentification\Entity\Personne;
use Doctrine\ORM\EntityManager;
 
class WebserviceUserProvider implements UserProviderInterface
{
    private $service;
    private $em;
 
    public function __construct( $service, EntityManager $em)
    {
        $this->service  = $service;
        $this->em       = $em;
    }
 
    public function loadUserByUsername($username)
    {
        // Do we have a local record?
       /*if ($user = $this->findUserBy(array('email' => $username))) {
            return $user;
        }*/
 
        // Try service
        if ($record = $this->service->getUser($username)) {
            // Set some fields
            $user = new User();
            $user->setUsername($username);
            return $user;
        }
 
        throw new UsernameNotFoundException(sprintf('No record found for user %s', $username));
    }
 
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }
 
    public function supportsClass($class)
    {
        return $class === 'PASS\Authentification\Entity\Personne';
    }
 
    protected function findUserBy(array $criteria)
    {
        $repository = $this->em->getRepository('PASS\Authentification\Entity\Personne');
        return $repository->findOneBy($criteria);
    }
}