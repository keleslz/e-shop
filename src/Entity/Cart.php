<?php 

namespace App\Entity;

use App\Entity\Product;
use App\Repository\Repository;

class Cart {

    const TABLE_PRODUCT_NAME = 'product';
    const PRODUCT_ID_FIELD = 'product_id';
    const SESSION_CART_NAME = '_cart';

    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Add product on array session
     * @return array|false
     */
    public function add()
    {   
        $productExist = $this->ifProductExist($this->product->getId());
        return  $productExist && $this->checkFormat() ? $this->push() : false;
    }

    /**
     * Check datas format send by user
     */
    private function checkFormat() : bool
    {
        $error = [];
        $error[] = is_int($this->product->getId());
        $error[] = is_int($this->product->getQuantity());
        
        return !in_array(false, $error);
    }

    /**
     * Check if id product exist
     * @return false|array
     */
    private function ifProductExist(int $id) 
    {
        return (new Repository())->findOneBy(self::TABLE_PRODUCT_NAME, self::PRODUCT_ID_FIELD, $id);
    }

    /**
     * Store the products cart on session or delete that
     */
    private function push() : array
    {     
        $this->product->getQuantity() === 0 
            ? $this->removeProduct()
            : $_SESSION[ self::SESSION_CART_NAME ][ $this->product->getId() ] = $this->getProductProperty()
        ;
        
        return $_SESSION[ self::SESSION_CART_NAME ];
    }

    /**
     * Get product properties and return them on an array
     */
    private function getProductProperty() : array
    {
        return [
            'id' => $this->product->getId(),
            'name' => $this->product->getName(),
            'quantity' => $this->product->getQuantity(),
        ];
    }

    /**
     * remove a product on array session thanks it product id 
     */
    public function removeProduct() : array
    {
        unset($_SESSION[ self::SESSION_CART_NAME ][ $this->product->getId() ]);
        return $_SESSION[ self::SESSION_CART_NAME ];
    }

}