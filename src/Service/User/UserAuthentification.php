<?php

namespace App\Services\User;

use App\Entity\User;
use App\Lib\Session\UserSession;
use App\Repository\UserRepository;
use App\AbstractClass\AbstractController;

/**
 * User authentification to get account access
 */
class UserAuthentification extends User
{
     /**
     * @param string $email send by user
     * @param string $password send by user
     */
    public function checkAuthentification(string $email, string $password, AbstractController $controller) : void
    {
        $userSession = new UserSession();

        $user = $this->checkAccount($email);
        $passValid = $this->checkPass($password, $user);
        $user && $passValid 
            ? $userSession->start($user, $controller)
            : $userSession->set( 'user' ,'error', "Veuillez vérifier vos identifiants de connexion")
        ;
    }

    /**
     * Check if account exist
     * @return array|false 
     */
    private function checkAccount (string $email)
    {
        return  ( new UserRepository() )->findOneBy( self::TABLE_NAME , self::EMAIL_TABLE_FIELD_NAME , $email) ;
    }

    /**
     * Check if pass is good
     * @param string $password send by user
     * @param array|false $user data provided by database
     */
    private function checkPass (string $password, $user ) : bool
    {
        return password_verify(
            $password,
            $user[ self::PASSWORD_TABLE_FIELD_NAME ] ?? false )
        ;
    }
}