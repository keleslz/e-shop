<?php
namespace App\Service\User;


use App\Entity\User;
use App\Lib\Session\Session;
use App\Repository\UserRepository;

/**
 * Create user
 */
class UserCreation extends User
{
    public function __construct(int $levelLaw = 1)
    {
        $this->levelLaw = $levelLaw;
    }
     /**
     * Create new user
     * @param string $email send by user
     * @param string $password send by user
     */
    public function new (string $email, string $password)
    {
        if ( $this->checkIfAlreadyExist($email) ) 
        {   
            $this->create($email, $password ) ? $this->success(): $this->failed();
        }else{
            $this->alreadyExist();
        }
    }

    /**
     * Check if user already exist
     * @param string $email send by user
     * @return array|false
     */
    private function checkIfAlreadyExist(string $email)
    {  
        return !is_array((new UserRepository())->findOneBy( self::TABLE_NAME, self::EMAIL_TABLE_FIELD_NAME, $email)) ;
    }
    
    /**
     * Create user
     * @param string $email send by user
     * @param string $password send by user
     */
    private function create(string $email, string $password) : bool
    {
        return (new UserRepository())->create( $this->setInfo( $email, $password)) ;
    }

    /**
     * Setting user info
     * @param string $email send by user
     * @param string $password send by user
     */
    private function setInfo(string $email, string $password) : User
    {
        $this->setLevelLaw($this->levelLaw);
        $this->setEmail($email);
        $this->setPassword($password);
        return $this;
    }

    /**
     * Alert user if creation was successful
     */
    private function success() : void
    {
        (new Session())->set('user','success', 'Votre compte a été crée');
    }

    /**
     * Alert user if creation failed
     */
    private function failed() : void
    {
        (new Session())->set('user','error', 'Désolé une erreur est survenue lors de votre inscription') ;
    }

    /**
     * Alert user if user  already exist
     */
    private function alreadyExist() : void
    {
        (new Session())->set('user','error', 'Désolé mais ce compte existe déjà') ;
    }
}