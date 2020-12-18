<?php
namespace App\Service\User;

use App\Entity\User;
use App\Lib\input\Input;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\UserRepository;
use App\Service\User\UserUpdateEmail;
use ErrorException;
use Exception;

/**
 * @class Update user user must called after UserUpdateEmail::class
 */
class UserUpdatePassword extends User
{
    public function __construct(array $userData, array $post)
    {
        $this->classNotcalledException();

        $isUpdatePassword = isset($post[ self::NEW_PASSWORD_FIELD_NAME ]) && !empty($post[ self::NEW_PASSWORD_FIELD_NAME ]);
      
        if( !$isUpdatePassword )
        {
           return;
        };

        $this->userDataId = intval($userData[ self::ID_TABLE_FIELD_NAME ]);
        $this->currentPassword = $userData[ self::PASSWORD_TABLE_FIELD_NAME ];
        $this->postPassword = $post[ self::PASSWORD_FIELD_NAME ];
        $this->newPassword = $post[ self::NEW_PASSWORD_FIELD_NAME ];
        $this->passwordConfirm = $post[ self::PASSWORD_CONFIRM_FIELD_NAME ];
        
        $this->updatePassword();
    }

    /**
     * @param string $userData password hashed
     */
    public function updatePassword() : void
    {   
        $password = isset($this->postPassword) && !empty($this->postPassword);
        $newPassword = isset($this->newPassword) && !empty($this->newPassword);
        $passwordConfirm = isset($this->passwordConfirm) && !empty($this->passwordConfirm);

        if(!$password || !$newPassword || !$passwordConfirm )
        {
            return;
        }

        $this->error();
        $this->update();

        header('Location:' . self::REDIRECT_ADDRRESS);
        die();
    }

    /**
     * Check if error exist
     */
    private function error()
    {
        $this->checkPassword();
        $this->checkPasswordFormat();
        $this->ifPasswordsAreDifferent();
    }
    
    /**
    * Check if password is the good
    */
    private function checkPassword() : void
    {
        if(!password_verify($this->postPassword, $this->currentPassword))
        {
            (new Session())->set('user','error', (new InputError())::password());
            header('Location:' . self::REDIRECT_ADDRRESS);
            die();
        }
    }

    /**
    * Check if password has good format
    */
    private function checkPasswordFormat() : void
    {
        if( !(new Input())->password($this->postPassword) )
        {
            (new Session())->set('user','error', (new InputError())::newPassword());
            header('Location:' . self::REDIRECT_ADDRRESS);
            die();
        }
    }

    /**
     * Chekck if pasword are different
     */
    private function ifPasswordsAreDifferent() : void
    {
        if($this->newPassword !== $this->passwordConfirm)
        {
            (new Session())->set('user','error', (new InputError())::passwordNotSame("nouveaux mot de passe","confirmer nouveau mot de passe"));
            header('Location:' . self::REDIRECT_ADDRRESS);
            die();
        }
    }

    /**
     * update User
     */
    private function update() : void
    {
        $this->setPassword( $this->newPassword );

        (new UserRepository())->updatePassword($this ,  $this->userDataId) 
            ? (new Session())->set('user','success', 'Mot de passe modifié')
            : (new Session())->set('user','error', (new InputError())::basicError())
        ;
    }
    
    /**
     * trow an Exception if UserUpadateEmail wasn't called before this class
     */
    private function classNotcalledException()
    {   
        $classCalledBefore = UserUpdateEmail::class;

        if($classCalledBefore !== self::$classCalled)
        {   
            try{
                throw new ErrorException($classCalledBefore . 
                ' was absolutely called before ' . get_class($this), 14_500 );
            }catch(ErrorException $e)
            {
                echo "<h1>Erreur {$e->getCode()} : {$e->getMessage()}";
                die();
            }
        }
    }
}