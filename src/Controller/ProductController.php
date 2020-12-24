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
use App\AbstractClass\AbstractController;

class ProductController extends AbstractController
{
    const PRODUCT_NAME_TABLE = 'product';
    const PRODUCT_CATEGORY_ID_FIELD = 'id_category';

    public function create()
    {
        $session = new Session();
        $userSession = new UserSession();
        $user = $userSession->get('_userStart');
        $userSession->ifNotConnected();
        $userSession->ifNotContributor();

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

            if($fileValidator->full())
            {   
                $good  = $fileValidator->controle();
                in_array(false, $good) ? $this->redirectTo('product/create') : '';
            }
            
            if(!isset($post['status']))
            {
                $session->set('product', 'error', 'Veuillez selectionner le statut du produit : online (On) / offline (Off)');
                return $this->redirectTo("product/create");
            }
            
            if (empty($empty) && !in_array(false, $conform))
            {   
                $idImage = (new Image())->ifImageExistCreate($picture);
                $product = new Product();
                $productRepo = new ProductRepository();
                $product->create($post, $productRepo, $session , $idImage);
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

    public function show($param)
    {
        $id = intval(strip_tags($param));

        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifNotContributor();

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

    public function edition($param)
    {
        $id = intval(strip_tags($param));

        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifNotContributor();
        
        $userData = (new UserRepository())->findOneBy('user','id', $user['id']);

        $productRepo = new ProductRepository();
        $input = new Input();
        
        $product = $productRepo->findOneBy('product','product_id', $id);

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

            $imageValidator = new FileValidator($_FILES);
            
            if($imageValidator->full())
            {   
                $good  = $imageValidator->controle();
                in_array(false, $good) ? $this->redirectTo("product/edition/$id") : null ;
            }

            (new Image())->ifImageAssiociatedExist($id, $uniqId, $this);
            
            $picture = $_FILES['product_picture'] ?? [];
            $fileValidator = new FileValidator($picture);
            
            if($fileValidator->full())
            {
                $good  = $fileValidator->controle();
                in_array(false, $good) ? $this->redirectTo("product/edition/$id") : null ;
            }
                
            if(!isset($post['status']))
            {
                return $this->redirectTo("product/create");
            }
            
            if(in_array(false, $conform))
            {
                return $this->redirectTo("product/edition/$id");
            }

            if(empty($empty))
            {
                (new Product())->edition($post, $uniqId, $id, $this);
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

    public function delete($param)
    {
        $id = intval(strip_tags($param));
        $session = new UserSession();
        $user = $session->get('_userStart');
        $session->ifNotConnected();
        $session->ifNotContributor();

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

    /**
     * Get product by price
     * @param string $param type 'asc' or desc
     */
    public function byFilter($param) : void
    { 
        header('Content-Type: application/json');
        http_response_code(200);

        $result = [];
        $price = $param === 'asc-price' || $param === 'desc-price';
        $name = $param === 'asc-name' || $param === 'desc-name';
        $news = $param === 'asc-news' || $param === 'desc-news';

        if ($price)
        {
            $asc = htmlspecialchars($param) === 'asc-price';

            $asc
                ? $result = (new FilterRepository())->findAllByFilter('product_price', 'asc')
                : $result = (new FilterRepository())->findAllByFilter('product_price', 'desc')
            ;
            echo json_encode(['result' => $result ]);
            return;
        }

        if ($name)
        {
            $asc = htmlspecialchars($param) === 'asc-name';

            $asc
                ? $result = (new FilterRepository())->findAllByFilter('product_name', 'asc')
                : $result = (new FilterRepository())->findAllByFilter('product_name', 'desc')
            ;
            echo json_encode(['result' => $result ]);
            return;
        }

        // TODO Ajouter filtre par nouveauté en ajoutant un created at au produit 
        http_response_code(401);
    }
}