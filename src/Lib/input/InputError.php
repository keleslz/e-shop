<?php

namespace App\Lib\Input;

use App\Lib\Session\Session;

class InputError
{   
    /**
     * Display errors if exist
     */
    public static function get (array $array) 
    {   
        $list = [] ;
        
        if( in_array(false, $array) )
        {
            foreach ($array as $key => $value) {

                if( $value === false)
                {   
                    array_push( $list, (new Session())->set('user','error', InputError::$key()) );
                }
            }
        }

        return $list;
    }
    
    // start purchase

    public static function name() : string 
    {
        return 'Veuillez vérifier le champ nom';
    }

    public static function surname() : string 
    {
        return 'Veuillez vérifier le champ prenom';
    }

    public static function address() : string 
    {
        return 'Veuillez vérifier le champ addresse';
    }

    public static function zip() : string 
    {
        return 'Veuillez vérifier le champ code postale';
    }

    
    public static function city() : string 
    {
        return 'Veuillez vérifier le champ ville';
    }
    
    public static function department() : string 
    {
        return 'Veuillez vérifier le champ departement';
    }
    
    public static function creditCardNumber() : string 
    {
        return 'Veuillez vérifier le champ carte de crédit';
    }

    
    public static function cryptoNumber() : string 
    {
        return 'Veuillez vérifier le champ numéro de cryptogramme';
    }

    public static function expirationDate() : string
    {
        return "Veuillez vérifier le champ date d'expiration";
    }

    // end purchase


    public static function email() : string 
    {
        return 'Veuillez vérifier le champ email';
    }

    public static function password() : string 
    {
        return 'Veuillez vérifier le champ mot de passe';
    }

    public static function passwordChanged() : string 
    {
        return 'Votre mot de passe a été modifié avec succès';
    }

    public static function newPassword() : string 
    {
        return 'Veuillez vérifier le champ nouveau mot de passe';
    }

    public static function passwordNotSame(string $input1, string $input2) : string 
    {
        $a = ucfirst($input1);
        $b = ucfirst($input2);
        return "Veuillez vérifier que les champs <strong>\"{$a}\"</strong> et <strong>\"{$b}\"</strong> soient identiques";
    }

    public static function equal() : string 
    {
        return 'Veuillez vérifier que les champs mot de passe soient identiques';
    }

    public static function price() : string 
    {
        return 'Veuillez vérifier que le prix du produit soit au format 0,00';
    }
    
    public static function product_name() : string 
    {
        return 'Veuillez vérifier le nom du produit';
    }

    public static function product_description() : string 
    {
        return 'Veuillez vérifier le description du produit';
    }

    public static function status() : string 
    {
        return 'Veuillez selectionner le statut du produit : online (On) / offline (Off)';
    }
    
    public static function category_name() : string 
    {
        return 'Veuillez vérifier le nom de la catégorie';
    }

    public static function category_id() : string 
    {
        return 'Veuillez vérifier le type de categorie';
    }

    public static function basicError() : string
    {
        return 'Désolé une erreur est survenue';
    }

    public static function quantity()
    {
        return 'Veuillez vérifier la quantité du produit';
    }
}