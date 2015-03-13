<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PASS\GestionLogBundle\Entity;

class StatServeur  {
    
    private $nomServeur;
    
    private $nbLog;
    
    private $nb0 =0;
    
    private $nb1 =0;
    
    private $nb2 =0;
    
    private $nb3 =0;
    
    private $nb4 =0;
    
    private $nb5 =0;
    
    private $nb6 =0;
    
    private $nb7 =0;
    
    private $Tableaux;
    
    function __construct($nomServeur) {
        $this->nomServeur = $nomServeur;
        $this->Tableaux = array();
    }

    
    function getNomServeur() {
        return $this->nomServeur;
    }

    function getNbLog() {
        return $this->nbLog;
    }

    function getNbUrgence() {
        return $this->nb0;
    }

    function getNbAlerte() {
        return $this->nb1;
    }

    function getNbCritique() {
        return $this->nb2;
    }

    function getNbErreur() {
        return $this->nb3;
    }

    function getNbAttention() {
        return $this->nb4;
    }

    function getNbNotice() {
        return $this->nb5;
    }

    function getNbInfo() {
        return $this->nb6;
    }

    function getNbDebug() {
        return $this->nb7;
    }

    function setNomServeur($nomServeur) {
        $this->nomServeur = $nomServeur;
    }

    function setNbLog( $nbLog) {
        $this->nbLog += $nbLog;
    }

    function setNbUrgence($nbUrgence) {
        $this->nb0 = $nbUrgence;
    }

    function setNbAlerte($nbAlerte) {
        $this->nb1 = $nbAlerte;
    }

    function setNbCritique($nbCritique) {
        $this->nb2 = $nbCritique;
    }

    function setNbErreur($nbErreur) {
        $this->nb3 = $nbErreur;
    }

    function setNbAttention($nbAttention) {
        $this->nb4 = $nbAttention;
    }

    function setNbNotice($nbNotice) {
        $this->nb5 = $nbNotice;
    }

    function setNbInfo($nbInfo) {
        $this->nb6 = $nbInfo;
    }

    function setNbDebug($nbDebug) {
        $this->nb7 = $nbDebug;
    }

    public function generation($listing){
        
        foreach ($listing as $ligne){
           $tmp = "nb".$ligne['id']; 
           // var_dump($tmp);
           $this->setNbLog(intval($ligne['1']));
           $this->$tmp = $ligne['1'];
           $this->Tableaux[] =  array($ligne['nom'],$ligne['1']);
            //var_dump($ligne['1']);
            
        }
        
        //var_dump($this->nb6);
        
        
    }
    function getTableaux(){
        return $this->Tableaux;
    }
    
    
}