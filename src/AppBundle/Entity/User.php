<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

// ----------------------------------------------------------------------------

use Symfony\Component\Security\Core\User\UserInterface;

// ----------------------------------------------------------------------------

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email")
 */
class User implements UserInterface
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

    private $roles;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Email()
     */
    private $email;


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

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getSalt()
    {

    }

    public function getUsername()
    {
        return $this->email;
    }

    public function setUsername($mail)
    {
        $this->email = $mail;

        return $this;
    }

    public function eraseCredentials()
    {
        // erase sensible informations of the user (here there are none)
    }
}