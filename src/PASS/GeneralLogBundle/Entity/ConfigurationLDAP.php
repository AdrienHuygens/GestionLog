<?php

/* 


   * Copyright 2015 Version 1.0.0
   * Pour le Pass, projet gestion de log.
   * @author Huygens Adrien
   * contact adrien.huygens@gmail.com
 
 */

namespace PASS\GeneralLogBundle\Entity;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Validator\Constraints as Assert;

class ConfigurationLDAP{
    
    /**
     *
     * 
     */
    private $ldap_server;
    /**
     *
     * 
     */
    private $ldap_port;
   
    private $ldap_dn;
    /**
     *
     * 
     *
     */
    private $ldap_filtre;
    /**
     *
     * 
     */
    private $ldap_connexion;
    private $url;
  
    public function __construct() {
              $yaml = new Parser();
              $this-> url = __DIR__.'/../../../../app/config/parametreLdap.yml';
        $value = $yaml->parse(file_get_contents($this->url));

        $this->ldap_server = $value['parameters']['ldap_server'];
        $this->ldap_port = $value['parameters']['ldap_port'];
        $this->ldap_dn= $value['parameters']['ldap_dn'];
        $this->ldap_filtre = $value['parameters']['ldap_filtre'];
        $this->ldap_connexion = $value['parameters']['ldap_connexion'];
        
       
        
    }
    
    function getLdapServer() {
        return $this->ldap_server;
    }

    function getLdapPort() {
        return $this->ldap_port;
    }

    function getLdapDn() {
        return $this->ldap_dn;
    }

    function getLdapFiltre() {
        return $this->ldap_filtre;
    }

    function getLdapConnexion() {
        return $this->ldap_connexion;
    }

    function setLdapServer($ldap_server) {
        $this->ldap_server = $ldap_server;
    }

    function setLdapPort($ldap_port) {
        $this->ldap_port = $ldap_port;
    }

    function setLdapDn($ldap_dn) {
        $this->ldap_dn = $ldap_dn;
    }

    function setLdapFiltre($ldap_filtre) {
        $this->ldap_filtre = $ldap_filtre;
    }

    function setLdapConnexion($ldap_connexion) {
        $this->ldap_connexion = $ldap_connexion;
    }

    
     public function Enregistrer(){
         $yaml = new Parser();
         $value = $yaml->parse(file_get_contents($this->url));
         $value['parameters']['ldap_server']  = $this->ldap_server;
         $value['parameters']['ldap_port'] = $this->ldap_port;
         $value['parameters']['ldap_dn'] =  $this->ldap_dn;
         $value['parameters']['ldap_filtre'] = $this->ldap_filtre;
         $value['parameters']['ldap_connexion'] = $this->ldap_connexion;
        $dumper = new Dumper();
        $yaml = $dumper->dump($value,2);
        file_put_contents($this->url, $yaml);

     }

}