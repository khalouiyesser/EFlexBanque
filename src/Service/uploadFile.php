<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class uploadFile
{
    public function __construct(private SluggerInterface $slugger) {}
    public function uploadFile(
        UploadedFile $file,
        string $directoryFolder='uploads_directory'
       
    ): string
    {
        
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
        
        // Move the file to the directory where brochures are stored
        try {
            $file->move(
                $directoryFolder,
                $newFilename
            );
        } catch (FileException $e) {
            echo "l'upload ne fonctionne pas! essaiez une autre fois!";
        }
        return $newFilename;
    }


    
}