<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\GestionLogBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use PASS\GestionLogBundle\Entity\StatServeur;

class Statistiquelog  {
    
    
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
        
         $repo = $this->doctrine->getRepository("PASS\GestionLogBundle\Entity\Systemevents");
         $repo2 = $this->doctrine->getRepository("PASS\GestionLogBundle\Entity\GroupeOrdinateur");
         $nom = $this->filtre->hostSql($repo2);
         if($this->filtre->getGroupes() == null){
         $stat = new StatServeur("Tout les Serveur");
       
            $listing = $repo->getStat($this->filtre);
            $stat->generation($listing);
            
            $this->stats[] = $stat;
         }
         else{
             foreach ($this->filtre->getGroupes() as  $groupess) {
                $groupesR =$repo2->find($groupess);
                
                $stat = new StatServeur("Groupe de Serveur: ".$groupesR->getNom());
                $listing = $repo->getStat($this->filtre,$groupesR->getOrdinateurs());
                $stat->generation($listing);
            
                $this->stats[] = $stat;
             }
             //$stat = new StatServeur("Tout les Serveur");
         }
         foreach($nom as $name){
            $tab = array();
            $tab[] = $name;
           
             $stat = new StatServeur(" Nom du Serveur: ".$name);
       
            $listing = $repo->getStat($this->filtre ,$tab);
            $stat->generation($listing);

            $this->stats[] = $stat;
         }
          
        return $this->stats;
        
        
    }
   
    

}