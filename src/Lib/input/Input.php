<?php

namespace App\Lib\input;

use App\Lib\Tool;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Lib\Input\InputValidator;

class Input extends InputValidator
{
    /**
     * Check if values field have good format but not for confirmPass and set a session message 
     * @param array $post
     * @param array|false $isEmpty if is an array 
     * @return array|false $errors return array if errors detected 
     */
    public function hasGoodformat (array $post)
    {  
        unset($post['confirmPass']);

        $errors = false;
        $session = new Session();

        foreach ($post as $key => $value) {
            
            try {
    
                $trueOrFalse = $this->$key($value);
                $errors[$key] = $trueOrFalse;
                $trueOrFalse ? null : $session->set('user','error', (new InputError())::$key($value)) ;
                
            } catch (\Throwable $th) {

                $session->set('user','error', 'Désolé  une erreur est survenue');
                header('Location:/public/user/dashboard');
                die();
            }
        }
        
        return $errors;
    }
    
    /**
     * @param array $array array to clean
     */
    public function cleaner(array $array) : array
    {
        $clean = [];

        foreach ($array as $key => $value) {

            $clean[$key] = htmlspecialchars($value);
        }
        return $clean;
    }

    /**
     * Key name empty of an array
     */
    public function isEmpty(array $inputs) : array
    {
        $empty = [];
        $i = 0;
        $session = new Session();
        $backToPage = $_SERVER['REQUEST_URI'];

        foreach ($inputs as $key => $value) {

            
            if(empty($value))
            {
                $empty[$key] = $key;

                try {

                    $session->set('product','error', (new InputError())->$key($value));
                } catch (\Throwable $th) {

                    $session->set('user','error', 'Désolé  une erreur est survenue');
                    header("Location:$backToPage");
                    die();
                }
            }
        }
        
        return $empty ;
    }

    /**
     * Return true if price has good format
     * @return bool|null
     */
    public function priceHasGoodFormat($price) 
    {   
        if (is_string($price))
        {
            return $this->price($price);
        }else{
            return null;
        }
    }

    /**
     * Save inputs in sessions 
     */
    public function save($post)
    {   
        if(is_array($post))
        {
            $postCleaned = $this->cleaner($post);
            
            foreach ($postCleaned as $key => $value) {

                $_SESSION['saveInputs'][$key] = $value;
            }
        }
    }
}