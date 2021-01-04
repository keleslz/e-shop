<?php
namespace App\Controller;

use App\Entity\Bill;
use App\Entity\Order;
use App\Entity\Client;
use App\Lib\input\Input;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\AbstractClass\AbstractController;
use App\Lib\Session\CartSession;
use App\Lib\Session\Session;

class ShopController extends AbstractController
{
    const USER_TABLE_NAME = 'user';
    const EMAIL_TABLE_FIELD_NAME = 'email';
    const USER_ID_FIELD = 'id';

    public function home()
    {   
        $session = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;
        
        $adminSession = [];

        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }

        $this->render('shop/article/home', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'session' => $session,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'cart' => $session->get('_cart'),
        ]);

        (new Repository())->disconnect();
    }

    public function show($param)
    {
        $userSession = new UserSession();
        $idProduct = intval(htmlspecialchars($param));
        $productRepo = new ProductRepository();
        $product = $productRepo->findOneBy('product','product_id', $idProduct);
        $currentProductCategory = null;
        if(!$product)
        {
            $userSession->set('product','error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/home');
            die();
        }
        
        $user = $_SESSION['_userStart'] ?? null;
        
        if(isset($user['id']))
        {
            $user = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }
        
        if( isset($product['id_category']) ){
            $currentProductCategory = $productRepo->findOneBy('category','category_id' , intval($product['id_category']));
        }
        
        
        $picture = new ImageRepository();
        $productImg = $picture->findImageProduct( $product['id_img'] ?? null);
        

        $this->render('shop/article/show', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'product' => $product,
            'productImg' => $productImg,
            'adminSessionLaw' => $user['law'] ?? null,
            'adminSession' => $user ??  null,
            'session' => $userSession,
            'cart' => $userSession->get('_cart'),
            'pictureAssociated' => $picture->findAllBy('img','id_product', $product['product_id'] ),
            'currentProductCategory' => $currentProductCategory['category_name'] ?? null
        ]);

        (new Repository())->disconnect();
    }
    
    public function cart()
    {
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        $adminSession = [];
        
        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }

        $cart = $userSession->get('_cart') ?? [];

        if(count($cart) > 0)
        {
            $productsCart = (new ProductRepository())->findAllcartRepo($cart);
        }

        $this->render('shop/purchase/cart', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'products' => (new ProductRepository())->findAllCards(),
            'session' =>  $userSession,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'cart' => $cart,
            'productsCart' =>  $productsCart ?? []
        ]);

        (new Repository())->disconnect();
    }

    public function delivery()
    {
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        $adminSession = [];
        
        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
            unset($adminSession['password']);
            unset($adminSession[2]);
        }

        $cart = $userSession->get('_cart') ?? [];

        if(!count($cart) > 0)
        {
            $userSession->set('shop', 'error', 'Accès refusé : Panier vide');
            $this->redirectTo('shop/home');
            die();
        }
        
        $input = new Input();

        if( count($_POST) > 0 )
        {
            $post = $input->cleaner($_POST);
            $empty = $input->isEmpty($_POST);
            $conform = $input->hasGoodformat($post);
           
            if (empty($empty) && !in_array(false, $conform))
            {   
                $_SESSION['_customer']['delivery'] = (new Client($post));
                $this->redirectTo('stripe/createCheckoutSession');
                
            }else{
                $post = $input->cleaner($_POST);
                $input->save($post);
            }
        }

        $this->render('shop/purchase/delivery', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'products' => (new ProductRepository())->findAllCards(),
            'session' =>  $userSession,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'cart' => $cart,
            'saveInputs' => $userSession->get('saveInputs') ?? []
        ]);

        (new Repository())->disconnect();
    }

    /**
     * Store customer delivery information on session
     */
    public function registreUserOnSession() : void
    {
        header('Content-Type: application/json;charset=utf-8');
        
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(404);
        }

        if(!isset($_POST) || empty($_POST))
        { 
            http_response_code(401);
        }  

        $input = new Input();
        $post = $input->cleaner($_POST);
        $empty = $input->isEmpty($_POST);
        $conform = $input->hasGoodformat($_POST);
        
        if (empty($empty) && is_array($conform) && !in_array(false, $conform))
        {   
            $session =  new CartSession();
            $user = $session->get('_userStart');
            $cart = $session->get('_cart');
            
            $totalPrice = $session->getTotalPrice($cart);
            $order = (new Order($post))->setArticle($cart)->setTotal($totalPrice);
            
            if(isset($user['id']))
            {   
                $userRepo = (new UserRepository())->findOneBy(self::USER_TABLE_NAME, self::USER_ID_FIELD, intval($user['id']));
                $userId = is_array($userRepo) ? intval($userRepo['id']) : null ;
                $order->setUserId($userId);
            }
            
            $_SESSION['_order'] = $order;
            var_dump($order);
            http_response_code(200);
            return;
        }

        http_response_code(401);
    }

  /*   public function payment()
    {
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        (new Bill([], true))->restartPurchase($this);

        if(!(isset($_SESSION['_customer']['delivery'])))
        {
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/home');
        }

        $adminSession = [];
                
        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
            unset($adminSession['password']);
            unset($adminSession[2]);
        }

        $cart = $userSession->get('_cart') ?? [] ;

        
        if(count($cart) > 0)
        {
            $productsCart = (new ProductRepository())->findAllcartRepo($cart);
        }else{
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/home');
        }

        $input = new Input();

        if( count($_POST) > 0 )
        {
            $post = $input->cleaner($_POST);
            $empty = $input->isEmpty($_POST);
            $conform = $input->hasGoodformat($post);
            
            if (empty($empty) && !in_array(false, $conform))
            {   
                $deliveryCustomer =  $_SESSION['_customer']['delivery'];
                $billingCustomerInfo = $_SESSION['_customer']['customer'] = (new Client($post, 'customer', $deliveryCustomer->getIdBilling()));
                $this->redirectTo('shop/treatment');
            }else{
                $post = $input->cleaner($_POST);
                $input->save($post);
            }
        }
        
        $this->render('shop/purchase/payment', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'products' => (new ProductRepository())->findAllCards(),
            'session' =>  $userSession,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'cart' => $cart,
            'saveInputs' => $userSession->get('saveInputs') ?? [],
            'productsCart' =>  $productsCart ?? [],
            'customerSession' =>  $_SESSION['_customer']
        ]);

        (new Repository())->disconnect();
    }
 */

    public function treatment()
    {     
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        (new Bill([], true))->restartPurchase($this);

        $adminSession = [];

        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }

        $cart = $userSession->get('_cart') ?? [];
        
        if(!isset($_SESSION['_customer']['customer']) || count($cart) < 0)
        {
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/home');
            die();
        }

        //TODO Paiement avec Api stripe puis redirection vers Bill/show/??{param}

        /**
         * Payment with stripe API then redictTo Bill/show
         * Facture create and send by mail and can be downloaded
         * If treatmont is good 
         * display meesage "paiement effectué un mail de confirmation vous a été envoyé"
         * unset session['_cart']
         * unset session['_customer']
        */
        /* 
        $_SESSION['_cart'] = null;
        $_SESSION['_customer'] = null;

        $userSession->set('user', 'success','Paiement effectué avec succès');

        $this->redirectTo('shop/home'); */

        // redirection vers -> $this->redirectTo('bill/generate'); 
        // redirection vers -> $this->redirectTo('shop/home'); 
    }
}