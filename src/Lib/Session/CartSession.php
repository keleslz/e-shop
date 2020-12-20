<?php
namespace App\Lib\Session;

use App\Lib\Input\InputError;
use App\Repository\ProductRepository;

class CartSession extends Session
{   
    const CART_NAME = '_cart';

    /**
     * Get total price cart an return price
     */
    public function getTotalPrice() : float
    {
        $sum =  [];

        foreach ($_SESSION[ self::CART_NAME ] as $value)
        {
            $price = (new ProductRepository())->findOnePrice($value['id']) ;

            if($price === 0.00)
            {
                $this->set('payment', 'error', 'Désolé une erreur est survenue, "PRIX" ');
                header('Location:/public/shop/cart');
                die();
            }

            $sum[] = (($price * 100) * intval($value['quantity']));
        }

        $sum = floatval(array_sum($sum) / 100);

        return $sum;
    }
}