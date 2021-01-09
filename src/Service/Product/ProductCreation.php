<?php 
namespace App\Service\Product;

use App\Entity\Image;
use App\Entity\Product;
use App\Lib\Session\Session;
use App\Lib\File\FileValidator;
use App\Repository\ProductRepository;
use App\AbstractClass\AbstractController;

class ProductCreation 
{
    private $fileValidator;

    private $controller;

    private $post;

    public function __construct(FileValidator $fileValidator, AbstractController $controller, array $post)
    {
        $this->fileValidator = $fileValidator;
        $this->controller = $controller;
        $this->post = $post;
    }

    /**
     * Create a product
     */
    public function create()
    {
        $this->haveImage();
        $this->checkStatus();
        
        $idImage = (new Image())->ifImageExistCreate($_FILES['product_picture']);
        $product = new Product();
        $product->create($this->post, (new ProductRepository()), (new Session()) , $idImage);
    }

    /**
     * Check if product have image 
     */
    private function haveImage() : void
    {
        if($this->fileValidator->full())
        {   
            $good  = $this->fileValidator->controle();
            in_array(false, $good) ? $this->controller->redirectTo('product/create') : '';
        }
    }

    /**
     * Check if product must be online or not
     */
    private function checkStatus() : void
    {
        if(!isset($this->post['status']))
        {
            (new Session())->set('product', 'error', 'Veuillez selectionner le statut du produit : online (On) / offline (Off)');
            $this->controller->redirectTo("product/create");
        }
    }
} 
