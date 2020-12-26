<?php
namespace App\Entity;

use App\Entity\File;
use App\Lib\Session\Session;
use App\Lib\File\FileValidator;
use App\Repository\FileRepository;
use App\Repository\ImageRepository;

class Image extends File
{
    /**
     * Create a picture in img-storage
     */
    public function createImage(array $picture, ImageRepository $imageRepo, $idProduct)
    {   
        $session = new Session();

        if( $imageRepo->createImage($this, $idProduct) ) {

            copy($picture['tmp_name'], $this->getDestination())
            ? $session->set('file','success','Votre image a bien été enregisté')
            : $session->set('file','error',"Votre image n'a pas pu être enregistré");
        }else{
            $session->set('file','error','Désolé une erreur est survenue, impossible de sauvegarder l\'image');
        } 
    }

    /**
     * return a repo an provide $idImage
     */
    public function ifImageExistCreate(array $picture) : ?int
    {
        $idImage = -1 ;

        if( !empty($picture['name']) )
        {   
            $fileRepo = new FileRepository();
            
            $uniqId = uniqid((string)(rand()*10));

            $this->setName( $uniqId . $picture['name'] );
            $this->getPath();

            $this->create($picture, $fileRepo);
            $idImage = $fileRepo->findOneBy('img', 'img_path',  $this->getPath())['img_id'];
        }
        
        return $idImage;
    }

    /**
     * If pictue associated exist do treatment then redirecto user
     */
    public function ifImageAssiociatedExist(int $id, string $uniqId, $controller)
    {   
        if(isset($_FILES['more_product_picture']) )
        {  
            if ( empty($_FILES['more_product_picture']['name']) )
            {
                return $controller->redirectTo("product/edition/$id");
            }

            $picture = $_FILES['more_product_picture'];
            $imageRepo = new ImageRepository();
            $fileValidator = new FileValidator($picture);

            if($fileValidator->full())
            {
                $good  = $fileValidator->controle();
                in_array(false, $good) ? $controller->redirectTo("product/edition/$id") : null ;
            }
            
            $this->setName( $uniqId . $picture['name'] );
            $this->getPath();

            $this->createImage($picture, $imageRepo, $id);
            
            $controller->redirectTo('product/edition/' . $id);
        }
    }

    public function whenEditIfImageExist( array $files , string $uniqId) :  ?int
    {
        $idImage = null;

        if( !empty($files['product_picture']['name']))
        {   
            $fileRepo = new FileRepository();
            $picture = $files['product_picture'];
            $this->setName( $uniqId . $picture['name'] );
            $this->getPath();

            $this->create($picture, $fileRepo);
            $idImage = $fileRepo->findOneBy('img', 'img_path',  $this->getPath())['img_id'];
        }

        return $idImage;
    }
}