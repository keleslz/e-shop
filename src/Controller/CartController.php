<?php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Lib\input\Input;
use App\AbstractClass\AbstractController;
use App\Lib\Session\UserSession;

class CartController extends AbstractController
{   
    public function add() : void
    {    
        if(isset($_POST['product']))
        {   
            $post = $_POST['product'];

            (new UserSession());

            $post = (new Input())->cleaner($_POST['product']);
            $product = (new Product())->setProductForCard($post);
            
            if(is_array( (new Cart($product))->add() ))
            {   
                http_response_code(200);
                echo json_encode(['cart' => $_SESSION['_cart'], 'count'=> count($_SESSION['_cart']) ]);

                return;
            } 
            
            http_response_code(400);
            return;
        }

        $this->redirectTo('shop/home');
        die();
    }
}