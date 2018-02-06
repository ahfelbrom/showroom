<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


// -----------------------------------------------------------------------------

use Symfony\Component\Validator\Constraints as Assert;

// -----------------------------------------------------------------------------

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du nom est de {{ limit }} caractères"
     * )
     */
    private $name;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
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

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}