<?php

namespace App\Controller;

use App\Entity\Bill;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\AbstractClass\AbstractController;

class Billcontroller extends AbstractController
{
    public function generate()
    {
        $userSession = new UserSession();
        $user = $_SESSION['_userStart'] ?? null;

        $adminSession = [];

        if(isset($user['id']))
        {
            $adminSession = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        }

        $cart = $userSession->get('_cart') ?? null;

        if(!isset($_SESSION['_customer']) || !isset($cart))
        {
            $userSession->set('shop', 'error', 'Désolé une erreur est survenue');
            $this->redirectTo('shop/accueil');
            die();
        }

        $_SESSION['_billing'] = true;
        (new Bill($cart));
    }
}