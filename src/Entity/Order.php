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

    private $article = [];

    private float $total;

    private array $info;

    private $createdAt;

    /**
     * @var bool $state if order was validated or not
     */
    private bool $state = false; 

    public function __construct(array $info = []) 
    {
        $this->info = $info;
        $this->init();
    }

    /**
     * Init attribute
     */
    public function init() : self
    {   
        if(count($this->info) === 0)
        {
            return $this;
        }
        
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
     * Set the value of id
     */ 
    public function setId(int $id) : self
    {   
        $this->id = intval($id);
        return $this;
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
     */ 
    public function setName($name) : self
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
     */ 
    public function setSurname($surname) : self
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
     */ 
    public function setEmail($email) : self
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
     */ 
    public function setAddress($address) : self
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
     */ 
    public function setZip($zip) : self
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
     */ 
    public function setCity($city) : self
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
     */ 
    public function setDepartment($department) : self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get article list
     * @param array|string
     */ 
    public function getArticle() 
    {
        return $this->article;
    }

    /**
     * Set the value of article
     * @param array|string $article
     */ 
    public function setArticle($article) : self
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
    public function setTotal(float $total) : self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get the value of date of creation
     */ 
    public function getCreatedAt() : string 
    {
        return $this->createdAt;
    }
    
    
    /**
     * Set the value of date of creation
     */ 
    public function setCreatedAt($createdAt) : self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
    /**
     * Get the value of state
     */ 
    public function getState() : string 
    {
        return $this->state;
    }
    
    
    /**
     * Set the value of state
     */ 
    public function setState(bool $state) : self
    {
        $this->state = $state;

        return $this;
    }
}