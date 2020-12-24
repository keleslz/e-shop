<?php
namespace App\Repository;

use PDO;
use App\Entity\Product;
use App\Repository\Repository;

class ProductRepository extends Repository
{   
    /**
     * @param id|nulll $idImage null if not exist 
     */
    public function create(Product $product, $idImage)
    {   
        $sql = "INSERT INTO product (product_name, product_description, product_slug, product_price, product_status, id_img, id_category )
                VALUES (:product_name, :product_description, :product_slug, :product_price, :product_status, :id_img, :id_category )
            ";

        $query = self::$pdo->prepare($sql);
        
        return $query->execute([
            'product_name' => $product->getName(),
            'product_description' => $product->getDescription(),
            'product_slug' => $product->getSlug(),
            'product_price' => $product->getPrice(),
            'product_status' => $product->getStatus(),
            'id_category' => $product->getIdCategory(),
            'id_img' => $idImage,
        ]);
    }

    public function findAllProductAndImage() 
    {
        $sql =" SELECT * FROM product 
                LEFT JOIN img 
                ON product.id_img = img.img_id 
                ORDER BY product.product_id ASC";

        $query = self::$pdo->query($sql);
        
        return $query->fetchAll();
    }

    public function findAllCards() 
    {
        $sql =" SELECT product_id, product_name, product_price, product_slug, product_status, img_name, id_category
                FROM product 
                LEFT JOIN img 
                ON product.id_img = img.img_id 
                ORDER BY product.product_id ASC";

        $query = self::$pdo->query($sql);
        
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function update (int $id, Product $product) : bool
    {  
        $sql = "UPDATE  product 
                SET     product_name = :product_name,
                        product_description = :product_description,
                        product_slug = :product_slug,
                        product_price = :product_price,           
                        product_status = :product_status,          
                        id_category = :id_category                
                WHERE   product_id = :product_id
        ";
    
        $query = self::$pdo->prepare($sql);

        return $query->execute([
            ':product_name' => $product->getName(),
            ':product_description' => $product->getDescription(),
            ':product_slug' => $product->getSlug(),
            ':product_price' => $product->getPrice(),         
            ':product_status' => $product->getStatus(),         
            ':id_category' => $product->getIdCategory(),                 
            ':product_id' => $id,
        ]);
    }

    public function updatePicture(int $id, Product $product, $idImg = null) : bool
    {   
        $sql = "UPDATE  product 
                SET     product_name = :product_name,
                        product_description = :product_description,
                        product_slug = :product_slug,
                        product_price = :product_price,           
                        product_status = :product_status, 
                        id_category = :id_category,               
                        id_img = :id_img
                WHERE   product_id = :product_id
        ";
    
        $query = self::$pdo->prepare($sql);

        return $query->execute([
            ':product_name' => $product->getName(),
            ':product_description' => $product->getDescription(),
            ':product_slug' => $product->getSlug(),
            ':product_price' => $product->getPrice(),         
            ':product_status' => $product->getStatus(),   
            ':id_category' => $product->getIdCategory(),                 
            ':id_img' => $idImg,
            ':product_id' => $id,
        ]);
    }
    
    public function findAllcartRepo(array $carts) : array
    {   
        foreach ($carts as $key => $cart) {
            
            $result[] = $this->findOneCard($key);
        }

        return $result ?? [];
    }

    /**
     * Find product and cover pricture associated
     */
    public function findOneCard(int $id)
    {
        $sql =" SELECT product_id, product_name, product_description, product_slug, product_price, img_name
            FROM product 
            LEFT JOIN img 
            ON product.id_img = img.img_id 
            WHERE product_id = :product_id ";

        $query = self::$pdo->prepare($sql);
        
        $query->execute([
            'product_id' => $id
        ]);
        
        return $query->fetch();
    }

    /**
     * Get one product price
     */
    public function findOnePrice(int $id)  : float
    {
        $sql =" SELECT product_price
        FROM product 
        WHERE product_id = :product_id ";

        $query = self::$pdo->prepare($sql);
        
        $query->execute([
            'product_id' => $id
        ]);

        $price = str_replace(',', '.', $query->fetch(PDO::FETCH_ASSOC)['product_price']) ?? null;
        
        return $price ?? 0;
    }
}
