<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\AuthentificationLogBundle\Service;
use Symfony\Component\Security\Core\User\UserInterface;
use PASS\AuthentificationLogBundle\Entity\Personne;
/**
* Service
*/
class Service
{
/**
* authenticate
*
* @param mixed $user
* @param mixed $password
*
* @return bool
*/
public function authenticate($user, $password)
{
// Web service call goes here
return true;
}
/**
* getUser
*
* @param string $username
*S
* @return User
*/
public function getUser($username)
{
$user = new Personne();
$user->setUsername($username);
return $user;
}
}