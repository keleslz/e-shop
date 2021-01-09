<?php

namespace App\Entity;

use App\Entity\Pdf;
use App\Entity\Client;
use App\Lib\Session\Session;
use App\Repository\ProductRepository;
use App\AbstractClass\AbstractController;

class Bill extends Client
{
    private int $id ;

    private  $productList;

    private bool $restart = false;

    
    /**
     * @param array $cart contains all product bought 
     * @param array|bool $productList is a bool when restart is true
     */
    public function __construct(array $cart = [], bool $restart = false)
    {   
        $this->restart = $restart;
        $this->cart = $cart; 
        $this->productList = $this->sortProductList();
        $this->create();
    }

    public function restartPurchase(AbstractController $abstractController) : void
    {   
        $billingSession = $_SESSION['_billing'] ?? null;

        if( $billingSession === true)
        {
            (new Session())->set('user','info', 'De retour ? Faites vous plaisir');
            $_SESSION['_cart'] = null;
            $_SESSION['_customer'] = null;
            $_SESSION['_billing'] = null;
            $abstractController->redirectTo('shop/home');
        }
    }

    /**
     * @return array|false
     */
    private function sortProductList()
    {
        if( $this->restart === true)
        {
            return false;
        }

        $productList = [];
        
        foreach ($this->cart as $key => $quantity) {
            
            $productRepo = (new ProductRepository())->findOneBy('product', 'product_id', intval($key) );
            $productList[] = [ 
                'product' => $productRepo,
                'quantity' => $quantity,
            ];
        }

        return $productList;
    }

    /**
     * @return Pdf|false
     */
    public function create()
    {
        if( $this->restart === true)
        {
            return false;
        }
        
        $customer = [
            'delivery' => $this->customerInfo = $_SESSION['_customer']['delivery'],
            'customer' => $this->customerBillingInfo = $_SESSION['_customer']['customer'],
        ];

        return new Pdf($this->productList, $customer);
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
}