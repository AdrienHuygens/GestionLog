<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */
namespace PASS\AuthentificationLogBundle\Security\Core\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider;

class ldapAuthenticationProvider extends DaoAuthenticationProvider{
    
    public function checkAuthentication(\Symfony\Component\Security\Core\User\UserInterface $user, \Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken $token) {
        
        
        
        
        
        
        
        
        parent::checkAuthentication($user, $token);
    } 
    
    
    
}

