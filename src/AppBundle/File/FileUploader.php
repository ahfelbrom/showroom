<?php

namespace AppBundle\File;

use Symfony\Component\HttpFoundation\File\UploadedFile;

//------------------------------------------------------------------------------


class FileUploader
{
    private $pathToProject;
    private $uploadDirectoryFile;

    public function __construct($pathToProject, $uploadDirectoryFile)
    {
        $this->pathToProject = $pathToProject;
        $this->uploadDirectoryFile = $uploadDirectoryFile;
    }

    public function upload(UploadedFile $file, $salt)
    {
        $generatedName = time()."_".$salt.".".$file->guessClientExtension();
        $path = $this->pathToProject."/web".$this->uploadDirectoryFile;

        $file = $file->move($path, $generatedName);

        return $generatedName;
    }
}