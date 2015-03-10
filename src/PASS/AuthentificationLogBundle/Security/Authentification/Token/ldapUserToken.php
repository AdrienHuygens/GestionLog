<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\AuthentificationLogBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ldapUserToken extends AbstractToken
{
    public $created;
    public $digest;
    public $nonce;
    
    public function __construct(array $roles = array()) {
        parent::__construct($roles);
        $this->setAuthenticated(count($roles)>0);
        // si nous avons des droits, nous somme donc authentifié. Nouveau principe de synfony
    }
    
    public function getCredentials()
    {
        return '';
    }
}

