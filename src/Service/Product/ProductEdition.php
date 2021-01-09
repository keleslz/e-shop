<?php 
namespace App\Service\Product;

use App\Entity\Image;
use App\Entity\Product;
use App\Lib\Session\Session;
use App\Lib\File\FileValidator;
use App\AbstractClass\AbstractController;

class ProductEdition 
{
    private $fileValidator;

    private $controller;

    private $post;

    private int $productId;

    public function __construct(FileValidator $fileValidator, AbstractController $controller, array $post, int $productId)
    {
        $this->fileValidator = $fileValidator;
        $this->controller = $controller;
        $this->post = $post;
        $this->productId = $productId;
    }

    /**
     * Edit product
     */
    public function edit(string $uniqId) : void
    {   
        $this->haveImage();
        (new Image())->ifImageAssiociatedExist($this->productId, $uniqId, $this->controller);
        $this->havePictureAssociated();
        $this->checkStatus();
       
        (new Product())->edition($this->post, $uniqId, $this->productId, $this->controller);
    }

    /**
     * Check if product have image 
     */
    private function haveImage() : void
    {
        if($this->fileValidator->full())
        {   
            $good  = $this->fileValidator->controle();
            in_array(false, $good) ? $this->controller->redirectTo("product/edition/$this->productId") : null ;
        }
    }
    
    /**
     * check if have picture associated
     */
    private function havePictureAssociated() : void
    {
        $picture = $_FILES['product_picture'] ?? [];
        $fileValidator = new FileValidator($picture);
        
        if($fileValidator->full())
        {
            $good  = $fileValidator->controle();
            in_array(false, $good) ? $this->controller->redirectTo("product/edition/$this->productId") : null ;
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

