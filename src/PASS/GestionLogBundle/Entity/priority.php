<?php

namespace PASS\GestionLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * priority
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class priority
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
     * @var integer
     *
     * ORM\Column(name="Priority", type="smallint")
     *@ORM\ManyToOne(targetEntity="Systemevents", inversedBy="priority")
     */
    private $prioritys;

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
     * @param array $couleur
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
     * @return array 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set id_priority
     *
     * @param integer $idPriority
     * @return priority
     */
    public function setIdPriority($idPriority)
    {
        $this->id_priority = $idPriority;

        return $this;
    }

    /**
     * Get id_priority
     *
     * @return integer 
     */
    public function getIdPriority()
    {
        return $this->id_priority;
    }

    /**
     * Set priority
     *
     * @param \PASS\GestionLogBundle\Entity\Systemevents $priority
     * @return priority
     */
    public function setPriority(\PASS\GestionLogBundle\Entity\Systemevents $priority = null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return \PASS\GestionLogBundle\Entity\Systemevents 
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set prioritys
     *
     * @param \PASS\GestionLogBundle\Entity\Systemevents $prioritys
     * @return priority
     */
    public function setPrioritys(\PASS\GestionLogBundle\Entity\Systemevents $prioritys = null)
    {
        $this->prioritys = $prioritys;

        return $this;
    }

    /**
     * Get prioritys
     *
     * @return \PASS\GestionLogBundle\Entity\Systemevents 
     */
    public function getPrioritys()
    {
        return $this->prioritys;
    }
}
