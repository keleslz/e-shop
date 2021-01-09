<?php
namespace App\Lib\Session;

use App\AbstractClass\AbstractController;
use App\Entity\User;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;

//TODO Creer un service UserSessionRestriction a terme
class UserSession extends Session 
{   
    const SESSION_USER_START = '_userStart';
    const CREATOR_LAW_LEVEL = 65535;
    const CONTRIBUTOR_SUPERIOR_LAW_LEVEL = 1000;
    const SIMPLE_CONTRIBUTOR_LAW_LEVEL = 100;
    const CLIENT_LAW_LEVEL= 1;

    /**
     * If user not connected return him to signin page 
     */
    public function ifNotConnected() : void
    {
        if( !isset($_SESSION[ self::SESSION_USER_START ]) )
        {
            header('Location:/public/shop/home');
            die();
        }
    }  
    
    /**
     * If is Creator return him to home page 
     */
    public function ifCreator() : void
    {   
        if( isset($_SESSION[ self::SESSION_USER_START ]) && intval($_SESSION[ self::SESSION_USER_START ]['law']) === self::CREATOR_LAW_LEVEL )
        {  
            $this->set('user', 'error', (new inputError())::basicError());
            header('Location:/public/user/edit');
            die();
        }
    }

    /**
     * If simple contributor access denied
     */
    public function ifSimpleContributor() : void
    {   
        if( isset($_SESSION[ self::SESSION_USER_START ]) && intval($_SESSION[ self::SESSION_USER_START ]['law']) < self::CONTRIBUTOR_SUPERIOR_LAW_LEVEL )
        {  
            $this->set('user', 'error', (new inputError())::accesDenied());
            header('Location:/public/user/dashboard');
            die();
        }
    }
    
    /**
     * If is client return him to home page because admin acces denied 
     */
    public function isClient() : void
    {   
        if( isset($_SESSION[ self::SESSION_USER_START ]) && intval($_SESSION[ self::SESSION_USER_START ]['law']) < self::SIMPLE_CONTRIBUTOR_LAW_LEVEL)
        {  
            $this->set('user', 'error', (new inputError())::accesDenied()) ;
            header('Location:/public/shop/home');
            die();
        }
    }

    /**
     * If user connected return him to dashboard page 
     */
    public function ifConnected() : void
    {
        if( isset($_SESSION[ self::SESSION_USER_START ]['id']) )
        {
            header('Location:/public/user/dashboard');
            die();
        }
    }   

    /**
     * If user exist start a session
     * @param array|false $user
     */
    public function start(array $user, AbstractController $controller )
    {
        if( intval($user['law']) === self::CREATOR_LAW_LEVEL  ) 
        {
            $this->create($user);
            $this->set('user','success', 'Vous êtes connecté');
            $controller->redirectTo('user/dashboard');
        
        }else {
            $this->create($user);
            $this->set('user','success', 'Vous êtes connecté');
            $controller->redirectTo('shop/home');
        }
    }

    /**
     * create Session thank $data provided and insert 
     * that in a  self::SESSION_USER_START  SESSION
     */
    private function create(array $data) : void
    {   
        $_SESSION[ self::SESSION_USER_START ] = [
            'id'=> $data['id'],
            'law' => $data['law']
        ] ;
    }
}