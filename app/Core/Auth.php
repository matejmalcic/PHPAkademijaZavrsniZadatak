<?php

declare(strict_types=1);

namespace App\Core;

use App\Model\User;

class Auth
{
    private static $instance;
    private $currentUser;

    private function __construct()
    {
        if ($userId = $_SESSION['user']->id ?? null) {
            $user = User::getOne('id', $userId);
            $this->currentUser = $user->getId() ? $user : null;
        }
    }

    private function __clone()
    {
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function login(User $user): void
    {
        if ($user->getId()) {
            if ($user->getStatus() === 'Guest') {
                $user->setSessionId($user->getId());
            }
            $_SESSION['user'] = $user;
            $this->currentUser = $user;
        }
    }

    public function logout(): void
    {
        unset($_SESSION['user'], $this->currentUser);
    }

    public function isLoggedIn(): bool
    {
        return isset($this->currentUser);
    }

    public function getCurrentUser(): ?User
    {
        return $this->currentUser;
    }
}