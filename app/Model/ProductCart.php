<?php

namespace App\Model;

use App\Core\Database as DB;

class ProductCart extends AbstractModel
{
    protected static $tableName = 'product_cart';

    public static function getProducts($cartId)
    {
        $sql = " 
             SELECT p.id, p.image, p.name, p.description, p.price, pc.amount 
             FROM product p INNER JOIN product_cart pc on p.id = pc.productid
             WHERE pc.cartId = :cartId
        ";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([ 'cartId' => $cartId ]);

        $models = [];
        while ($row = $con->fetch()) {
            $models[] = static::createObject($row);
        }

        return $models;
        //return $con->fetchAll();
    }

    public static function removeProduct($cartId, $productId)
    {
        $sql = "DELETE FROM product_cart WHERE cartId =:cartId  AND productId =:productId";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([
            'cartId' => $cartId,
            'productId' => $productId
        ]);
    }
}