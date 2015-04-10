<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\GestionLogBundle\Entity;






class Date implements \Serializable{

    
       
    private $signe;
    
    private $date1;
    
    private $date2;
    
    function __construct() {
        //$this->signe  = null;
      
       $this->date2 = new \DateTime('NOW');
       date_add($this->date2, date_interval_create_from_date_string('1 days'));
    }

    public function getSigne() {
        return $this->signe;
    }

   public function getDate1() {
        return $this->date1;
    }

    public function getDate2() {
        return $this->date2;
    }

    public function setSigne($signe) {
        $this->signe = $signe;
    }

     public function setDate1($date1) {
        $this->date1 = $date1;
    }

     public function setDate2($date2) {
        $this->date2 = $date2;
    }


    public function  getDate(){
        return array($this->signe,  $this->date1,  $this->date2);
    }
    public function getSql(){
        
        
        if ($this->signe ==="=" || $this->signe ==="<"){
            if ($this->signe ==="="){
                
                //$this->date1->strtotime(' +59 Seconde');
                //var_dump($this->date1);
                return "systemevent.devicereportedtime BETWEEN '".$this->date1->format("Y-m-d H:i:s")."' AND '". $this->date1->modify('+59 second')->format("Y-m-d H:i:s")."'";
            }
            return "systemevent.devicereportedtime" .$this->signe."'".$this->date1->format("Y-m-d H:i:s")."'";
        }
        elseif ($this->signe ==="between"){
           return "systemevent.devicereportedtime BETWEEN '".$this->date1->format("Y-m-d H:i:s")."' AND '". $this->date2->format("Y-m-d H:i:s")."'"; 
        }
        else{
            $this->date1 = new \DateTime('NOW');
             $this->date2 = new \DateTime('NOW');
            date_sub($this->date1, date_interval_create_from_date_string('1 days'));
            date_add($this->date2, date_interval_create_from_date_string('1 days'));
             return "systemevent.devicereportedtime BETWEEN '".$this->date1->format("Y-m-d H:i:s")."' AND '". $this->date2->format("Y-m-d H:i:s")."'"; 
        }
        
            
            
        
    }
    
    
     public function serialize() {
        return serialize(array(
            $this->signe,$this->date1,$this->date2,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
             $this->signe,$this->date1,$this->date2,
                ) = unserialize($serialized);
    }
   
}
