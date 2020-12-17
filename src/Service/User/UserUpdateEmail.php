<?php

namespace App\Service\User;

use App\Entity\User;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\UserRepository;
use App\AbstractClass\AbstractController;
use App\Lib\input\Input;
use App\Lib\Input\InputValidator;

/**
 * Create user
 */
class UserUpdateEmail extends User
{
   const REDIRECT_ADDRRESS = '/public/user/edit';

   public function update(array $userData, array $post): void
   {
      $session = new Session();

      $currentMail = $userData['email'];
      $mail = $post['email'];

      $this->checkFormat($mail);
      $this->checkPassword($post['password'], $userData['password']);
      $this->mailAreSame($currentMail, $mail);
      $this->mailUnavailable( (new UserRepository())->findOneBy('user', 'email', $mail)['email'] ?? null );
      
      $this->setEmail($mail);

      (new UserRepository())->updateEmail($this, $userData['id'])
            ? $session->set('user', 'success', 'Votre Email a bien été modifié')
            : $session->set('user', 'error', (new InputError())::basicError())
      ;

      header('Location:' . self::REDIRECT_ADDRRESS);
      die();
   }


   /**
    * Check if not empty, if has good format
    *  and isEmpty() funciton will call the InputError class
    * @param string $mail sent by user
    */
   private function checkFormat(string $mail) : void
   {
      $input = new Input();
      $array = ['email' => $mail];
      $empty = !empty($input->isEmpty($array)) ;
      $badFormat = !$input->email($mail);

      if( $empty || $badFormat )
      {
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }

   /**
    * Check if password is the good
    * @param string $password sent by user
    * @param string $passwordHashed provided by database
    */
   private function checkPassword(string $password, string $passwordHashed): void
   {
      if (!password_verify($password, $passwordHashed)) {
         (new Session())->set('user', 'error', (new InputError())::password());
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }

   /**
    * If old and new address mail are same 
    * @param string $currentMail mail provided by database
    * @param string $mail sent by user
    */
   private function mailAreSame(string $currentMail, string $mail)
   {
      if ($currentMail === $mail) 
      {
         (new Session())->set('user', 'info', 'Aucune modfication effectuée');
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }

   /**
    * If another user have already the new mail address
    * @param string|false $mailExist array else false if not exist
    * @return void
    */
   private function mailUnavailable( $mailExist) : void
   {  
      if (!is_null($mailExist)) 
      {
         (new Session())->set('user', 'error', 'Désolé cet email est déjà utilisé');
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }
}
