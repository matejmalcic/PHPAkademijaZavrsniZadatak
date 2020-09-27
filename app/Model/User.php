<?php

declare(strict_types=1);

namespace App\Model;

use App\Core\Database as DB;

class User extends AbstractModel
{
    protected static $tableName = 'user';

    public function getPassword(): string
    {
        return $this->__get('password');
    }

    public static function setSessionId()
    {
        $sessionId = session_id();
        $sql = "UPDATE user SET sessionId = :sessionId WHERE id = :userId";

        $con = DB::getInstance()->prepare($sql);
        $con->execute([
            'sessionId' => $sessionId,
            'userId' => $_SESSION['user_id']
        ]);
    }
}