<?php

namespace PASS\GestionLogBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use \Doctrine\Common\Collections\ArrayCollection;
use PASS\AuthentificationLogBundle\Entity\Groupe;
use PASS\GestionLogBundle\Entity\priority;

/**
 * GroupeOrdinateur
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="PASS\GestionLogBundle\Entity\GroupeOrdinateurRepository")
 */
class GroupeOrdinateur {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=true)
     */
    private $actif;

    /**
     *
     * @ORM\Column(name="ordinateurs", type="array")                                             
     * 
     */
    private $ordinateurs;

   /**
     * @var boolean
     *
     * @ORM\Column(name="mail", type="boolean", nullable=true)
     */
    private $mail ;
    
     /**
     * 
     *
     * @ORM\ManyToOne(targetEntity="PASS\GestionLogBundle\Entity\priority")
     */
    private $priority ;
    
    /**
     *
     * @ORM\ManyToMany(targetEntity="PASS\AuthentificationLogBundle\Entity\Groupe")
     */        
    private $groupe;
    
    
    
    
    
     /**
     * Get id
     *
     * @return integer 
     */
    
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return GroupeOrdinateur
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return GroupeOrdinateur
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return GroupeOrdinateur
     */
    public function setActif($actif) {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif() {
        return $this->actif;
    }

    /**
     * Set ordinateurs
     *
     * @param array $ordinateurs
     * @return GroupeOrdinateur
     */
    public function setOrdinateurs($ordinateurs) {
        $this->ordinateurs[] = $ordinateurs;
       
        return $this;
    }

    /**
     * Get ordinateurs
     *
     * @return array 
     */
    public function getOrdinateurs() {
        return $this->ordinateurs;
    }
    
    function getPriority() {
        return $this->priority;
    }

    function setPriority($priority) {
        $this->priority = $priority;
    }

    
    public function __construct() {
        $this->ordinateurs = array();
        $this->groupe = new ArrayCollection();
        $this->actif = true;
    }

    public function __toString() {
        return $this->nom;
    }

    /**
     * Add ordinateur
     *
     * @param \PASS\GestionLogBundle\Entity\Systemevents $ordinateur
     * @return GroupeOrdinateur
     */
    public function addOrdinateur($ordinateur) {
        $this->ordinateurs[] = $ordinateur;

        return $this;
    }

    /**
     * Remove ordinateur
     *
     * @param \PASS\GestionLogBundle\Entity\Systemevents $ordinateur
     */
    public function removeOrdinateur($ordinateur) {
       $tab = array();
       $add = true;
       foreach($this->ordinateurs as $ordi){
           
           if($ordinateur !== $ordi)$tab []= $ordi ;
       }
       $this->ordinateurs = $tab;
              
    }
    

    function getMail() {
        return $this->mail;
    }

    function getGroupe() {
        return $this->groupe->toArray();
    }

    function setMail($mail) {
        $this->mail = $mail;
    }

    function setGroupe($groupe) {
        $this->groupe = $groupe;
    }
    function addGroupe(Groupe $groupe) {
        $this->groupe[] = $groupe;
    }
    function removeGroupe(Groupe $groupe) {
        $this->groupe->removeElement($groupe);
    }
        
    
    
    public function getSql() {
        
    }


        public function bool($var,$msgS="activé", $msgE="non activé") {
        if ($var)
            return " <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true<\"></span> ".$msgS;
        else
            return "<span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true<\"></span> ".$msgE;
    }
    public function getGroupeOrdiGen() {
        $strings = "";

        foreach ($this->ordinateurs as $ordi) {

            $strings = $strings . "- " . $ordi . "</br>";
        }

        return $strings;
    }
     public function getGroupeGen() {
        $strings = "";

        foreach ($this->groupe as $ordi) {

            $strings = $strings . "- " . $ordi . "</br>";
        }

        return $strings;
    }

    public function affichage() {
        return $this->nom;
    }

    public function resumer() {
        return array('Id' => $this->id,
            'Nom' => $this->nom,
            'Description' => $this->description,
            'ordinateur' => $this->getGroupeOrdiGen(),
            
            'envoi d\'email' => $this->bool($this->mail),
            "Groupe d'envoi"=>$this->getGroupeGen(),
        );
    }
    public function getEmailUser(array $email){
        
        foreach ($this->groupe as $groupe){
           foreach($groupe->getPersonnes() as $personne){
               if(!in_array($personne->getMail(),$email) && $personne->getMail() !== null){
                   $email[] = $personne->getMail();
               }
              
           }
    
        }
        return $email;
    }
}
