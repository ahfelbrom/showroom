<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 *
 * @UniqueEntity("name", message="{{ value }} is already in database")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Category
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
     *
	 * @JMS\Expose
	 */
	private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     *
     * @Assert\NotBlank
     *
     * @JMS\Expose
     */
	private $name;

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function update(Category $category)
	{
		$this->name = $category->getName();
	}
}