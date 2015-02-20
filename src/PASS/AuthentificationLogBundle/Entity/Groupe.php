<?php

namespace PASS\AuthentificationLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use PASS\AuthentificationLogBundle\Entity\Personne;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Groupe
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PASS\AuthentificationLogBundle\Entity\GroupeRepository")
 * 
 * @UniqueEntity(fields={"nom", "ldap"}, message="Le groupe existe déjà!!!")
 */
class Groupe implements \Symfony\Component\Security\Core\Role\RoleInterface {

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
     * @Assert\Length(min=2, minMessage="Vous devez avoir un nom de min {{ limit }} caractères.",
     * max =50, maxMessage="La longeur du nom ne peux pas dépasser {{ limit }} caractères")  
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/\W/", match = false ,message="La chaine ne peux pas avoir que des Chiffres et des lettres")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=50)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=True)
     *
     * @Assert\Type(type="string", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     * @Assert\Length(max=1000,maxMessage="Vous ne pouvez enregistrer une déscription de maximun {{ limit }} caractères.")
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ldap", type="boolean")
     * 
     * 
     * @Assert\Type(type="bool", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $ldap;

    /**
     * @var boolean
     *  
     * @ORM\Column(name="actif", type="boolean")
     * 
     * @Assert\Type(type="bool", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     * 
     */
    private $actif;

    /**
     * @ORM\ManyToMany(targetEntity="Personne", mappedBy="groupes")
     * 
     * @var Personne
     */
    private $personnes;

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
     * @return Groupe
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
     * @return Groupe
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
     * Set ldap
     *
     * @param boolean $ldap
     * @return Groupe
     */
    public function setLdap($ldap) {
        $this->ldap = $ldap;

        return $this;
    }

    /**
     * Get ldap
     *
     * @return boolean 
     */
    public function getLdap() {
        return $this->ldap;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Groupe
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
     * Constructor
     */
    public function __construct() {
        $this->personnes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add personnes
     *
     * @param \PASS\AuthentificationLogBundle\Entity\Personne $personnes
     * @return Groupe
     */
    public function addPersonne(\PASS\AuthentificationLogBundle\Entity\Personne $personnes) {
        $this->personnes[] = $personnes;

        return $this;
    }

    /**
     * Remove personnes
     *
     * @param \PASS\AuthentificationLogBundle\Entity\Personne $personnes
     */
    public function removePersonne(\PASS\AuthentificationLogBundle\Entity\Personne $personnes) {
        $this->personnes->removeElement($personnes);
    }

    /**
     * Get personnes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPersonnes() {
        return $this->personnes;
    }

    public function __toString() {
        return $this->nom;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Role
     */
    public function setRole($role) {
        $this->role = $role;
        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole() {
        return $this->role;
    }
    
     public function affichage(){
        return $this->nom;
    }
    public function type(){
        if ($this->ldap) return "LDAP";
        else return "local";
        
            }
     public function activiter(){
        if ($this->actif) return "Groupe activé <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true<\"></span>";
        else return "Groupe Désactivié <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true<\"></span>";
        
            }
            
     public function resumer(){
         
         $tab = array('Id' => $this->getId(),
                      'Nom' => $this->getNom(),
                      'Description' => $this->getDescription(),
                        'Type de groupe' => $this->type(),
                        'Information' => $this->activiter());
         
      return $tab;
             
             
         
         
         
     }

}
