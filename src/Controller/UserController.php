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
use App\AbstractClass\AbstractController;

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
        $session = new UserSession();
        $session->ifConnected();
        
        if( count($_POST) > 0 )
        {
            $data =  (new Input)->hasGoodFormat($_POST);
            $error = InputError::get($data);
            
            if(!in_array( false, $data))
            {      
                $repo = new UserRepository();
                $exist = $repo->findOneBy('user','email', $_POST['email']) ;
                $passValid = password_verify( $_POST['password'], $exist['password'] ?? false );

                if ( $exist && $passValid )
                {   
                    if( intval($exist['law']) === 65535 ) 
                    {
                        $session->create($exist);
                        $session->set('user','success', 'Vous êtes connecté');
                        $this->redirectTo('user/dashboard');
                    
                    }else {
                        $session->create($exist);
                        $session->set('user','success', 'Vous êtes connecté');
                        $this->redirectTo('shop/home');
                    }
                    
                }else
                {
                    $session->set('user','error', "Veuillez vérifier vos identifiants de connexion");
                }
            }
        }

        $this->render('admin/user/sign-in', [
            'error' => $error,
            'session' => $session
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
                $repo = new UserRepository();
                $exist = $repo->findOneBy('user', 'email', $_POST['email']) ;

                if ( $exist === false ) 
                {   
                    $user = new User();
                    $user->setEmail($_POST['email']);
                    $user->setPassword($_POST['password']);
                    $repo->create($user) 
                    ? (new Session())->set('user','success', 'Votre compte a été crée') 
                    : (new Session())->set('user','error', 'Désolé une erreur est survenue lors de votre inscription') ;
                }else{
                    (new Session())->set('user','error', 'Désolé mais ce compte existe déjà') ;

                }

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
            $user = new User();
            $post = $input->cleaner($_POST);
            
            if($input->password($post['password'])) 
            {
                $user->updateEmailOnly($userData, $post, $this);
                $user->updatePassword($userData, $post, $this);
            }else{
                $session->set('user','error', (new InputError())::password());
            }
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