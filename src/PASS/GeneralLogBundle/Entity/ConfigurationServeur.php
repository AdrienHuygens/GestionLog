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

class ConfigurationServeur{
    
    /**
     *
     * 
     */
    private $Srv_date_log;
    /**
     *
     * 
     */
  
    
    
    public function __construct() {
              $yaml = new Parser();
        $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parametreLdap.yml'));

        $this->Srv_date_log = $value['parameters']['Srv_Date_log'];
      
       
        
    }
    public function load(){
         $yaml = new Parser();
                      
        $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parametreLdap.yml'));

        $this->Srv_date_log = $value['parameters']['Srv_Date_log'];
       
    }
    
    function getSrvDateLog() {
        return $this->Srv_date_log;
    }

    function setSrvDateLog($Srv_date_log) {
        $this->Srv_date_log = $Srv_date_log;
    }

    
     public function Enregistrer(){
         $yaml = new Parser();
         $value = $yaml->parse(file_get_contents(__DIR__.'/../../../../app/config/parametreLdap.yml'));
         $value['parameters']['Srv_Date_log']  = $this->Srv_date_log;
        
         $dumper = new Dumper();

        $yaml = $dumper->dump($value,2);
        file_put_contents(__DIR__.'/../../../../app/config/parametreLdap.yml', $yaml);

     }

}