<?php

namespace PASS\GestionLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * priority
 *
 * @ORM\Table()
 * 
 * @ORM\Entity(repositoryClass="PASS\GestionLogBundle\Entity\priorityRepository")
 */
class priority
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * ORM\GeneratedValue(strategy="AUTO")
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
     * @ORM\Column(name="couleur", type="string", length=7)
     */
    private $couleur;
    /**
     * @var string
     *
     * @ORM\Column(name="couleurText", type="string", length=7)
     */
    private $couleurText;

    
     public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return priority
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
     * @return priority
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
     * Set couleur
     *
     * @param string $couleur
     * @return priority
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;

        return $this;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set syslogId
     *
     * @param integer $syslogId
     * @return priority
     */
    public function setSyslogId($syslogId)
    {
        $this->syslogId = $syslogId;

        return $this;
    }

    /**
     * Get syslogId
     *
     * @return integer 
     */
    public function getSyslogId()
    {
        return $this->syslogId;
    }

    /**
     * Set couleurText
     *
     * @param string $couleurText
     * @return priority
     */
    public function setCouleurText($couleurText)
    {
        $this->couleurText = $couleurText;

        return $this;
    }

    /**
     * Get couleurText
     *
     * @return string 
     */
    public function getCouleurText()
    {
        return $this->couleurText;
    }
}
