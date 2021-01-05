<?php
namespace App\Repository;

use PDO;
use App\Entity\Order;
use App\Repository\Repository;

class OrderRepository extends Repository
{   
    const ORDER_ACCEPT = 1;
    const ORDER_REJECTED = -1;
    const ORDER_NO_VALIDATED = 0;

    public function create(Order $order) : bool
    {
        $sql = "INSERT INTO `order` (order_name, order_surname, order_email, order_address, order_zip, order_city, order_department, order_article, order_total_price, id_user)
                VALUES (:order_name, :order_surname, :order_email, :order_address, :order_zip, :order_city, :order_department, :order_article, :order_total_price, :id_user);
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
            'order_article' => (string) $order->getArticle(),
            'order_total_price' => $order->getTotal(),
            'id_user' => $order->getUserId()
            ]);
            
        return $exec;
    }

    /**
     * Find all order validated
     */
    public function findAllValidated() : array
    {   
        $orders = [];

        $sql = "SELECT order_id, order_name, order_surname, order_email, order_address,
                    order_zip, order_city, order_department, order_created_at, order_article, order_total_price
                FROM `order`
                WHERE order_state = :state"
            ;

        $query = self::$pdo->prepare($sql);

        $query->execute([ 'state' => self::ORDER_ACCEPT]);
        
        foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $order )
        {
            $orders[] = (new Order())
            ->setId(intval($order['order_id']))
            ->setName($order['order_name'])
            ->setSurname($order['order_surname'])
            ->setEmail($order['order_email'])
            ->setAddress($order['order_address'])
            ->setZip($order['order_zip'])
            ->setCity($order['order_city'])
            ->setDepartment($order['order_department'])
            ->setCreatedAt($order['order_created_at'])
            ->setArticle($order['order_article'])
            ->setTotal($order['order_total_price'])
            ;
        }
        return $orders;
    }

    
    /**
     * Find all order reject
     */
    public function findAllRejected() : array
    {
        $orders = [];

        $sql = "SELECT order_id, order_name, order_surname, order_email, order_address,
                    order_zip, order_city, order_department, order_created_at, order_article, order_total_price
                FROM `order`
                WHERE order_state = :state"
            ;

        $query = self::$pdo->prepare($sql);

        $query->execute(['state' => self::ORDER_REJECTED]);

        foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $order )
        {
            $orders[] = (new Order())
                ->setId(intval($order['order_id']))
                ->setName($order['order_name'])
                ->setSurname($order['order_surname'])
                ->setEmail($order['order_email'])
                ->setAddress($order['order_address'])
                ->setZip($order['order_zip'])
                ->setCity($order['order_city'])
                ->setDepartment($order['order_department'])
                ->setCreatedAt($order['order_created_at'])
                ->setArticle($order['order_article'])
                ->setTotal($order['order_total_price'])
            ;
        }

        return $orders;
    }

    /**
     * Find all order no still validated
     */
    public function findAllNoValidated() : array
    {
        $orders = [];

        $sql = "SELECT order_id, order_name, order_surname, order_email, order_address,
                    order_zip, order_city, order_department, order_created_at, order_article, order_total_price
                FROM `order`
                WHERE order_state = :state"
            ;

        $query = self::$pdo->prepare($sql);

        $query->execute(['state' => self::ORDER_NO_VALIDATED]);
        
        foreach( $query->fetchAll(PDO::FETCH_ASSOC) as $order )
        {
            $orders[] = (new Order())
                ->setId(intval($order['order_id']))
                ->setName($order['order_name'])
                ->setSurname($order['order_surname'])
                ->setEmail($order['order_email'])
                ->setAddress($order['order_address'])
                ->setZip($order['order_zip'])
                ->setCity($order['order_city'])
                ->setDepartment($order['order_department'])
                ->setCreatedAt($order['order_created_at'])
                ->setArticle($order['order_article'])
                ->setTotal($order['order_total_price'])
            ;
        }
        return $orders;
    }
}