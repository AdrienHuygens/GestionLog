<?php

namespace PASS\AuthentificationLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use \PASS\AuthentificationLogBundle\Entity\Groupe;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use \Symfony\Component\Validator\Constraints\DateTime;

/**
 * Personne
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PASS\AuthentificationLogBundle\Entity\PersonneRepository")
 * 
 * @UniqueEntity("username", message="Le nom d'utilisateur éxiste déjà!!!")
 */
class Personne implements AdvancedUserInterface, \Serializable, EquatableInterface {

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
     * @ORM\Column(name="username", type="string", length=50)
     * 
     * @Assert\Length(min=2, minMessage="Vous devez avoir un nom d'utilisateur de min {{ limit }} caractères.",
     * max =50, maxMessage="La longeur du nom ne peux pas dépasser {{ limit }} caractères")  
     * @Assert\NotBlank(message="le champs ne peux pas être vide")
     * @Assert\Regex(pattern="/\W/", match = false ,message="La chaine ne peux pas avoir que des Chiffres et des lettres")
     */
    private $username;

    /**
     * @ORM\Column(name="mdp", type="string", length=255)
     * @var string
     *  @Assert\Length(min=2, minMessage="Votre mot de passe dois avoir au moins {{ limit }} caractères.",
     * max =50, maxMessage="La longeur du mot de passe ne peux pas dépasser {{ limit }} caractères",  groups={"registration"})  
     * 
     * 
     *
     */
    private $mdp;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     */
    private $salt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dernierConnexion", type="datetime", nullable=True)
     */
    private $dernierConnexion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ldap", type="boolean", nullable=True)
     * 
     * @Assert\Type(type="bool", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     * 
     */
    private $ldap;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean", nullable=True)
     * @Assert\Type(type="bool", message="La valeur {{ value }} n'est pas un type {{ type }} valide.")
     */
    private $actif;

    /**
     *
     * @ORM\ManyToMany(targetEntity="Groupe", inversedBy="personnes")
     * @ORM\JoinTable(name="GroupePersonne")
     * 
     * 
     */
    private $groupes;
    /**
     *@ORM\Column(name="roles",  type="string", length=255, nullable=True)
     *
     *  
     */
    private $roles;
    
     public static $em;
        public $fingerprinting;

    public function __construct() {
        $this->groupes = new ArrayCollection();
        $this->salt = md5(uniqid(null, true));
        //$this->setDernierConnexion(new \DateTime("00000000000000"));
    }

    public function addGroupe(Groupe $groupe) {
        $this->groupes[] = $groupe;
        return $this;
    }

    public function removeGroupe(Groupe $groupe) {

        $this->groupes->removeElement($groupe);
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Personne
     */
    public function setUsername($username) {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     * @return Personne
     */
    public function setMdp($mdp) {
        $this->mdp = $mdp;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string 
     */
    public function getMdp() {
        return $this->mdp;
    }

    /**
     * Set mdp
     *
     * @param string $mdp
     * @return Personne
     */
    private function setSalt($salt) {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get mdp
     *
     * @return string 
     */
    public function getSalt() {
        return $this->salt;
    }

    /**
     * Set dernierConnexion
     *
     * @param \DateTime $dernierConnexion
     * @return Personne
     */
    public function setDernierConnexion($dernierConnexion) {
        $this->dernierConnexion = $dernierConnexion;

        return $this;
    }

    /**
     * Get dernierConnexion
     *
     * @return \DateTime 
     */
    public function getDernierConnexion() {
        
        return $this->dernierConnexion;
    }
     public function getDernierConnexionString() {
        if ($this->dernierConnexion ===null){
            return "00-00-0000 00:00:00";
        }
        else{
            return $this->dernierConnexion->format("d-m-Y H:i:s");
        }
        
    }
    /**
     * Set ldap
     *
     * @param boolean $ldap
     * @return Personne
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
     * @return Personne
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

    public function getGroupes() {
        return $this->groupes;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize() {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
                ) = unserialize($serialized);
    }

    public function isAccountNonExpired() {
        $date = new DateTime();
//return (isset($this->valideJusque) && $this->valideJusque > $date) || !isset($this->valideJusque);
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    public function isEnabled() {
        return $this->actif;
//return $this->actif;
    }

    public function isEqualTo(UserInterface $user) {
        //return $this->getUsername() === $user->getUsername();
        return false;
        
    }

    public function __toString() {
        return $this->username;
    }

    public function eraseCredentials() {
 
    }

    public function getPassword() {
        
        return $this->getMdp();
    }
    function setRoles($roles) {
        $this->roles[] = $roles;
    }

        public function getRoles() {
        $tab = array();
        foreach($this->groupes as  $groupess)
        {
            $tab[] = $groupess->getRole();
            
        }
        
        return $tab;
        //return ;
    }
    public function affichage(){
        return $this->username;
    }
    
    
     public function type(){
        if ($this->ldap) return "LDAP";
        else return "local";
        
            }
            
            public function getGroupeGen(){
               $strings ="";
              
                foreach ($this->getGroupes() as $groupe ){
                    
                    $strings = $strings."- ".$groupe."</br>"; 
                
                    
                }
                
                return $strings;
                
            }
     public function activiter(){
        if ($this->actif) return "Groupe activé <span class=\"glyphicon glyphicon-ok\" aria-hidden=\"true<\"></span>";
        else return "Groupe Désactivié <span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true<\"></span>";
        
            }
    
    
    public function resumer(){
         
         $tab = array('Id' => $this->getId(),
                      'username' => $this->getUsername(),
                      'groupe' => $this->getGroupeGen(),
                      'dernier connexion' => $this->getDernierConnexionString(),
                        'ldap' => $this->type(),
                        'Information' => $this->activiter());
         
      return $tab;
             
             
         
         
         
     }

     
     
      public function makeFingerprinting()
{
$this->fingerprinting = sha1($_SERVER['HTTP_USER_AGENT']."".$_SERVER['SERVER_ADDR']."".$_SERVER['SERVER_PROTOCOL']."zmaslemiogorkiem".$_SERVER['HTTP_ACCEPT_ENCODING'].'abbbisjqjsjd893732');
}
 public function checkFingerprinting($fingerprinting)
{
$this->makeFingerprinting();
return (bool)($this->fingerprinting == $fingerprinting);
}
}
