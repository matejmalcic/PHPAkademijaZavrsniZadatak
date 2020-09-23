<?php

namespace App\Model;

use App\Core\Database as DB;

class Product  extends AbstractModel
{
    protected static $tableName = 'product';

    public static function getProductByMainCategory(string $value)
    {
        $sql = "SELECT * FROM product WHERE category = :value";
        $statement = DB::getInstance()->prepare($sql);
        $statement->bindValue('value', $value);
        $statement->execute();
        return $statement->fetchAll();
    }

}