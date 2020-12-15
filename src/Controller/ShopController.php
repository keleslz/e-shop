<?php
namespace App\Controller;

use App\Entity\Bill;
use ImageRepository;
use App\Entity\Client;
use App\Lib\input\Input;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\AbstractClass\AbstractController;

class ShopController extends AbstractController
{
    public function accueil()
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
            'products' => (new ProductRepository())->findAllCards(),
            'session' => $session,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'cart' => $session->get('_cart'),
            'email' => 'merde'
        ]);

        (new Repository())->disconnect();
    }

    public function show($param)
    {
        $userSession = new UserSession();

        $idProduct = intval(strip_tags($param));
        $product = (new ProductRepository())->findOneBy('product','product_id', $idProduct);

        if(!$product)
        {
            $userSession->set('product','error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/accueil');
            die();
        }

        $user = $_SESSION['_userStart'] ?? null;

        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }
        
        if(isset($_POST))
        {
            $input = new Input();
            $post = $input->cleaner($_POST);
            $good = $input->hasGoodformat($post);

            if(is_array($good) && !in_array(false, $good) )
            {
                $userSession->cart($post);
            }
        }

        $picture = new ImageRepository();
        $productImg = $picture->findImageProduct( $product['id_img'] ?? null);

        $this->render('shop/article/show', [
            'categories' => (new CategoryRepository())->findAll('category'),
            'product' => $product,
            'productImg' => $productImg,
            'adminSessionLaw' => $adminSession['law'] ?? null,
            'adminSession' => $adminSession ??  null,
            'session' => $userSession,
            'cart' => $userSession->get('_cart'),
            'pictureAssociated' => $picture->findAllBy('img','id_product', $product['product_id'] )
        ]);

        (new Repository())->disconnect();
    }
    
    public function panier()
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
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/accueil');
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
                $this->redirectTo('shop/payment');
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

    public function payment()
    {
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        (new Bill([], true))->restartPurchase($this);

        if(!(isset($_SESSION['_customer']['delivery'])))
        {
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/accueil');
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
            $this->redirectTo('shop/accueil');
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
            $this->redirectTo('shop/accueil');
            die();
        }

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

        $this->redirectTo('shop/accueil'); */

        $this->redirectTo('bill/generate');
    }
}