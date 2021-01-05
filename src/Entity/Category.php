<?php
namespace App\Entity;

use App\Lib\input\Input;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class Category
{
    private int $id;

    private string $name;

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
        return ucFirst($this->name);
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

    public function create(array $post)
    {
        $categoryRepo = new CategoryRepository();
        $session = new Session();
        
        $this->setName($post['category_name']);

        if($categoryRepo->create($this)) 
        {
            $session->set('category', 'success',
            "La catégorie <strong>\"{$post['category_name']}\"</strong> a bien été enregistée");
        }else{
            (new Input())->save($post);
            $session->set('category', 'error',
            "La catégorie <strong>{$post['category_name']}</strong> n'a pas été enregisté car une erreur est survenue");
        }

        
    }
    
    public function delete(array $categories, $controller)
    {   
        $categoryRepo = new CategoryRepository();
        $session = new Session();

        foreach ($categories as $key => $category) {

            $idCategory = intval($category);
            
            $categoryExist = $categoryRepo->findOneBy('category', 'category_id', $idCategory ) ;
            
            if($categoryExist !== false)
            {   
                $categoryRepo->delete('category', 'category_id', intval($categoryExist['category_id'])) ;
                (new ProductRepository())->updateProductCategory($idCategory);
            }else{
                
                $session->set('category','error', (new InputError())::basicError());
                $controller->redirectTo('category/show');
                die();
            }
        }
        $categoryRepo->disconnect();
        $session->set('category','success', 'Categorie(s) supprimée(s)');
        $controller->redirectTo('category/show');
        die();
    }

    public function edition(array $post, int $idCategory)
    {
        $categoryRepo = new CategoryRepository();
        $session = new Session();
        
        $this->setName($post['category_name']);

        if($categoryRepo->update($idCategory, $this)) 
        {
            $session->set('category', 'success',
            "La catégorie <strong>\"{$post['category_name']}\"</strong> a bien été modifiée");
        }else{
            (new Input())->save($post);
            $session->set('category', 'error',
            "La catégorie <strong>{$post['category_name']}</strong> n'a pas été modifiée car une erreur est survenue");
        }
        

    }
    
}