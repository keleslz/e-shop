<?php
namespace App\Repository;

use App\Entity\Order;
use App\Repository\Repository;

class OrderRepository extends Repository
{
    public function create(Order $order) : bool
    {
        $sql = "INSERT INTO `order` (order_name, order_surname, order_email, order_address, order_zip, order_city, order_department, order_article, id_user)
                VALUES (:order_name, :order_surname, :order_email, :order_address, :order_zip, :order_city, :order_department, :order_article, :id_user);
        ";
        
        $query = self::$pdo->prepare($sql);
        
        $exec = $query->execute([
            'order_name' => $order->getName(),
            'order_surname' => $order->getSurname(),
            'order_email' => $order->getEmail(),
            'order_address' => $order->getAddress(),
            'order_zip' => $order->getZip(),
            'order_city' => $order->getCity(),
            'order_department' => $order->getDepartment(),
            'order_article' => json_encode($order->getArticle()),
            'id_user' => $order->getUserId()
            ]);

        return $exec;
    }
}