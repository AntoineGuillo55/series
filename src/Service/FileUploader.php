<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{

    public function __construct(
        string $directory,
        EntityManagerInterface $entityManager
    ){

    }

    public function upload(UploadedFile $file, string $directory, string $name = ''): string
    {
        $filename = ($name ? $name . '-' : $name) . uniqid() . '-' . $file->guessExtension();
        $file->move($directory, $filename);

        return $filename;
    }

    public function delete(string $filename, string $directory): bool
    {

        return unlink($directory . DIRECTORY_SEPARATOR . $filename);
    }
}