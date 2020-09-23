<?php

declare(strict_types=1);

namespace App\Model;

class User extends AbstractModel
{
    protected static $tableName = 'user';

    public function getPassword(): string
    {
        return $this->__get('password');
    }
}