<?php

namespace App\Model;

use App\Core\Database as DB;

class Order extends AbstractModel
{
    protected static $tableName = 'orders';

    public static function productsByOrder()
    {
        $sql = " 
             SELECT p.id, p.image, p.name, p.description, p.price, pc.amount, c.price AS cartPrice, c.id as cartId
             FROM product p 
             INNER JOIN product_cart pc on p.id = pc.productId
             INNER JOIN cart c on pc.cartId = c.id 
             INNER JOIN user u on c.userId = u.id 
             WHERE u.id = :userId
        ";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([ 'userId' => $_SESSION['user']->id ]);

        return $con->fetchAll();
    }

    public static function getOrderData()
    {
        $sql = " 
             SELECT o.id, o.time, c.price, o.status, o.cart
             FROM orders o 
             INNER JOIN cart c on o.cart = c.id 
             INNER JOIN user u on o.user = u.id 
             WHERE u.id = :userId
        ";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([ 'userId' => $_SESSION['user']->id ]);

        $models = [];
        while ($row = $con->fetch()) {
            $models[] = static::createObject($row);
        }

        return $models;
    }

}