<?php

namespace App\Model;

use App\Core\Database as DB;

class Order extends AbstractModel
{
    protected static $tableName = 'orders';

    public static function productsByOrder()
    {
        $sql = "
             SELECT p.id, p.image, p.name, p.description, p.price, pc.amount, c.id AS cartId
             FROM product p
             INNER JOIN product_cart pc ON p.id = pc.productId
             INNER JOIN cart c ON pc.cartId = c.id
             INNER JOIN orders o ON c.id = o.cart
             WHERE o.cart = pc.cartId
        ";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([ 'userId' => $_SESSION['user']->id ]);

        return $con->fetchAll();
    }
}