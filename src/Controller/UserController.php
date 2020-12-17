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
use App\Services\User\UserAuthentification;

class UserController extends AbstractController
{
    
    public function dashboard()
    {
        (new UserSession())->ifNotConnected();
        $userSession = $_SESSION['_userStart'];

        $userData = (new UserRepository())->findOneBy('user','id', $userSession['id']);

        $this->render('admin/user/dashboard', [
            'email' => $userData['email'],
            'law' => $userData['law'],
            'session'=> (new Session())
        ]);

        (new Repository())->disconnect();
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
            
            if(in_array( false, $data))
            {      
                return;
            }

            (new UserAuthentification())->checkAuthentification($_POST['email'], $_POST['password'], $this);
        }

        $this->render('admin/user/sign-in', [
            'error' => $error,
            'session' => $userSession
        ]);
        
        (new Repository())->disconnect();
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
        
        $this->render('admin/user/sign-up', [
            'state' => $state,
            'error' => $error,
            'session' => (new Session())
        ]);

        (new Repository())->disconnect();
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
            // $user = new User();
            $post = $input->cleaner($_POST);
            
            if(!$input->password($post['password'])) 
            {
                $session->set('user','error', (new InputError())::password());
                $this->redirectTo('user/edit');
            };

            (new UserUpdateEmail())->update($userData, $post, $this);
            // $user->updatePassword($userData, $post, $this);
            (new UserUpdatePassword())->updatePassword($userData, $post, $this);

        }

        $this->render('admin/user/edition', [
            'session' => (new Session()),
            'email' => $userData['email'],
            'law' => $userData['law'] ?? null 
        ]);
        
        // (new Repository())->disconnect();
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
        $this->redirectTo('user/signin');
    }

    public function delete()
    {   
        $session = new UserSession();
        $userRepo = new UserRepository(); 
        $session->ifNotConnected();
        $session->ifAdmin();

        $idUser = $session->get('_userStart')['id'];
        $user  = $userRepo->findOneBy('user','id', intval($idUser));

        if(!$userRepo)
        {
            $session->set('user','error','Désolé une erreur est survenue');
            $this->redirectTo('/shop/home');
            die();
        }

        if($userRepo->delete('user','id', intval($idUser)))
        {   
            session_destroy();
            session_unset();
            (new Repository())->disconnect();
            (new Session())->set('user','success', 'Votre compte a bien été supprimé');
            $this->redirectTo('user/signup');
        }else{
            $session->set('user','success', 'Une erreur est survenue nous vous prions de réessayer ultèrieurement');
            (new Repository())->disconnect();
            $this->redirectTo('shop/home');
        }
    }
}