<?php
namespace App\Repository;

use App\Entity\File;

class FileRepository extends Repository
{
    public function create(File $file)
    {
        $sql = "INSERT INTO img (img_name, img_path)
                VALUES (:img_name, :img_path)
        ";

        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            
            ':img_name' => $file->getName(),
            ':img_path' => $file->getPath(),
        ]);
    }

    public function findImageProduct($productId)
    {   
        $sql = "SELECT * FROM img 
                WHERE img_id = :product_id
            ";

        $query = self::$pdo->prepare($sql);

        $query->execute([
            'product_id' => $productId
        ]);

        return $query->fetch();
    }
}