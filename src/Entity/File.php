<?php 

namespace App\Entity;

use App\Lib\Session\Session;
use App\Repository\FileRepository;

class File
{
    private int $id;
    
    private string $name;

    private string $type;

    private string $tmp_name;

    protected int $size = 30000;

    private string $destination = ROOT . DS . '/public/img-storage' ; 

    private int $idProduct;
  

    /**
     * Create a file
     */
    public function create(array $picture, FileRepository $fileRepo)
    {
        $session = new Session();

        if( $fileRepo->create($this) ) {

            copy($picture['tmp_name'], $this->getDestination());
            $session->set('file','success','Votre image a bien été enregisté');
        }else{
            $session->set('file','error','Désolé une erreur est survenue, impossible de sauvegarder l\'image');
        } 
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
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

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
     * Get the value of type
     */ 
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */ 
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of tmp_name
     */ 
    public function getTmp_name()
    {
        return $this->tmp_name;
    }

    /**
     * Set the value of tmp_name
     *
     * @return  self
     */ 
    public function setTmp_name($tmp_name)
    {
        $this->tmp_name = $tmp_name;

        return $this;
    }

    /**
     * Get the value of size
     */ 
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set the value of size
     *
     * @return  self
     */ 
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get the value of path
     */ 
    public function getPath()
    {
        return $this->destination . DS . $this->name;
    }

    /**
     * Get the value of destination !! Don't forget to define a name before call this function 
     */ 
    public function getDestination()
    {
        return $this->destination . DS . $this->getName();
    }   
    
    /**
     * Get the value of idProduct
     */ 
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set the value of idProduct
     *
     * @return  self
     */ 
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }
}
