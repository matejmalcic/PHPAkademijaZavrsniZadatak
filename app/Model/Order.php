<?php

namespace App\Model;

use App\Core\Database as DB;

class Order extends AbstractModel
{
    protected static $tableName = 'orders';

    public static function productsByOrder()
    {
        $sql = " 
             SELECT p.image, p.name, p.description, p.price, pc.amount, c.price AS cartPrice, c.id as cartId
             FROM product p 
             INNER JOIN product_cart pc on p.id = pc.productId
             INNER JOIN cart c on pc.cartId = c.id 
             INNER JOIN user u on c.userId = u.id 
             WHERE u.id = :userId
        ";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([ 'userId' => $_SESSION['user_id'] ]);

        return $con->fetchAll();
    }

}