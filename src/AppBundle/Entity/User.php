<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// ----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $fullname;


    /**
     *
     * Constructor of the User entity
     *
     */
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;


        return $this;
    }

    public function getFullname()
    {
        return $this->fullname;
    }
}