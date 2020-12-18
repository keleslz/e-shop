<?php

namespace App\Service\User;

use App\Entity\User;
use App\Lib\Session\Session;
use App\Lib\Input\InputError;
use App\Repository\UserRepository;
use App\Lib\input\Input;

/**
 * Uupdate user email
 */
class UserUpdateEmail extends User
{
   /**
    * Update user email if " NEW_PASSWORD_FIELD_NAME " is empty,  must called before UserUpdateEmail::class
    * @param array $userData an array with user info provided by database
    * @param array $post an array with info sent by user
    */
   public function __construct(array $userData , array $post)
   {
      /*
       *  Warn UserUpdatePassword::class that this class was called before 
       *   and don't throw exception
       */
      self::$classCalled = get_class($this);

      $isNotUpdateEmail = isset($post[ self::NEW_PASSWORD_FIELD_NAME ]) && !empty($post[ self::NEW_PASSWORD_FIELD_NAME ]);

      if( $isNotUpdateEmail )
      {
         return;
      };

      $this->userDataId = intval($userData[ self::ID_TABLE_FIELD_NAME]);
      $this->currentMail = $userData[ self::EMAIL_TABLE_FIELD_NAME ];
      $this->postMail = $post[ self::EMAIL_FIELD_NAME ];
      $this->currentPassword = $userData[ self::PASSWORD_TABLE_FIELD_NAME ];
      $this->postPassword = $post[ self::PASSWORD_FIELD_NAME ];

      $this->update();
   }

   /**
    * Start update
    */
   public function update(): void
   {
      $session = new Session();
      $this->error();
      $this->setEmail($this->postMail);

      (new UserRepository())->updateEmail($this, $this->userDataId )
            ? $session->set('user', 'success', 'Votre Email a bien été modifié')
            : $session->set('user', 'error', (new InputError())::basicError())
      ;

      header('Location:' . self::REDIRECT_ADDRRESS);
      die();
   }

   /**
    * Redirect with message if error detected
    */
   public function error()
   {
      $this->checkFormat();
      $this->checkPassword();
      $this->mailAreSame();
      $mail = (new UserRepository())->findOneBy(self::TABLE_NAME, self::EMAIL_TABLE_FIELD_NAME, $this->postMail)[ self::EMAIL_FIELD_NAME] ?? null ;
      $this->mailUnavailable( $mail );
   }

   /**
    * Check if not empty, if has good format
    *  and isEmpty() funciton will call the InputError class
    */
   private function checkFormat() : void
   {
      $input = new Input();
      $array = ['email' => $this->postMail];
      $empty = !empty($input->isEmpty($array)) ;
      $badFormat = !$input->email($this->postMail);

      if( $empty || $badFormat )
      {
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }

   /**
    * Check if password is the good
    */
   private function checkPassword(): void
   {
      if (!password_verify($this->postPassword, $this->currentPassword )) {
         (new Session())->set('user', 'error', (new InputError())::password());
         header('Location:' . self::REDIRECT_ADDRRESS);
         die();
      }
   }

   /**
    * If old and new address mail are same 
    */
   private function mailAreSame() : void
   {
      if ($this->currentMail === $this->postMail) 
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
