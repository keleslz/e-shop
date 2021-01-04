<?php
namespace App\Entity;

use DateTime;
use DateTimeZone;

class Order {

    private int $id;

    /** if exist  */
    private $userId;

    private string $name;

    private string $surname;

    private string $email;

    private string $address;

    private string $zip;

    private string $city;

    private string $department;

    private array $article = [];

    private float $total;

    private array $info;

    private $createAt;

    public function __construct(array $info) 
    {
        $this->info = $info;
        $this->init();
    }

    /**
     * Init attribute
     */
    public function init() : self
    {   
        $this->name = $this->info['name'];
        $this->surname = $this->info['surname'];
        $this->email = $this->info['email'];
        $this->address = $this->info['address'];
        $this->zip = $this->info['zip'];
        $this->city = $this->info['city'];
        $this->department = $this->info['department'];

        return $this;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get the value of user id
     */ 
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set the value of user id
     */
    public function setUserId($userId) : self
    {   
        if(is_int($userId))
        {
            $this->userId = $userId;
        }

        return $this;
    }
    
    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
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
     * Get the value of lastname
     */ 
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of Surname
     *
     * @return  self
     */ 
    public function setSurname($surname)
    {
        $this->surname = $surname;

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
    public function setEmail($email)
    {
        $this->email = $email;

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
     * Get article list
     */ 
    public function getArticle() : array
    {
        return $this->article;
    }

    /**
     * Set the value of article
     */ 
    public function setArticle(array $article) : self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get the value of total
     */ 
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of total
     */ 
    public function setTotal($total) : self
    {
        $this->total = $total;

        return $this;
    }
    
    /**
     * Get the value of date of creation
     */ 
    public function getCreatedAt() : string 
    {
        $now = new DateTime('now', new DateTimeZone('europe/paris'));

        return $this->createdAt = $now->format('Y-m-d H:m:i');
    }
}