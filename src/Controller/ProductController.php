<?php

namespace App\Controller;

use PDO;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Category;
use App\Lib\input\Input;
use Stripe\Terminal\Reader;
use App\Lib\Session\Session;
use App\Repository\Repository;
use App\Lib\File\FileValidator;
use App\Lib\Session\UserSession;
use App\Repository\FileRepository;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\FilterRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Service\Product\ProductCreation;
use App\AbstractClass\AbstractController;
use App\Service\Product\ProductEdition;

class ProductController extends AbstractController
{
    const PRODUCT_NAME_TABLE = 'product';
    const PRODUCT_CATEGORY_ID_FIELD = 'id_category';

    /**
     * Create product
     */
    public function create() : void
    {
        $session = new Session();
        $userSession = new UserSession();
        $user = $userSession->get('_userStart');
        $userSession->ifNotConnected();
        $userSession->isClient();

        $input = new Input();

        $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

        $empty = [];
        $good = [];
        $input->save($_POST);
        
        if(count($_POST) > 0)
        {
            $post = $input->cleaner($_POST);
            $empty = $input->isEmpty($_POST);
            $conform = $input->hasGoodformat($_POST);
            
            $picture = $_FILES['product_picture'];
            $fileValidator = new FileValidator($picture);

            if (empty($empty) && !in_array(false, $conform))
            {
                (new ProductCreation( $fileValidator, $this, $post ))->create();
            } 
        }

        $this->render('admin/product/create', [
            'email' => $userData['email'],
            'empty' => $empty,
            'session' => $session,
            'save' => $_SESSION['saveInputs'] ?? null,
            'categories' => (new CategoryRepository())->findAll('category'),
            'law' => intval($userData['law']),


        ]);
        
        (new Repository())->disconnect();
    }

    
    /**
     * Show product
     */
    public function show($param)
    {
        $id = intval(htmlspecialchars($param));

        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->isClient();

        $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

        $repo = new ProductRepository();
        $products = $repo->findAllProductAndImage();
        $currentProduct = $repo->findOneBy('product', 'product_id', $id);
        $currentimg = (new FileRepository())->findImageProduct($currentProduct['id_img'] ?? null);  
        $category = new CategoryRepository();
        
        if( isset($currentProduct['id_category']) ){
            $currentProductCategory = $category->findOneBy('category','category_id' , intval($currentProduct['id_category']));
        }
        $this->render('admin/product/show', [
            'email' => $userData['email'],
            'products' => $products,
            'currentProduct' =>  $currentProduct ,
            'currentImage' => $currentimg,
            'currentCategoryName' => $currentProductCategory['category_name'] ?? 'Non classée' ,
            'session' => $session,
            'price' => $currentProduct['product_price'] ?? null,
            'categories' => $category->findAll('category'),
            'law' => intval($userData['law']),
        ] );
        
        (new Repository())->disconnect();
    }

    
    /**
     * Edit product
     */
    public function edition($param)
    {
        $id = intval(strip_tags($param));

        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->isClient();
        
        $userData = (new UserRepository())->findOneBy('user','id', $user['id']);
        $input = new Input();
        
        $product = (new ProductRepository())->findOneBy('product','product_id', $id);
        
        if(!$product)
        {
            $session->set('product','error', 'Désolé une erreur est survenue');
            $this->redirectTo('product/show');
        }
        
        $empty = [];
        $currentImg = (new FileRepository())->findImageProduct( $product['id_img'] ?? null); 
        $input->save($_POST) ;
        
        if(count($_POST) > 0)
        {
            $post = $input->cleaner($_POST);
            $empty = $input->isEmpty($_POST);
            $uniqId = uniqid((string)(rand()*10));
            $conform = $input->hasGoodformat($post);
            
            if(in_array(false, $conform))
            {
                return $this->redirectTo("product/edition/$id");
            }

            $imageValidator = new FileValidator($_FILES);
            
            if(empty($empty))
            {
                (new ProductEdition($imageValidator, $this, $post, $id))->edit($uniqId);
            } 
        }

        $this->render('admin/product/edition', [
            'email' => $userData['email'],
            'product' => $product,
            'empty' => $empty,
            'currentImg' => $currentImg,
            'imageAssociated' => (new ImageRepository())->findAllbyIdProduct($id),
            'session' => $session,
            'categories' => (new CategoryRepository())->findAll('category'),
            'law' => intval($userData['law']),
        ]);
        
        (new Repository())->disconnect();
    }

    
    /**
     * Delete product
     */
    public function delete($param)
    {   
        $id = intval(strip_tags($param));
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifSimpleContributor();
        $session->isClient();
        
        $repo = new ProductRepository();
        $productRepo = $repo->findOneBy('product', 'product_id', $id);
        
        $product = new product();

        if($productRepo)
        {
            $imageRepo = new ImageRepository();
            $paths = $imageRepo->findAllBy('img', 'id_product', $productRepo['product_id']);
            $productCoverId = $productRepo['id_img'];
            
            $product->deleteCover($productCoverId, $productRepo);
            $product->deleteAllPathPictureAssociated($paths, $productRepo);
            $deleteProduct = $repo->delete('product', 'product_id', $productRepo['product_id'] );

            $deleteProduct 
            ? $session->set('product','success', 'Produit supprimé')
            :  $session->set('product','success', 'Désolé une erreur est survenue');

            $this->redirectTo('product/show');
        }else{
            
            $session->set('product','error', 'Désolé une erreur est survenue');
            $this->redirectTo('product/show');
        }
        $repo->disconnect();
    }

    /**
     * get all product & category and display them
     */
    public function getAll()
    {
        $product = (new ProductRepository())->findAllCards();
        $category = (new CategoryRepository())->findAll('category', PDO::FETCH_ASSOC);

        echo json_encode([
            'category' => $category,
            'product' => $product
        ]);

        (new Repository())->disconnect();
    }

    /**
     * Get product by category thanks category id
     * @param int $id product id
     */
    public function getProductByCategory($id)
    {   
        if(is_nan($id))
        {
            http_response_code(400);
            echo json_encode(false);
            die();
        };

        $id = intval(htmlspecialchars($id));
        $productRepo = new ProductRepository();
        $product = $productRepo->findAllBy( 
            self::PRODUCT_NAME_TABLE ,
            self::PRODUCT_CATEGORY_ID_FIELD,
            intval($id)
        );

        $category = (new CategoryRepository())->findAll('category', PDO::FETCH_ASSOC);
        $productRepo->disconnect();

        if(is_array($product) && is_array($category))
        {
            http_response_code(200);
            echo json_encode(['product' => $product, 'category' => $category]);
            return;
        }

        http_response_code(400);
        echo json_encode('Une erreur est suvenue Code : Produit par Categorie');
        return;
    }
}