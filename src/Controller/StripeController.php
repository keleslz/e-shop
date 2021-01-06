<?php
namespace App\Controller;

use Stripe\Stripe;
use App\Entity\User;
use App\Entity\Order;
use Stripe\Checkout\Session;
use App\Lib\Session\CartSession;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use App\AbstractClass\AbstractController;
use App\Lib\Session\Session as mySession;

class StripeController extends AbstractController
{ 
    /**
     * Create and display id key 
     * Minimum payment is 0.50 USD
     */
    public function createCheckoutSession ()
    {
      if($_SERVER['REQUEST_METHOD'] !== 'POST')
      {
        http_response_code(404);
        die();
      }
      $sum = (new CartSession())->getTotalPrice() * 100;

      if($sum < 50)
      {
        http_response_code(404);
        $this->redirectTo('shop/cart');
        die();
      }

      \Stripe\Stripe::setApiKey('sk_test_4eC39HqLyjWDarjtT1zdp7dc');
      header('Content-Type: application/json');
      $YOUR_DOMAIN = 'http://localhost:8000';
      $checkout_session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
          'price_data' => [
            'currency' => 'eur',// in cents
            'unit_amount' => $sum, 
            'product_data' => [
              'name' => "Montant total : ",
              'images' => ["https://i.imgur.com/EHyR2nP.png"],
            ],
          ],
          'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/public/stripe/success',
        'cancel_url' => $YOUR_DOMAIN . '/public/stripe/error',
      ]);
      echo json_encode(['id' => $checkout_session->id]);
    }

    /**
     * Case payment success
     */
    public function success()
    { 
      $session = new mySession();

      if(!isset($_SESSION['_cart']) || empty($_SESSION['_cart']) || !isset($_SESSION['_order']) || empty($_SESSION['_order']))
      {
          http_response_code(404);
          $this->redirectTo('shop/home');
          die();
      }

      $order = $_SESSION['_order'];

      $orderRepo = new OrderRepository();

      if($store = $orderRepo->create($order))
      {
        $session->set('user','success', 'Merci pour votre confiance à très vite');
      }else{
        $session->set('order','error', 'Erreur enregistrement commande');
      };
      // TODO envoi email
      
      $orderRepo->disconnect();

      if($store) {

        unset($_SESSION['_cart']);
      }

      $this->redirectTo('shop/home');
    }

    /**
     * Case payment error
     */
    public function error()
    {
      $session = new mySession();
      $session->set('user','info', "Abandon du panier");
      $this->redirectTo('shop/home');
    }
}