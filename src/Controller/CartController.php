<?php
namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use App\Lib\input\Input;
use App\AbstractClass\AbstractController;
use App\Lib\Session\Session;
use App\Lib\Session\UserSession;
use App\Repository\ProductRepository;

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
                header('Content-Type: application/json');
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

    public function quantity() : void
    {   
        if(isset($_POST['id']))
        {   
            $id = intval(htmlentities($_POST['id']));

            $product = (new ProductRepository())->findOneBy('product', 'product_id', $id);

            if($product)
            {   
                (new Session());

                http_response_code(200);
                
                if(!isset($_SESSION['_cart']) || !is_array($_SESSION['_cart'] ))
                {   
                    echo json_encode(['quantity' => 1 ]);
                    return;
                }

                foreach( $_SESSION['_cart'] as $key => $value)
                {
                    if( intval($value['id']) === intval($id))
                    {
                        header('Content-Type: application/json');
                        echo json_encode(['quantity' => $value['quantity']]);
                        return;
                    }
                }
                echo json_encode(['quantity' => 1 ]);
                return;
            } 
            
            http_response_code(400);
            return;
        }
    }

    public function remove() : void
    {
        if(isset($_POST['id']))
        {   
            $productId = intval(htmlentities($_POST['id']));
            $state = false;
            (new Session());

            header('Content-Type: application/json');

            if( isset($_SESSION['_cart']))
            {   
                foreach( $_SESSION['_cart'] as $key => $value)
                {
                    $id = intval($value['id']);
                    
                    if(  $id === intval($productId))
                    {
                        unset($_SESSION['_cart'][$id]);
                        
                        echo json_encode([
                            'id' => intval($productId) ,
                            'remove' => true
                        ]);
                        http_response_code(200);
                        die();
                    }
                }

                http_response_code(401);
                echo json_encode(['remove' => $state]);
                return;
            } 

            echo json_encode(['remove' => $state]);
            http_response_code(401);
            return;
        }
    }
}