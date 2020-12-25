<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;

class User 
{
    private int $id;

    private string $email;

    private string $password;

    private int $levelLaw = 1;
    
    private string $name ;

    private string $surname ;

    private string $address ;

    private string $zip ;

    private string $city ;

    private string $department ;

    private string $createdAt;

    /**
     * Setting if child class A was Called before child Class B
     */
    protected static string $classCalled = '';

    //constant name for HTML5 input
    const PASSWORD_FIELD_NAME = 'password';
    const EMAIL_FIELD_NAME = 'email';
    const NEW_PASSWORD_FIELD_NAME = 'newPassword';
    const PASSWORD_CONFIRM_FIELD_NAME = 'passwordConfirm';

    //constant name for the name of the User table and all its fields
    const TABLE_NAME = 'user';
    const PASSWORD_TABLE_FIELD_NAME = 'password';
    const EMAIL_TABLE_FIELD_NAME = 'email';
    const ID_TABLE_FIELD_NAME = 'id';
    const LAW_TABLE_FIELD_NAME = 'law';

    const REDIRECT_SIGNIN = '/public/admin/signin';
    const REDIRECT_SIGNUP = '/public/admin/signup';
    const REDIRECT_EDIT = '/public/user/edit';
    const REDIRECT_HOME = '/public/shop/home';

    const ADMIN_LAW_LEVEL = 65535;
    const CONTRIBUTOR_SUPERIOR_LAW_LEVEL = 10_000;
    const CONTRIBUTOR_LAW_LEVEL = 100;
    const CLIENT_LAW_LEVEL= 1;
    const ALL_LEVEL_LAW = [ self::ADMIN_LAW_LEVEL, self::CONTRIBUTOR_SUPERIOR_LAW_LEVEL, self::CONTRIBUTOR_LAW_LEVEL, self::CLIENT_LAW_LEVEL ];

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Get the value of levelLaw
     */ 
    public function getLevelLaw()
    {
        return $this->levelLaw;
    }

    /**
     * Set the value of levelLaw
     *
     * @return  self
     */ 
    public function setLevelLaw(int $levelLaw)
    {
        $this->levelLaw = $levelLaw;

        return $this;
    }

    
    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */ 
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @return  self
     */ 
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of zip
     */ 
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     *
     * @return  self
     */ 
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of department
     */ 
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set the value of department
     *
     * @return  self
     */ 
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        $now = new DateTime('now', new DateTimeZone('europe/paris'));

        return $this->createdAt = $now->format('Y-m-d H:m:i');
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }
}