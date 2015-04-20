<?php

namespace PASS\GestionLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Statistique
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PASS\GestionLogBundle\Entity\StatistiqueRepository")
 */
class Statistique
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
     * @ORM\Column(name="serveur", type="string", length=50)
     */
    private $serveur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var array
     *
     * @ORM\Column(name="priority", type="array")
     */
    private $priority;


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
     * Set serveur
     *
     * @param string $serveur
     * @return Statistique
     */
    public function setServeur($serveur)
    {
        $this->serveur = $serveur;

        return $this;
    }

    /**
     * Get serveur
     *
     * @return string 
     */
    public function getServeur()
    {
        return $this->serveur;
    }

    /**
     * Set µdate
     *
     * @param \DateTime $µdate
     * @return Statistique
     */
    public function setDate($µdate)
    {
        $this->date = $µdate;

        return $this;
    }

    /**
     * Get µdate
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set priority
     *
     * @param array $priority
     * @return Statistique
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return array 
     */
    public function getPriority()
    {
        return $this->priority;
    }
}
