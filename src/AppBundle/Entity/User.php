<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

// ----------------------------------------------------------------------------

use Symfony\Component\Security\Core\User\UserInterface;

// ----------------------------------------------------------------------------

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

// -----------------------------------------------------------------------------

use JMS\Serializer\Annotation as JMS;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @UniqueEntity("email")
 *
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     * @JMS\Groups({"user"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     *
     * @JMS\Expose
     *
     * @JMS\Groups({"user", "show", "creation"})
     */
    private $fullname;

    /**
     * @ORM\Column(type="json_array")
     *
     * @JMS\Expose
     * @JMS\Groups({"creation"})
     */
    private $roles;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\Email()
     *
     * @JMS\Expose
     * @JMS\Groups({"user", "creation"})
     */
    private $email;

    /**
     * The attribute for the tasks of the Consultation
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Show", cascade={"persist", "remove"}, orphanRemoval=true, mappedBy="author")
     */
    private $shows;


    /**
     *
     * Constructor of the User entity
     *
     */
    public function __construct()
    {
        $this->shows = new ArrayCollection();
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

    public function addRole($role)
    {
        $this->roles[] = $role;

        return $this;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    public function getRoles()
    {
        return $this->roles;
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

    public function addShow(Show $show)
    {
        if (!($this->shows->contains($show)))
        {
            $show->setAuthor($this);
            $this->shows->add($show);
        }

        return $this;
    }

    public function removeShow(Show $show)
    {
        if ($this->shows->contains($show))
        {
            $this->shows->removeElement($show);
        }

        return $this;
    }

    public function getShows()
    {
        return $this->shows;
    }

    public function update($user)
    {
        $this
            ->setFullname($user->getFullname())
            ->setRoles($user->getRoles())
            ->setPassword($user->getPassword());

        return $this;
    }
}