<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


// -----------------------------------------------------------------------------

use Symfony\Component\Validator\Constraints as Assert;

// -----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="s_show")
 */
class Show
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
     * @ORM\Column(name="abstract", type="text")
     * 
     * @Assert\NotBlank()
     */
    private $abstract;

    /**
     * @ORM\Column(name="country", type="string", length=50)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du pays est de {{ limit }} caractères"
     * )
     */
    private $country;

    /**
     * @ORM\Column(name="author", type="string", length=50)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du nom de l'auteur est de {{ limit }} caractères"
     * )
     */
    private $author;

    /**
     * @ORM\Column(name="release_date", type="date")
     * 
     * @Assert\NotBlank()
     */
    private $releaseDate;

    /**
     * @ORM\Column(name="main_picture", type="string", length=50)
     * 
     * @Assert\NotBlank()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "La longueur maximale du nom de la photo est de {{ limit }} caractères"
     * )
     */
    private $mainPicture;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;


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

    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;

        return $this;
    }

    public function getAbstract()
    {
        return $this->abstract;
    }

    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }
    
    public function getCountry()
    {
        return $this->country;
    }

    
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setReleaseDate(\DateTime $releaseDate)
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getReleaseDate()
    {
        return $this->releaseDate;
    }

    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;

        return $this;
    }

    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }
}