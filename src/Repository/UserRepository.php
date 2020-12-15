<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Repository;

class UserRepository extends Repository
{
    /**
     * Create a user
     */
    public function create(User $user) : bool
    {   
        $sql = "
            INSERT INTO user (email, password, law)
            VALUES (:email, :password, :law)
        ";
        
        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'law' => $user->getLevelLaw(),
        ]);
    }

    /**
     * update email user
     */
    public function updateEmail(User $user, $id) : bool
    {   
        $sql = "UPDATE  user 
                SET     email = :email
                WHERE   id = :id
                "
            ;

        $query = self::$pdo->prepare($sql);

        return $query->execute([
            'email' => $user->getEmail(),
            'id' => $id,
        ]);
    }
    
    /**
     * update password user
     */
    public function updatePassword(User $user, $id) : bool
    {   
        $sql = "UPDATE  user 
                SET     password = :password
                WHERE   id = :id
                "
            ;

        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            'password' => $user->getPassword(),
            'id' => $id,
        ]);; 
    }

}