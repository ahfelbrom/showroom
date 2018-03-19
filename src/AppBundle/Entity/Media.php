<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="media")
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    private $file;

    /**
     * @ORM\Column(name="path", type="string", length=200)
     */
    private $path;


    /**
     *
     * Constructor of the Document entity
     *
     */
    public function __construct()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }
}