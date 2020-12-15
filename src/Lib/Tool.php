<?php

namespace App\Lib;

class Tool
{   
    /**
     *  Return true if $comparant === $compared;
     */
    public static function equal($comparant, $compared) : bool
    {
        if($comparant === $compared)
        {
            return true;
        }else {
            return false;
        }
    }
    
    /**
     * Convert a string snake_case format to CamelCase format
     * 
     */
    public static function convertStringSnakeToCamel(string $string) : string 
    {   
        $contains = strpos($string, '_');
        $final = '';

        if($contains)
        {
            $words  = explode('_', $string);
            $firstWord =  $words[0];

            for ($i=0; $i < count($words); $i++) { 

                if($i !== 0)
                {
                    $final = $firstWord . ucfirst($words[$i]);
                }
            }
            return $final;
        }else{

            return $string;
        }
    }

}