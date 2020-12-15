<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;
use App\Lib\input\Input;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Lib\Input\InputValidator;
use App\Repository\UserRepository;
use App\AbstractClass\AbstractController;

class User 
{
    private int $id;

    private string $email;

    private string $password;

    private int $levelLaw = 1;
    
    private string $name ;

    private string $surname ;

    private string $address ;

    private string $zip ;

    private string $city ;

    private string $department ;

    private string $createdAt;

    const PASSWORD_FIELD_NAME = 'password';
    const NEW_PASSWORD_FIELD_NAME = 'newPassword';
    const PASSWORD_CONFIRM_FIELD_NAME = 'passwordConfirm';

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);

        return $this;
    }

    /**
     * Get the value of levelLaw
     */ 
    public function getLevelLaw()
    {
        return $this->levelLaw;
    }

    /**
     * Set the value of levelLaw
     *
     * @return  self
     */ 
    public function setLevelLaw(int $levelLaw)
    {
        $this->levelLaw = $levelLaw;

        return $this;
    }

    
    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of surname
     */ 
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set the value of surname
     *
     * @return  self
     */ 
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get the value of address
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @return  self
     */ 
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of zip
     */ 
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     *
     * @return  self
     */ 
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get the value of city
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @return  self
     */ 
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of department
     */ 
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set the value of department
     *
     * @return  self
     */ 
    public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        $now = new DateTime('now', new DateTimeZone('europe/paris'));

        return $this->createdAt = $now->format('Y-m-d H:m:i');
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    public function updateEmailOnly(array $userData, array $post, AbstractController $controller) : void
    {   
        $session = new Session();
        
        if(!password_verify($post['password'], $userData['password']))
        {
            $session->set('user','error', (new InputError())::password());
            $controller->redirectTo('user/edit');
            die();
        }

        if (empty($post['newPassword']) && empty($post['passwordConfirm']) )
        {   
            $currentMail = $userData['email'];
            $mail = $post['email'];

            if(isset($mail) && !empty($mail))
            {   
                $session = new Session();
                $mailExist = (new UserRepository())->findOneBy('user','email', $mail)['email'] ?? null; 

                if ( $currentMail === $mail ) {
                    
                    $session->set('user', 'info' , 'Aucune modfication effectuée');
                    $controller->redirectTo('user/edit');
                    die();
                } 

                if( $currentMail !== $mail && is_array($mailExist) )
                {
                    $session->set('user','error', 'Désolé cet email est déjà utilisé');
                    $controller->redirectTo('user/edit');
                    die();
                }

                if( $currentMail !== $mail && is_null($mailExist) )
                {   
                    $this->setEmail($mail);

                    (new UserRepository())->updateEmail($this , $userData['id']) 
                        ? $session->set('user','success', 'Votre Email a bien été modifié')
                        : $session->set('user','error', (new InputError())::basicError())
                    ;

                    $controller->redirectTo('user/edit');
                    die();
                }
            }
        }
    }

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