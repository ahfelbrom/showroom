<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ShowRepository")
 * @ORM\Table(name="s_show")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Show
{
	const DATA_SOURCE_OMDB = 'OMDB';
	const DATA_SOURCE_DB = 'In local database';

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 *
	 * @JMS\Expose
	 * @JMS\Groups({"show"})
	 */
	private $id;

    /**
     * @ORM\Column
     * @Assert\NotBlank(message="Please provide a name for the show.", groups={"create", "update"})
	 *
	 * @JMS\Expose
	 * @JMS\Groups({"show"})
     */
	private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(groups={"create", "update"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
	private $abstract;

	 /**
     * @ORM\Column
     * @Assert\NotBlank(groups={"create", "update"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
	private $country;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="shows")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
	private $author;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank(groups={"create", "update"})
     */
	private $releaseDate;

    /**
     * @ORM\Column
     */
	private $mainPicture;

	/**
	 * @Assert\NotBlank(message="You must provide an image.", groups={"create"})
     * @Assert\Image(minHeight=300, minWidth=750, groups={"create"})
	 */
	private $tmpPicture;

	/**
     * @ORM\Column(options={"default" : "In local database"})
     */
	private $dataSource;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     *
     * @Assert\NotBlank(groups={"create", "update"})
     */
	private $category;

	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	public function getAbstract()
	{
		return $this->abstract;
	}

	public function setAbstract($abstract)
	{
		$this->abstract = $abstract;

		return $this;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function setCountry($country)
	{
		$this->country = $country;

		return $this;
	}

	public function getAuthor()
	{
		return $this->author;
	}

	public function setAuthor(User $author)
	{
		$this->author = $author;

		return $this;
	}

	public function getReleaseDate()
	{
		return $this->releaseDate;
	}

	public function setReleaseDate(\Datetime $releaseDate)
	{
		$this->releaseDate = $releaseDate;

		return $this;
	}

	public function getMainpicture()
	{
		return $this->mainPicture;
	}

	public function setMainPicture($mainPicture)
	{
		$this->mainPicture = $mainPicture;

		return $this;
	}

	public function getTmpPicture()
	{
		return $this->tmpPicture;
	}

	public function setTmpPicture($tmpPicture)
	{
		$this->tmpPicture = $tmpPicture;

		return $this;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function setCategory(Category $category)
	{
		$this->category = $category;

		return $this;
	}

	public function getDataSource()
	{
		return $this->dataSource;
	}

	public function setDataSource($dataSource)
	{
		$this->dataSource = $dataSource;

		return $this;
	}

	public function update(Show $newShow)
	{
		$this
		    ->setName($newShow->getName())
		    ->setAbstract($newShow->getAbstract())
		    ->setCountry($newShow->getCountry())
		    ->setReleaseDate($newShow->getReleaseDate())
		    ->setMainPicture($newShow->getMainPicture())
		    ->setCategory($newShow->getCategory())
		    ->setAuthor($newShow->getAuthor())
		;
	}
}