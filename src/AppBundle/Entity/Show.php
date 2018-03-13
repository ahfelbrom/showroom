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
    const DATA_SOURCE_OMDB = "OMDB";
    const DATA_SOURCE_DB = "In local Database";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    protected $id;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     * 
     * @Assert\NotBlank(message="Please enter a name for the show", groups={"create", "update"})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du nom est de {{ limit }} caractères",
     *      groups={"create", "update"}
     * )
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $name;

    /**
     * @ORM\Column(name="abstract", type="text")
     * 
     * @Assert\NotBlank(message="Please enter an abstract for the show", groups={"create", "update"})
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $abstract;

    /**
     * @ORM\Column(name="country", type="string", length=50)
     * 
     * @Assert\NotBlank(message="Please enter a country for the show", groups={"create", "update"})
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du pays est de {{ limit }} caractères",
     *      groups={"create", "update"}
     * )
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="shows")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $author;

    /**
     * @ORM\Column(name="release_date", type="date")
     * 
     * @Assert\NotBlank(message="Please select a date for the show", groups={"create", "update"})
     */
    private $releaseDate;

    /**
     * @ORM\Column(name="main_picture", type="string", length=100)
     * 
     * @Assert\NotBlank(message="Please upload a picture for the show", groups={"create"})
     * @Assert\Image(
     * minHeight=300,
     * minWidth=750,
     * groups={"create"}
     * )
     */
    private $mainPicture;


    private $tmpPicture;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank(message="Please select a category for the show", groups={"create", "update"})
     *
     * @JMS\Expose
     */
    private $category;

    /**
     * @ORM\Column(name="data_source", type="string", options={"default": "In local Database"})
     */
    private $dataSource;


    public function __construct()
    {
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    public function setAuthor(User $author)
    {
        $this->author = $author;

        return $this;
    }

    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function setTmpPicture($tmpPicture)
    {
        $this->tmpPicture = $tmpPicture;

        return $this;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    public function removeCategory()
    {
        $this->category = null;

        return $this;
    }

    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAbstract()
    {
        return $this->abstract;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    public function getTmpPicture()
    {
        return $this->tmpPicture;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getDataSource()
    {
        return $this->dataSource;
    }

    public function update($show)
    {
        $this
            ->setName($show->getName())
            ->setAbstract($show->getAbstract())
            ->setCountry($show->getCountry())
            ->setAuthor($show->getAuthor())
            ->setCategory($show->getCategory())
            ->setReleaseDate($show->getReleaseDate())
            ->setDataSource($show->getDataSource())
            ->setMainPicture($show->getMainPicture());

        return $this;
    }
}