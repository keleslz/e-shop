<?php

namespace App\Repository;

use \PDO;
use Exception;

class Repository
{   
    const HOST = 'localhost:3308';
    const DB_NAME = 'e_shop';
    const USERNAME = "root";
    const PASSWORD = "";

    protected static $pdo;

    public function __construct()
    {   
        self::$pdo = $this->connection();
    }   

    /**
     * Database connection
     */
    protected function connection ()
    {
       try {

            if(self::$pdo !== null)
            {
                return self::$pdo;
            }

            $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DB_NAME;
            $pdo = new PDO($dsn, self::USERNAME, self::PASSWORD, [PDO::FETCH_OBJ]);

            return $pdo;
            
        } catch (\Throwable $th) {
            
            if( !($_SERVER['REQUEST_URI'] === '/user/signin' || $_SERVER['REQUEST_URI'] === '/user/signup') )
            {
                include_once ROOT . DS . 'templates/error/error.html.php';
                header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
            }
            die();
        }
    }
    
    /**
     * find one by field occurence
     * @param string $tableName table target
     * @param string $field target field in table
     * @param mixed $value target field in table
     * @return false|array
     */
    public function findOneBy (string $tableName, string $field , $value) 
    {
        $sql =" SELECT * FROM $tableName WHERE $field = :$field";

        $query = self::$pdo->prepare($sql);

        $query->execute([
            ":$field" => $value
        ]);

        return $query->fetch();
    }

    /**
     * find all by field occurence
     * @param string $tableName table target
     * @param string $field target field in table
     * @param mixed $value target field in table
     * @return false|array
     */
    public function findAllBy (string $tableName, string $field , $value) 
    {
        $sql =" SELECT * FROM $tableName WHERE $field = :$field";

        $query = self::$pdo->prepare($sql);

        $query->execute([
            ":$field" => $value
        ]);

        return $query->fetchAll();
    }

    /**
     * @param int $option PDO::FETCH_ASSOC, FETCH_BOUND, FETCH_CLASS,
     * FETCH_INTO, FETCH_NAMED , FETCH_NUM , FETCH_OBJ , FETCH_PROPS_LATE 
     */
    public function findAll(string $tableName, int $fetchStyle = 0)  : array
    {
        $sql ="SELECT * FROM $tableName";

        $query = self::$pdo->query($sql);
        
        return $fetchStyle === 0 ? $query->fetchAll() : $query->fetchAll($fetchStyle); ;
    }

    /**
     * delete all by one field occurence
     * @param mixed $value
     */
    public function delete( string $tableName, string $field, int $id) : bool
    {
        $sql = "DELETE FROM $tableName WHERE $field = :$field";
        
        $query = self::$pdo->prepare($sql);

        return $query->execute([
            ":$field" => $id
        ]);
    }

    /**
     * delete all by one field occurence
     * @param mixed $value
     */
    public function deleteAll( string $tableName, string $field, int $id) : void
    {
        $sql = "DELETE FROM $tableName WHERE $field = :$field";
        
        $query = self::$pdo->prepare($sql);

        $query->execute([
            ":$field" => $id
        ]);
    }

    /**
     * Pdo
     */
    public function disconnect()
    {
        self::$pdo = null;
    }
}