<?php

namespace App\Lib\Input;

use App\Entity\File;

/**
 * Validator check input values
 */
abstract class InputValidator
{   
    /**
     * Check if value name is conform else return false
     * @param string $name It's input value
     */
    public function name(string $name) : bool
    {
        $regex = "/^[a-z ]*$/i";

        if(!preg_match( $regex, $name) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value surnname is conform else return false
     * @param string $name It's input value
     */
    public function surname(string $surname) : bool
    {
        return $this->name($surname);
    }
    
    
    /**
     * Check if value zip is conform else return false
     * @param string $zip It's input value
     */
    public function zip(string $zip) : bool
    {
        $regex = "/^^(([0-8][0-9])|(9[0-5])|(2[ab]))[0-9]{3}$/";

        if(!preg_match( $regex, $zip) )
        {
            return false;
        }else {
            return true;
        }
    }
    

    /**
     * Check if value cryptoNumber is conform else return false
     * @param string $cryptoNumber It's input value
     */
    public function cryptoNumber(string $cryptoNumber) : bool
    {   
        $regex = "/^[0-9]{1,3}$/";

        if(!preg_match( $regex, $cryptoNumber) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value expirationDate is conform else return false
     * @param string $expirationDate It's input value
     */
    public function expirationDate(string $expirationDate) : bool
    {
        $regex = "/^([012][0-9]|3[0-1])\/[0-9]{2}$/";

        if(!preg_match( $regex, $expirationDate) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value creditCardNumber is conform else return false
     * @param string $creditCardNumber It's input value
     */
    public function creditCardNumber(string $creditCardNumber) : bool
    {
        $regex = "/^([0-9]{1,4}){1,4}$/";

        if(!preg_match( $regex, $creditCardNumber) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value address is conform else return false
     * @param string $address It's input value
     */
    public function address(string $address) : bool
    {
        return true;
    }

    /**
     * Check if value city is conform else return false
     * @param string $city It's input value
     */
    public function city(string $city) : bool
    {
        return true;
    }

    /**
     * Check if value department is conform else return false
     * @param string $department It's input value
     */
    public function department(string $department) : bool
    {
        return true;
    }

    /**
     * Check if value email is conform else return false
     * @param string $email It's input value
     */
    public function email(string $email) : bool
    {
        if( !filter_var($email, FILTER_VALIDATE_EMAIL) )
        {
            return false;
            
        }else {
            return true;
        }
    }

    /**
     * Check if value password is conform else return false
     * @param string $password It's input value
     */
    public function password(string $password) : bool
    {
        $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
        
        if( !preg_match( $regex, $password) )
        {
            return false;
        }else {
            return true;
        }
    }

     /**
     * Check if value newPassword is conform else return false
     * @param string $newPassword It's input value
     */
    public function newPassword(string $newPassword) : bool
    {
        return $this->password($newPassword);
    }

     /**
     * Check if value passwordConfirm is conform else return false
     * @param string $newPassword It's input value
     */
    public function passwordConfirm(string $newPassword) : bool
    {
        if($newPassword === $_POST['passwordConfirm'])
        {
            return true; 
        }else{
            return false;
        }
    }

    /**
     * Check if two inputs are same and return false if it's not case
     */
    public function areSame(string $password, string $passwordConfirm) : bool
    {
        if($password === $passwordConfirm)
        {
            return true; 
        }else{
            return false;
        }
    }

    /**
     * Check if value name is conform else return false
     * @param string $name It's input value
     */
    public function price(string $priceInCents) : bool
    {
        $regex = "/^\d+(,\d{1,2})?$/";

        if(!preg_match( $regex, $priceInCents) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value name is conform else return false
     * @param string $name It's input value
     */
    public function status(string $status) : bool
    {
        if($status == '-1' || $status == '1' )
        {
            return true;
        }else {
            return false;
        }
    }

    public function product_name() : bool
    {
        return true;
    }

    public function product_description($string = '') : bool
    {
        return true;
    }

    public function maxfileSize($value) : bool
    {
        return true;
    }

    /**
     * Check if value name is conform else return false
     * @param string $name It's input value
     */
    public function category_name(string $name) : bool
    {
        $regex = "/^[a-z ]*$/i";

        if(!preg_match( $regex, $name) )
        {
            return false;
        }else {
            return true;
        }
    }

    /**
     * Check if value is conform else return false
     * @param string $value It's input value
     */
    public function category_id(string $value) : bool
    {
        $regex = "/^[0-9]*$/";

        if(preg_match( $regex, $value) || $value === "Non classée" )
        {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Check if value is conform else return false
     * @param string $value It's input value
     */
    public function productId(string $value) : bool
    {
        $regex = "/^[0-9]*$/";

        if(!preg_match( $regex, $value) )
        {
            return false;
        }else {
            return true;
        }
    }
    
    /**
     * Check if value is conform else return false
     * @param string $value It's input value
     */
    public function quantity(string $value) : bool
    {
        $regex = "/^[0-9]*$/";

        if(!preg_match( $regex, $value) )
        {
            return false;
        }else {
            return true;
        }
    }
}