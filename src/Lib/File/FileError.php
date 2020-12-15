<?php
namespace App\Lib\File;

use App\Entity\File;

class FileError extends File
{   
    public function errorName()
    {   
        $_SESSION['file']['error']['name'] = 'Nom de fichier invalide';
    }

    public function errorType()
    {   
        $_SESSION['file']['error']['type'] = 'Type de fichier invalide';
    }

    public function errorSize()
    {   
        $_SESSION['file']['error']['size'] = 'Taille de fichier superieur à ' . $this->size . 'ko';
    }

    /**
     * Destroy a key of session
     */
    public function destroy(string $arrayName)
    {   
        $_SESSION[$arrayName] = [];
    }
}