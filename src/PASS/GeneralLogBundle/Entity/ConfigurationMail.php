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

class ConfigurationMail {

    private $titre;
    private $body;
    private $css;
    private $url;
    private $strict;

    public function __construct() {

        $this->url = __DIR__ . '/../../GestionLogBundle/Resources/views/mail/';
        $monfichier = fopen($this->url . 'titreMail.html.twig', 'r+');
        $this->titre = fgets($monfichier);
        fclose($monfichier);

        $this->body = file_get_contents($this->url . 'Corp.html.twig');
        
        $this->css = file_get_contents($this->url . 'cssMail.html.twig');
    }

    function getTitre() {
        return $this->titre;
    }

    function getBody() {
        return $this->body;
    }

    function getCss() {
        return $this->css;
    }

    function setTitre($titre) {
        $this->titre = $titre;
    }

    function setBody($body) {
        $this->body = $body;
    }

    function setCss($css) {
        $this->css = $css;
    }
    function getStrict() {
        return $this->strict;
    }

    function setStrict($strict) {
        $this->strict = $strict;
    }

  public function verificationTwig() {
        $cpt = 0;
         $string = $this->body;
        $len = strlen($string);
        $start = false;
        $text= "";
        $check="";
        $liste = array();
        $tab= array("log.id",'log.receivedat',"log.devicereportedtime","log.facility.nom","log.facility.description","log.priority.nom",
                "log.priority.description","log.priority.couleur","log.priority.couleurText","log.fromhost","log.message","log.syslogtag");
       
        for ($i = 0; $i < $len; $i++) {
            if (($string[$i] ==='{' && $i+1 < $len && $string[$i+1] ==='{') ) {
                $start = true;
                $i = $i+2;
                
            }
            if ($start && $string[$i] ==='{'){
              
                $check .= "flute";
            }
            
             if (($string[$i] ==='}' && $i+1 < $len && $string[$i+1] ==='}') ) {
               
                   if (!$start && $i+2 < $len && $string[$i+2] ==='}'){
              
                $check .= "flute";
            }
                 
                 
                $i = $i +1;
                 $start = false;
                 $test=false;
                 foreach($tab as $val){
                    
                     if($text == $val) { $test = true;
                     break;}
                 }
                 if (!$test){ $check .= "flute";}//retourne une erreur 
                 
                 $liste["{{".$text."}}"] = $text; 
                 
                 $text ="";
            }
           
             
            if ($start){
                
                if($string[$i] !== ' '  ){
                    $text .= $string[$i];
                }
            }
             
           
        
    }
    return $liste;
}
    public function Enregistrer() {
        
      
        
        file_put_contents($this->url .'titreMail.html.twig',  $this->titre);
        file_put_contents($this->url .'Corp.html.twig',  $this->body);
        file_put_contents($this->url .'cssMail.html.twig',  $this->css);
    }
    public function Previsualisation() {
        
      
        
        file_put_contents($this->url .'tempTitreMail.html.twig',  $this->titre);
        file_put_contents($this->url .'tempCorp.html.twig',  $this->body);
        file_put_contents($this->url .'tempCssMail.html.twig',  $this->css);
    }

}
