<?php

namespace App\Lib\Session;

use App\Entity\User;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;

class UserSession extends Session 
{
    /**
     * create Session thank $data provided and insert 
     * that in a '_userStart' SESSION
     */
    public function create(array $data) : void
    {   
        $_SESSION['_userStart'] = [
            'id'=> $data['id'],
            'law' => $data['law']
        ] ;
    }

    /**
     * If user not connected return him to signin page 
     */
    public function ifNotConnected() : void
    {
        if( !isset($_SESSION['_userStart']) )
        {
            header('Location:/public/shop/accueil');
            die();
        }
    }  

    /**
     * If user not Admin return him to home page 
     */
    public function ifNotAdmin() : void
    {   
        if( isset($_SESSION['_userStart']) && intval($_SESSION['_userStart']['law']) < 65535)
        {  
            $this->set('user', 'error', (new inputError())::basicError());
            header('Location:/public/shop/accueil');
            die();
        }
    }
    
    /**
     * If user not contributor return him to home page 
     */
    public function ifNotContributor() : void
    {   
        if( isset($_SESSION['_userStart']) && intval($_SESSION['_userStart']['law']) < 100)
        {  
            $this->set('user', 'error', (new inputError())::basicError()) ;
            header('Location:/public/shop/accueil');
            die();
        }
    }
    
    /**
     * If user connected return him to dashboard page 
     */
    public function ifConnected() : void
    {
        if( isset($_SESSION['_userStart']['id']) )
        {
            header('Location:/public/user/dashboard');
            die();
        }
    }   

    /**
     * If user not Admin return him to home page 
     */
    public function ifAdmin() : void
    {   
        if( isset($_SESSION['_userStart']) && intval($_SESSION['_userStart']['law']) === 65535)
        {  
            $this->set('user', 'error', (new inputError())::basicError());
            header('Location:/public/user/edit');
            die();
        }
    }
}