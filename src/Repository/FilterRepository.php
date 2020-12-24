<?php 
namespace App\Repository;

use PDO;
use App\Repository\Repository;

class FilterRepository  extends Repository
{
    /**
     * Order descendant or Ascendant by filter
     */
    public function findAllByFilter(string $field, string $asc = 'asc') : array 
    {   
        $asc === 'asc' 
            ? $sql ="SELECT * FROM product ORDER BY :product_price ASC"
            : $sql ="SELECT * FROM product ORDER BY :product_price DESC"
        ;

        $query = self::$pdo->prepare($sql);

        $query->execute([
            ':product_price' => $field
        ]);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}