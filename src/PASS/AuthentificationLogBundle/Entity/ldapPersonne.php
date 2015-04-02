<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */

namespace PASS\AuthentificationLogBundle\Entity;

class ldapPersonne{
    
    private $username;
    private $diplayName;
    private $mail;
    
    function __construct($username, $diplayName, $mail) {
        $this->username = $username;
        $this->diplayName = $diplayName;
        $this->mail = $mail;
    }

    function getUsername() {
        return $this->username;
    }

    function getDiplayName() {
        return $this->diplayName;
    }

    function getMail() {
        return $this->mail;
    }


   public function __toString() {
       return $this->diplayName."  ".$this->username."  ".$this->mail;
   } 
    
    
    
    
}