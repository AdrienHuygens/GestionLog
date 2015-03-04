<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\GestionLogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Filtre {
    
  private $hosts = array();
  
  private $dates;
    
    public function getHosts(){
       
        return $this->hosts;
    }
    public function getDates(){
        return $this->dates;
     
    }
    public function setDates($date){
        $this->dates = $date;
     
    }
  
    
    
    public function addHost($host){
        
        $this->hosts[] = $host;
        
    }
     public function removeHost($host) {

        $this->hosts->removeElement($host);
    }
    
     public function addDate( $date){
        
        $this->dates[] = $date;
        
    }
     public function removeDate($date) {

        $this->dates->removeElement($date);
    }
    
    public function filtrer($query){
        
       
        foreach ($this->hosts as $hosts){

                
          $query->orWhere("systemevent.fromhost ='".$hosts."'");
                 //->setParameter('hosts', $hosts);
             
        }
        if(isset($this->dates) && $this->dates->getSigne() !== null){
           
        $query->andWhere($this->dates->getSql());
        }
        return $query;
    }
    
    
}