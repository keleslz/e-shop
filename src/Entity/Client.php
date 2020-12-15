<?php

namespace App\Entity;

use App\Entity\User;

class Client extends User{

    private $idBilling = null;

    private $creditCardNumber = null;

    private $expirationDate = null;

    private $cryptoNumber = null;

    public function __construct(array $post, string $info = 'delivery', string $idBilling = '' )
    {   
        $this->addCustomerInfo($post, $info);
        $this->addBillingInfo($post, $info, $idBilling );

        return $this;
    }
    
    private function addCustomerInfo (array $post, string $info)
    {   
        if ($info === 'delivery')
        {
            $this->setName($post['name']);
            $this->setSurname($post['surname']);
            $this->setEmail($post['email']);
            $this->setAddress($post['address']);
            $this->setZip($post['zip']);
            $this->setCity($post['city']);
            $this->setDepartment($post['department']);
            $this->getCreatedAt();
            $this->getIdBilling();
        }
    }
    
    private function addBillingInfo ( array $post, string $info, string $idBilling)
    {   
        if ($info === 'customer')
        {
            $this->setName($post['name']);
            $this->setSurname($post['surname']);
            $this->setCreditCardNumber($post['creditCardNumber']);
            $this->setExpirationDate($post['expirationDate']);
            $this->setCryptoNumber($post['cryptoNumber']);
            $this->setDepartment($post['department']);
            $this->setIdBilling($idBilling);
        }
    }
    
    /**
     * Get the value of idBilling
     */ 
    public function getIdBilling()
    {
        return $this->idBilling = uniqid((string)(rand()*10)) . 'e_shop' ;
    }

    /**
     * Get the value of creditCardNumber
     */ 
    public function getCreditCardNumber()
    {   
        return $this->creditCardNumber;
    }
    
    /**
     * Get the value of creditCardNumber formated
     */ 
    public function getCreditCardNumberFormated() : string 
    {
        $regex  ='/[0-9]/';

        $splited =  str_split($this->creditCardNumber, 4) ;
        $a = preg_replace( $regex , 'X', $splited[0]);
        $b = preg_replace( $regex , 'X', $splited[1]);
        $c = preg_replace( $regex , 'X', $splited[2]);
        
        return "{$a}-{$b}-{$c}-{$splited[3]}";
    }

    /**
     * Set the value of creditCardNumber
     *
     * @return  self
     */ 
    public function setCreditCardNumber($creditCardNumber)
    {
        $this->creditCardNumber = $creditCardNumber;

        return $this;
    }

    /**
     * Get the value of expirationDate
     */ 
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
    
    /**
     * Get the value of expirationDate formated
     */ 
    public function getExpirationDateFormated() : string
    {
        $regex  ='/^([0-9]){2}/';

        $splited = preg_replace( $regex , 'XX', $this->expirationDate);
        
        return $splited;
    }

    /**
     * Set the value of expirationDate
     *
     * @return  self
     */ 
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get the value of cryptoNumber
     */ 
    public function getCryptoNumber()
    {
        return $this->cryptoNumber;
    }

    /**
     * Set the value of cryptoNumber
     *
     * @return  self
     */ 
    public function setCryptoNumber($cryptoNumber)
    {
        $this->cryptoNumber = $cryptoNumber;

        return $this;
    }

    /**
     * Set the value of idBilling
     *
     * @return  self
     */ 
    public function setIdBilling($idBilling)
    {
        $this->idBilling = $idBilling;

        return $this;
    }
}