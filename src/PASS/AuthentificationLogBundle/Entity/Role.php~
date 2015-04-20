<?php

namespace PASS\AuthentificationLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PASS\AuthentificationLogBundle\Entity\Personne;
use PASS\AuthentificationLogBundle\Entity\Groupe;

/**
 * Roles
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PASS\AuthentificationLogBundle\Entity\RoleRepository")
 */
class Role implements \Serializable, \Symfony\Component\Security\Core\Role\RoleInterface
{
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
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", length=255)
     */
    private $roles;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif;
     /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable = true)
     */
    private $type;
    
    /**
     * @ORM\ManyToMany(targetEntity="Personne", mappedBy="roles")
     * 
     */
    
    private $personnes;
    
    /**
     * @ORM\ManyToMany(targetEntity="Groupe", mappedBy="roles", cascade={"persist"})
     */        
    private $groupes;

    function __construct() {
        $this->personnes = array();
        $this->groupes = array();
        $this->actif = true;
         
    }
    
    public function addPersonnes(Personne $personne){
         
            
        $this->personnes[] = $personne;
    }
    
    

    function getPersonnes() {
         
        return $this->personnes;
    }

    function getGroupes() {
        return $this->groupes;
    }

    function setPersonnes($personnes) {
        
        $this->personnes = $personnes;
    }

    function setGroupes($groupes) {
        $this->groupes = $groupes;
    }

        /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Roles
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Roles
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set roles
     *
     * @param string $roles
     * @return Roles
     */
    public function setRole($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string 
     */
    public function getRole()
    {
        return $this->roles;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Roles
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }
    public function __toString() {
        return $this->nom." ";
    }
    function getType() {
        return $this->type;
    }

    function setType($type) {
        $this->type = $type;
    }

    
    public function serialize() {
        
        return serialize(array(
            $this->id,
            $this->nom,
            $this->description,
            $this->roles
           
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized) {
        list (
                $this->id,
            $this->nom,
            $this->description,
            $this->roles
                ) = unserialize($serialized);
    }

}
