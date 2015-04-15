<?php

/*
 * Copyright 2015 Version 1.0.0
 * Pour le Pass, projet gestion de log.
 * @author Huygens Adrien
 * contact adrien.huygens@gmail.com
 */

namespace PASS\GestionLogBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PASS\GestionLogBundle\Entity\GroupeOrdinateur;

class Filtre implements \Serializable {

    private $hosts;
    private $dates;
    private $groupes;
    private $priority;
    private $nbPage = 40;
    
    
    
   

        public function __construct($nbPage=40) {
        $this->groupes = new ArrayCollection();
        $this->hosts = new ArrayCollection();
        $this->priority = new ArrayCollection();
        $this->dates = new Date();
        $this->nbPage = $nbPage;
        
    }
    public function getNbPage(){
        return $this->nbPage;
        }
    public function setNbPage($nbPage){
        $this->nbPage = $nbPage;
    }

    public function getHosts() {
        $tab = array();
        foreach ($this->hosts as $host) {
            $tab[] = $host;
        }

        return $tab;
    }

    public function getGroupes() {
        $tab = array();
       if ($this->groupes != null) {
        foreach ($this->groupes as $groupe) {
            $tab[] = $groupe;
        }
       }
        return $tab;
    }
    function getPriority() {
        $tab = array();
         if ($this->groupes != null) {
        foreach ($this->priority as $prio) {
            $tab[] = $prio;
        }
         }
        return $tab;
    }

    public function getDates() {
        return $this->dates;
    }

    public function setDates($date) {
        $this->dates = $date;
    }

    public function addHost($host) {

        $this->hosts[] = $host;
    }

    public function addGroupe($groupe) {
        
        $this->groupes[] = $groupe;
    }

    public function setGroupes($groupe) {

        $this->groupes[] = $groupe;
    }
     public function setPriority($priority) {

        $this->priority = $priority;
    }

    public function removeGroupe($groupe) {
        $tab = new ArrayCollection();

        foreach ($this->groupes as $groupee) {
            if ($groupee !== $groupe)
                $tab[] = $groupee;
        }
        $this->groupes = $tab;
        //$this->groupes->removeElement($groupe);
    }

    public function removeHost($host) {
        $tab = new ArrayCollection();

        foreach ($this->hosts as $hoste) {
            $tab[] = $host;
        }
        $this->hosts = $tab;
        $this->hosts->removeElement($host);

        //$hosttmp->removeElement($host);
        //unset($this->hosts[$host]);
        // $this->hosts->removeElement($host);
        // $this->hosts[] = $host;
    }
    public function removePriority($priority) {
        $tab = new ArrayCollection();

        foreach ($this->priority as $prio) {
            if ($prio !== $priority)
                $tab[] = $prio;
        }
        $this->groupes = $tab;
        //$this->groupes->removeElement($groupe);
    }
    
     

    

    public function addDate($date) {

        $this->dates[] = $date;
    }

    public function removeDate($date) {

        $this->dates->removeElement($date);
    }

    public function filtrer($query, $repo) {
    //if (isset($this->dates)
       
        foreach ( $this->hostSql($repo) as $hosts) {


            $query->orWhere("systemevent.fromhost ='" . $hosts . "'");
            
            //->setParameter('hosts', $hosts);
        }
         $this->gestionPriority($query);
        $this->gestionDate($query);
       
        
        return $query;
    }
    public function gestionDate($query,$variable="systemevent.devicereportedtime"){
       
        if (isset($this->dates) ) {
            if ($this->dates->getSql($variable) != null)
            $query->andWhere($this->dates->getSql($variable));
        }
    }
    private function gestionPriority($query){
         $nb = 0;
         
        foreach ( $this->priority as $prio) {
            
            if($nb==0) $query->andWhere("priority.id =".$prio);
            else $query->orWhere("priority.id =".$prio);
            $nb++;
        }
        
    }

    public function hostSql($repo) {
        $tableau = array();
        
        $tableau = $this->getHosts();
        
        if ($this->groupes != null) {
            foreach ($this->groupes as  $groupess) {
                $groupesR =$repo->find($groupess);
                foreach ($groupesR->getOrdinateurs() as $tmp){
                    $i = 0;
                    foreach($tableau as $tab)
                    {
                        if ($tab == $tmp) $i = 1;
                    }
                    if ($i ===0)$tableau[] = $tmp;
                }
            }
        }
       return $tableau;
    }

    
    
    
    public function serialize() {
        return serialize(array(
            $this->hosts, $this->dates, $this->priority,$this->groupes,$this->nbPage,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {


        list (
                 $this->hosts, $this->dates, $this->priority,$this->groupes,$this->nbPage,
                ) = unserialize($serialized);
    }

}
