<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


// ----------------------------------------------------------------------------


/**
 * @ORM\Entity
 * @ORM\Table(name="show")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="text")
     */
    private $abstract;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=50)
     */
    private $country;

    /**
     * @var integer
     *
     * @ORM\Column(name="author", type="string", length=50)
     */
    private $author;

    /**
     * @var integer
     *
     * @ORM\Column(name="released_date", type="date")
     */
    private $releasedDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="main_picture", type="string", length=50)
     */
    private $mainPicture;


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

    public function setReleasedDate($releasedDate)
    {
        $this->releasedDate = $releasedDate;

        return $this;
    }

    public function getReleasedDate()
    {
        return $this->releasedDate;
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

    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }
}