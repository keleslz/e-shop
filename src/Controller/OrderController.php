<?php
namespace App\Controller;

use App\Lib\input\Input;
use App\Repository\OrderRepository;

class OrderController {

    /**
     * Update state sorder :  ex : valid, reject
     * @param string $param
     */
    public function update() : void
    {
        header('Content-Type: application/json;charset=utf-8');
        
        if($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            http_response_code(404);
            die();
        }

        if(!isset($_POST['data']) || empty($_POST['data']))
        { 
            http_response_code(401);
            die();
        }  

        //TODO ajouter les droit (si n'est pas admin 401 die)        
        $post = (new Input())->cleaner($_POST['data']);
        
        $state = $post['choice'] === 'accept' ? 1 : -1;

        $orderRepo = new OrderRepository();

        if ( $orderRepo->updateState(intval($post['id']), $state ) )
        {
            echo json_encode([
                'success' => true,
                'choice' => $post['choice'] === 'accept' ? 'accept' : 'reject'
                ]);
            http_response_code(200);
            die();
        }
        
        $orderRepo->disconnect();
        http_response_code(401);
        die();

    }
}