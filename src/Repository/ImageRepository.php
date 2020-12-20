<?php
namespace App\Repository;

use App\Entity\Image;
use App\Repository\FileRepository;

class ImageRepository extends FileRepository
{
    public function createImage(Image $image, int $idProduct)
    {
        $sql = "INSERT INTO img (img_name, img_path, id_product)
                VALUES (:img_name, :img_path, :id_product)
        ";

        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            
            ':img_name' => $image->getName(),
            ':img_path' => $image->getPath(),
            ':id_product' => $idProduct,
        ]);
    }

    public function findAllbyIdProduct($productId)
    {
        $sql = "SELECT img_id, img_name, img_path FROM img WHERE id_product = :id_product";

        $query = self::$pdo->prepare($sql);

        $query->execute([
            ':id_product' => $productId
        ]);

        return $query->fetchAll();

    }
}