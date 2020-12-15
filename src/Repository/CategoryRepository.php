<?php
namespace App\Repository;

use App\Entity\Category;
use App\Repository\Repository;

class CategoryRepository extends Repository
{
    /**
     * @param id|nulll $idImage null if not exist 
     */
    public function create(Category $category)
    {   
        $sql = "INSERT INTO category (category_name)
                VALUES (:category_name)
            ";

        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            'category_name' => $category->getName(),
        ]);
    }

    public function update (int $id, Category $category) : bool
    {   
        $sql = "UPDATE  category 
                SET     category_name = :category_name
                WHERE   category_id = :category_id
        ";
    
        $query = self::$pdo->prepare($sql);

        return $query->execute([
            'category_name' => $category->getName(),
            'category_id' => $id,
        ]);
    }
}