<?php

/* 
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */
namespace PASS\GestionLogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Filtre implements \Serializable{
    
  private $hosts ;
  
  private $dates;
  public function __construct() {
      $this->hosts = new ArrayCollection();
  }
    
    public function getHosts(){
       $tab = array();
        foreach($this->hosts as  $host)
        {
            $tab[] = $host;
            
        }
        
        return $tab;
        
        
       
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
         $tab =new ArrayCollection();
         
         foreach($this->hosts as  $host)
        {
            $tab[] = $host;
            
        }
        $this->hosts = $tab;
        $this->hosts->removeElement($host);
        
          //$hosttmp->removeElement($host);
         //unset($this->hosts[$host]);
        
       // $this->hosts->removeElement($host);
       // $this->hosts[] = $host;
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
    
     public function serialize() {
        return serialize(array(
        $this->hosts,$this->dates,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
       
        
        list (
             $this->hosts,$this->dates,
                ) = unserialize($serialized);
    }
    
    
}