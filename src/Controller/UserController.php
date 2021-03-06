<?php

namespace App\Controller;

use App\Lib\Tool;
use App\Entity\User;
use App\Lib\Input\Input;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\Repository;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\Service\User\UserCreation;
use App\Service\User\UserUpdateEmail;
use App\Service\User\UserUpdatePassword;
use App\AbstractClass\AbstractController;
use App\Repository\OrderRepository;
use App\Service\User\UserDelete;
use App\Services\User\UserAuthentification;
use DateTime;

class UserController extends AbstractController
{
    /**
     * User dashboard
     */
    public function dashboard()
    {
        $userSession =  new UserSession();
        $userSession->ifNotConnected();
        $user = $userSession->get('_userStart');

        $userData = (new UserRepository())->findOneBy('user','id', intval($user['id']));
        $orderRepo = (new OrderRepository());
        
        if(($userData['law']) > 1 )
        {
            $ordersValidated = $orderRepo->findAllValidated();
            $ordersRejected = $orderRepo->findAllRejected();
            $ordersNoValidated = $orderRepo->findAllNoValidated();
        }
        
        $myOrders = $orderRepo->findAllBy('`order`','id_user', intval($user['id']));
        
        (new Repository())->disconnect();

        $this->render('admin/user/dashboard', [
            'email' => $userData['email'],
            'law' => $userData['law'],
            'session'=> (new Session()),
            'orders' => ['validated' => $ordersValidated ?? [], 'noValidated' => $ordersNoValidated ?? [], 'rejected' =>  $ordersRejected ?? [] ],
            'myOrders' => $myOrders
        ]);
    }

    /**
     * User connect
     */
    public function signIn()
    {    
        $error = [];
        $userSession = new UserSession();
        $userSession->ifConnected();
        
        if( count($_POST) > 0 )
        {
            $data =  (new Input)->hasGoodFormat($_POST);
            $error = InputError::get($data);
            
            if(!in_array( false, $data))
            {     
                (new UserAuthentification())->checkAuthentification($_POST['email'], $_POST['password'], $this);
            }
        }

        (new Repository())->disconnect();

        $this->render('admin/user/sign-in', [
            'error' => $error,
            'session' => $userSession
        ]);
    }

    /**
     * User suscribe
     */
    public function signUp()
    {   
        $state = null;
        $error = [];
        $session = new UserSession();
        $session->ifConnected();

        if( count($_POST) )
        {
            $data =  (new Input)->hasGoodFormat($_POST);
            $data['equal'] =  Tool::equal($_POST['password'], $_POST['confirmPass']) ;
            $error = InputError::get($data);

            if(!in_array( false, $data))
            {
                (new UserCreation())->new($_POST['email'], $_POST['password']);
            }
        }
        
        (new Repository())->disconnect();

        $this->render('admin/user/sign-up', [
            'state' => $state,
            'error' => $error,
            'session' => (new Session())
        ]);
    }

    /**
     * User edition email and password
     */
    public function edit()
    {
        $session = new UserSession();
        $userSession = $session->get('_userStart');
        $session->ifNotConnected();

        $userData = (new UserRepository())->findOneBy('user','id', $userSession['id']);

        if(count($_POST) > 0)
        {
            $input = new Input();
            $post = $input->cleaner($_POST);
            
            (new UserUpdateEmail($userData, $post));
            (new UserUpdatePassword($userData, $post));
        }

        (new Repository())->disconnect();

        $this->render('admin/user/edition', [
            'session' => (new Session()),
            'email' => $userData['email'],
            'law' => $userData['law'] ,
            'createdAt' => (new DateTime($userData['created_at']))->format('d/m/Y à H:i') ,
        ]);
        
    }
    
    /**
     * Redirect to dashboard if user not connected kill all session and redirect to
     * signin page
     */
    public function signOut()
    {   
        (new Repository())->disconnect();
        session_start();
        session_unset();
        $this->redirectTo('shop/home');
    }

    /**
     * Delete current user
     */
    public function delete()
    {   
        $session = new UserSession();
        $userRepo = new UserRepository(); 
        $session->ifNotConnected();
        $session->ifCreator();
        
        $idUser = $session->get('_userStart')['id'];
        $user  = $userRepo->findOneBy('user','id', intval($idUser));
        (new UserDelete( $user ));
    }
}