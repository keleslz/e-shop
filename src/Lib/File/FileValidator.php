<?php

namespace App\Lib\File;

use App\Lib\File\FileError;

class FileValidator extends FileError
{
    /**
     * @param array $_FILES[$key]
     */
    public function __construct(array $file)
    {
        $this->file = $file;
    }

    public function controle () : array
    {
        $error = [];
        
        $error['name'] = $this->name();
        $error['type'] = $this->type();
        $error['size'] = $this->size();

        foreach ($error as $key => $value) {
            
            if($value === false)
            {   
                $func = 'error' . ucFirst($key);
                $this->$func();
            }
        }
        return $error;
    }

    public function full() : bool
    {
        return !empty($this->file['name']) ? true : false ;
    }

    private function name() : bool
    {
        return is_string($this->file['name']) ? true : false;
    }

    private function type() : bool
    {
        $types = ['jpeg','jpg', 'png', 'gif'];
        $error = false;

        foreach ($types as $key => $type) {
            
            $contains = strpos($this->file['name'], $type);

            if($contains !== false)
            {
                $error = true;
            }
        }
        
        return $error;
    }

    private function size() : bool
    {
        return intval($this->file['size']) < $this->size ? true : false; 
    }
}