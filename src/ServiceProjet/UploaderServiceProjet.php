<?php

namespace App\ServiceProjet;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploaderServiceProjet
{
    public function __construct(private SluggerInterface $slugger) {}
    public function uploadFileRec(
        UploadedFile $fileRec,
        string $directoryFolderRec='uploads_directory_Rec'
       
    ): string
    {
        
        $originalFilenameRec = pathinfo($fileRec->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilenameRec = $this->slugger->slug($originalFilenameRec);
        $newFilenameRec = $safeFilenameRec.'-'.uniqid().'.'.$fileRec->guessExtension();
        
        // Move the file to the directory where brochures are stored
        try {
            $fileRec->move(
                $directoryFolderRec,
                $newFilenameRec
            );
        } catch (FileException $e) {
            echo "l'upload ne fonctionne pas! Essaiez une autre fois!";
        }
        return $newFilenameRec;
    }


    
}