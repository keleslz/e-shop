<?php
namespace App\Service\User;

use App\Entity\User;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\Repository;
use App\Repository\UserRepository;

/**
 * Deleete user account
 */
class UserDelete extends User
{   
    /**
     * @param array|false $user an array or false if not exist
     */
    public function __construct($user)
    {   
        $this->user = $user;
        $this->id = intval($user[ self::ID_TABLE_FIELD_NAME ]);
        $this->law = $user[ self::LAW_TABLE_FIELD_NAME ];

        $this->delete();
    }

    public function delete()
    {
        $this->checkError();
        (new UserRepository())->delete('user','id', $this->id)
            ? $this->success()
            : $this->failure()
        ;
    }

    /**
     * If request is not a post
     */
    private function isNotPost() : void
    {
        if(!($_SERVER['REQUEST_METHOD'] === 'POST'))
        {
            (new Session())->set('user','error', InputError::basicError());
            header('Location:' . self::REDIRECT_SIGNIN);
            die();
        }
    }

    /**
     * Check if error exist
     */
    private function checkError() : void
    {
        $this->isNotPost();
        $this->notExist();
        $this->notAdmin();
    }

    /**
     * If user not exist
     */
    private function notExist() : void
    {
        if(!$this->user)
        {
            (new Session())->set('user','error','Désolé une erreur est survenue');
            header('Location:' . self::REDIRECT_HOME);
            die();
        }
    }

    /**
     * If is not admin
     */
    private function notAdmin() : void
    {
        if( intval($this->law) === self::HIGH_LAW_LEVEL)
        {
            (new Session())->set('user','error','Impossible d\'effectuer cette action');
            header('Location:' . self::REDIRECT_HOME);
            die();
        }
    }

    /**
     * If delete action's success
     */
    private function success() : void
    {
        session_destroy();
        session_unset();
        (new Repository())->disconnect();
        (new Session())->set('user','success', 'Votre compte a bien été supprimé');
        header('Location:' . self::REDIRECT_SIGNUP);
        die();

    }

    /**
     * If delete action's faile
     */
    private function failure() : void
    {
        (new Repository())->disconnect();
        (new Session())->set('user','success', 'Une erreur est survenue nous vous prions de réessayer ultèrieurement');
        header('Location:' . self::REDIRECT_HOME);
        die();
    }
}