<?php

function dd($mixed)
{   
    if(is_array($mixed) || is_object($mixed))
    {
        echo '$key ===> $value';
        
        foreach ($mixed as $key => $value) {
    
            echo '<pre> key[' . $key  . '] ===> value[' . $value . '] ===> type[' . gettype($value) . ']</pre>';
        }

        return;
    }

    if(is_string($mixed) || is_numeric($mixed))
    {
        echo '<pre>value[' . $mixed . '] ===> type[' . gettype($mixed) . ']</pre>';
    }
}

function fillProduct($x)
{
    $number = 0;

    for ($i=1; $i < $number ; $i++) 
    { 
        $sql = "INSERT INTO product (product_name, product_description, product_slug, product_price, product_status, id_img, id_category )
                VALUES (:product_name, :product_description, :product_slug, :product_price, :product_status, :id_img, :id_category )
            ";

        $query = $x->pdo->prepare($sql);
        
        return $query->execute([
            'product_name' => "Produit-$i",
            'product_description' => "Une description pour le Produit-$i",
            'product_slug' => "Une-description-pour-le-Produit-$i",
            'product_price' => "$i",
            'product_status' => $i < ($number/2) ? 1 : -1 ,
            'id_category' => $i,
            'id_img' => $i,
        ]);
    }
}


