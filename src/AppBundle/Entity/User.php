<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity
 * @ORM\Table
 *
 * @UniqueEntity("email", groups={"create"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @JMS\Groups({"user"})
	 */
	private $id;

    /**
     * @ORM\Column
     *
	 * @Assert\NotBlank
     *
     * @JMS\Expose
     * @JMS\Groups({"user", "show"})
     */
	private $fullname;

    /**
     * @ORM\Column(type="json_array")
     *
     * @Assert\NotBlank(groups={"create"})
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"user_create"})
     */
	private $roles;

	/**
     * @ORM\Column
     *
     * @Assert\NotBlank(groups={"create"})
     *
     * @JMS\Expose
     * @JMS\Groups({"user_create"})
     */
	private $password;

	/**
	 * @ORM\Column
	 *
	 * @Assert\Email
	 * @Assert\NotBlank(groups={"create"})
	 * 
	 * @JMS\Expose
	 * @JMS\Groups({"user"})
	 */
	private $email;

	/**
	 * @ORM\OneToMany(targetEntity="Show", mappedBy="author", cascade={"remove"})
	 */
	private $shows;

	public function __construct()
	{
		$this->shows = new ArrayCollection();
	}

	public function getFullname()
	{
		return $this->fullname;
	}

	public function setFullname($name)
	{
		$this->fullname = $name;
	}

	public function getRoles()
	{
		return $this->roles;
	}

	public function setRoles($roles)
	{
		$this->roles = $roles;
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function getSalt()
	{
	}

	public function getUsername()
	{
		return $this->email;
	}

	public function setUsername($email)
	{
		$this->email = $email;
	}

	public function eraseCredentials()
	{
	}

	public function addShow(Show $show)
	{
		if (!$this->shows->contains($show)){
			$this->shows->add($show);
		}
	}

	public function removeShow(Show $show)
	{
		$this->shows->remove($show);
	}

	public function getShows()
	{
		return $this->shows;
	}

	public function update(User $user)
	{
		$this->fullname = $user->getFullname();
		$this->roles = explode( ', ', $user->getRoles());
		$this->password = $user->getPassword();
		$this->email = $user->getUsername();
	}
}