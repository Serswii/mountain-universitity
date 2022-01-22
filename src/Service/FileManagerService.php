<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $postImageDirectory;

    public function __construct($postImageDirectory)
    {
        $this->postImageDirectory = $postImageDirectory;
    }

    /**
     * @return mixed
     */
    public function getPostImageDirectory()
    {
        return $this->postImageDirectory;
    }


    public function imagePostUpload(UploadedFile $file): string
    {
        $fileName = uniqid('', true).'.'.$file->guessExtension();
        try {
            $file->move($this->getPostImageDirectory(), $fileName);
        }
        catch (FileException $exception) {
            return $exception;
        }
        return $fileName;
    }

    public function removePostImage(string $fileName)
    {
        // TODO: Implement removePostImage() method.
    }
}