<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\GestionLogBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use PASS\GestionLogBundle\Entity\StatServeur;

class statistiquelog  {
    
    
    private $filtre;
    
    private $doctrine;
    private $stats;
    
     
   public function __construct($filtre,  $doctrine) {
        $this->filtre = $filtre;
         $this->doctrine = $doctrine;
         $this->stats = new ArrayCollection ();
    }
    public function getFiltre() {
        return $this->filtre;
    }

     public function setFiltre($filtre) {
        $this->filtre = $filtre;
    }
    
    
    public function setDoctrine($doctrine) {
        $this->doctrine = $doctrine;
    }
    
    


    public function stat()
    {
        $stat = new StatServeur('test');
        $repo = $this->doctrine->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
        $listing = $repo->getStat();
        $stat->generation($listing);
        
        $stats[] = $stat;
        return $stats;
        
        
    }
   
    

}