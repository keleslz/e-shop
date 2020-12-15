<?php
namespace App\Entity;

use ImageRepository;
use App\Entity\Image;
use App\Lib\Session\Session;
use App\Repository\ProductRepository;
use App\AbstractClass\AbstractController;
use App\Lib\input\Input;

class Product 
{
    private int $id;

    private string $name;

    private string $description;

    private string $idImg;

    private string $price;

    private int $status = -1;

    private int $idCategory = 0;


    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
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
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of slug
     */ 
    public function getSlug()
    {
        return  $this->name = str_replace(' ', '-', $this->name);
    }
    
    /**
     * Get the value of idImg
     */ 
    public function getIdImg()
    {
        return $this->idImg;
    }

    /**
     * Set the value of idImg
     *
     * @return  self
     */ 
    public function setIdImg($idImg)
    {
        $this->idImg = $idImg;

        return $this;
    }

    /**
     * Get the value of price
     */ 
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set the value of price
     *
     * @return  self
     */ 
    public function setPrice(string $price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of idCategory
     */ 
    public function getIdCategory()
    {
        return $this->idCategory;
    }

    /**
     * Set the value of idCategory
     *
     * @return  self
     */ 
    public function setIdCategory($idCategory)
    {
        $this->idCategory = $idCategory;

        return $this;
    }
    
    public function create(array $post, ProductRepository $productRepo , Session $session, $idImage )
    {   
        $category = $post['category_id'] === "Non classée" ? -1 : intval($post['category_id']) ;

        $this->setName($post['product_name']);
        $this->setDescription($post['product_description']);
        $this->setPrice($post['price']);
        $this->setStatus($post['status']);
        $this->setIdCategory($category);

        if($productRepo->create($this, $idImage)) 
        {
            $session->set('product', 'success',
            "Le produit <strong>\"{$post['product_name']}\"</strong> a bien été enregisté");
        }else{
            (new Input())->save($post);
            $session->set('product', 'error',
            "Le produit <strong>{$post['product_name']}</strong> n'a pas été enregisté car une erreur est survenue");
        }
    }

    public function edition(array $post, string $uniqId, int $id, AbstractController $abstractController)
    {
        $session = new Session();

        if ( !isset($post['product_name']))
        {
            $session->set('product', 'error', 'Aucune image selectionnée');
            return $abstractController->redirectTo('product/edition/' . $id);
        }
    
        $idImage = (new Image())->whenEditIfImageExist($_FILES, $uniqId);
        $productRepo =  new ProductRepository();

        $category = $post['category_id'] === "Non classée" ? -1 : intval($post['category_id']) ;
        
        $this->setName($post['product_name']);
        $this->setDescription($post['product_description']);
        $this->setPrice($post['price']);
        $this->setStatus($post['status']);
        $this->setIdCategory($category);
     
        if($idImage == null )
        {
            $productRepo->update($id, $this)
            ? $session->set('product', 'success', 'Produit modifié')
            : $session->set('product', 'error', 'Une erreur est survenue lors de la modification du produit');
        }else{
            $productRepo->updatePicture($id, $this, $idImage) 
            ? $session->set('product', 'success', 'Image pincipale modifiée')
            : $session->set('product', 'error', "Une erreur esr survenue lors de la modification de l'image principale");
        }

        return $abstractController->redirectTo('product/edition/' . $id);
    }

    public function deleteCover( int $productCoverId, array $productRepo)
    {
        if($productCoverId)
        {
            $imageRepo  = new ImageRepository();
            
            $productCover = $imageRepo->findOneBy('img','img_id', $productCoverId);
            unlink($productCover['img_path']); // Delete product cover file
            $imageRepo->delete('img','img_id', $productRepo['id_img']); //Delete product cover in db
        }
    }
    
    public function deleteAllPathPictureAssociated(array $paths, array $productRepo)
    {
        if(count($paths) > 0)
        {
            foreach ($paths as $path) {

                unlink($path['img_path']); //Delete each file associated
            }
            (new ImageRepository())->deleteAll('img', 'id_product', $productRepo['product_id']); //Delete all picture associated in db
        }
    }
}