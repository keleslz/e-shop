<?php
namespace App\Controller;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Lib\Input\InputError;
use App\Lib\Session\CartSession;
use App\Repository\ProductRepository;
use App\AbstractClass\AbstractController;
use App\Lib\Session\Session as mySession;

class StripeController extends AbstractController
{ 
    /**
     * Create and display id key 
     */
    public function createCheckoutSession ()
    {
      $sum = (new CartSession())->getTotalPrice() * 100;

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

    public function checkout()
    {
      $this->render('stripe/checkout');
    }

    /**
     * Case payment success
     */
    public function success()
    { 
      $session = new mySession();
      unset($_SESSION['_cart']);
      $session->set('user','success', 'Merci pour votre confiance à très vite');
      $this->redirectTo('shop/home');
    }

    /**
     * Case payment error
     */
    public function error()
    {
      $session = new mySession();
      $session->set('user','error', "Une erreur est survenue lors du paiement, nous vous conseillons de vous rapprocher de votre banque et de réessayer ultèrieuement ou d'éssayer avec une autre carte de crédit");
      $this->redirectTo('shop/home');
    }
}