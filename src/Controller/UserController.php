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
use App\Service\User\UserDelete;
use App\Services\User\UserAuthentification;

class UserController extends AbstractController
{
    /**
     * User dashboard
     */
    public function dashboard()
    {
        (new UserSession())->ifNotConnected();
        $userSession = $_SESSION['_userStart'];

        $userData = (new UserRepository())->findOneBy('user','id', $userSession['id']);

        (new Repository())->disconnect();

        $this->render('admin/user/dashboard', [
            'email' => $userData['email'],
            'law' => $userData['law'],
            'session'=> (new Session())
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
            'law' => $userData['law'] ?? null 
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
     * Delete user
     */
    public function delete()
    {   
        $session = new UserSession();
        $userRepo = new UserRepository(); 
        $session->ifNotConnected();
        $session->ifAdmin();
        
        $idUser = $session->get('_userStart')['id'];
        $user  = $userRepo->findOneBy('user','id', intval($idUser));
        (new UserDelete( $user ));
        $userRepo->disconnect();

    }
}