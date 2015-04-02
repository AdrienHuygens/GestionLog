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

class ConfigurationSql{
    
    /**
     *
     * 
     */
    private $database_driver;
    /**
     *
     * 
     */
    private $database_host;
   
    private $database_port;
    /**
     *
     * 
     *
     */
    private $database_name;
    /**
     *
     * 
     */
    private $database_user;
    
    
    private $databasePassword;
    
    
    public function __construct() {
              $yaml = new Parser();
        $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parameters.yml'));

        $this->database_driver = $value['parameters']['database_driver'];
        $this->database_host = $value['parameters']['database_host'];
        $this->database_port = $value['parameters']['database_port'];
        $this->database_name = $value['parameters']['database_name'];
        $this->database_user = $value['parameters']['database_user'];
        $this->databasePassword = $value['parameters']['database_password'];
       
        
    }
    public function load(){
         $yaml = new Parser();
        $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parameters.yml'));

        $this->database_driver = $value['parameters']['database_driver'];
        $this->database_host = $value['parameters']['database_host'];
        $this->database_port = $value['parameters']['database_port'];
        $this->database_name = $value['parameters']['database_name'];
        $this->database_user = $value['parameters']['database_user'];
        $this->databasePassword = $value['parameters']['database_password'];
    }
    
    function getDatabaseDriver() {
        return $this->database_driver;
    }
    function getDatabasePassword() {
        return $this->databasePassword;
    }

    function setDatabasePassword($databasePassword) {
        $this->databasePassword = $databasePassword;
    }

        function getDatabaseHost() {
        return $this->database_host;
    }

    function getDatabasePort() {
        return $this->database_port;
    }

    function getDatabaseName() {
        return $this->database_name;
    }

    function getDatabaseUser() {
        return $this->database_user;
    }

    function setDatabaseDriver($database_driver) {
        $this->database_driver = $database_driver;
    }

    function setDatabaseHost($database_host) {
        $this->database_host = $database_host;
    }

    function setDatabasePort($database_port) {
        $this->database_port = $database_port;
    }

    function setDatabaseName($database_name) {
        $this->database_name = $database_name;
    }

    function setDatabaseUser($database_user) {
        $this->database_user = $database_user;
    }

     public function Enregistrer(){
         $yaml = new Parser();
         $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parameters.yml'));
         $value['parameters']['database_driver']  = $this->database_driver;
         $value['parameters']['database_host'] = $this->database_host;
         $value['parameters']['database_port'] =  $this->database_port;
         $value['parameters']['database_name'] = $this->database_name;
         $value['parameters']['database_user'] = $this->database_user;
         $value['parameters']['database_password'] = $this->databasePassword ;
         $dumper = new Dumper();

        $yaml = $dumper->dump($value,2);
        file_put_contents(__DIR__.'/../../../../app/config/parameters.yml', $yaml);

     }

}