<?php
namespace App\Service\User;

use App\Entity\User;
use App\Lib\input\Input;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\UserRepository;
use App\AbstractClass\AbstractController;



/**
 * Create user
 */
class UserUpdatePassword extends User
{
   /**
     * @param string $userData password hashed
     */
    public function updatePassword(array $userData, array $post, AbstractController $controller) : void
    {   
        $session = new Session();

        $pass = self::PASSWORD_FIELD_NAME ;
        $newPass = self::NEW_PASSWORD_FIELD_NAME ;
        $passConfirm = self::PASSWORD_CONFIRM_FIELD_NAME ;

        $password = isset($post[$pass]) && !empty($post[$pass]);
        $newPassword = isset($post[$newPass]) && !empty($post[$newPass]);
        $passwordConfirm = isset($post[$passConfirm]) && !empty($post[$passConfirm]);

        if(!$password || !$newPassword || !$passwordConfirm )
        {
            return;
        }

        if(!password_verify($post[$pass], $userData[$pass]))
        {
            $session->set('user','error', (new InputError())::password());
            $controller->redirectTo('user/edit');
            die();
        }

        if( !(new Input())->password($post[$pass]) )
        {
            $session->set('user','error', (new InputError())::newPassword());
            $controller->redirectTo('user/edit');
            die();
        }

        if($post[$newPass] !== $post[$passConfirm])
        {
            $session->set('user','error', (new InputError())::passwordNotSame("nouveaux mot de passe","confirmer nouveau mot de passe"));
            $controller->redirectTo('user/edit');
            die();
        }

        $this->setPassword($post[$newPass]);
        
        $userRepo = new UserRepository();
      
        $userRepo->updatePassword($this , $userData['id']) 
            ? $session->set('user','success', 'Mot de passe modifié')
            : $session->set('user','error', (new InputError())::basicError())
        ;

        $controller->redirectTo('user/edit');
        die();
    }
}