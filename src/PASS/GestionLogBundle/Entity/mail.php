<?php

namespace PASS\GestionLogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * mail
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class mail
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
