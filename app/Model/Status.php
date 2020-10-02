<?php

namespace App\Model;

use App\Core\Database as DB;

class Status extends AbstractModel
{
    protected static $tableName = 'status';

    public static function getNextStatus($name)
    {
        $sql ="
            SELECT name FROM status 
            WHERE id > (SELECT id FROM status WHERE name = :name) 
            ORDER BY id LIMIT 1";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([
            'name' => $name
        ]);
        return $con->fetch();
    }
}