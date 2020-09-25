<?php

namespace App\Controller;

use App\Model\User;

class UserController extends MainController
{
    public function loginAction()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->view->render('login');
        }

        header('Location: /');
    }

    public function registerAction()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->view->render('register');
        }

        header('Location: /');
    }

    public function registerSubmitAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /');
            return;
        }

        $requiredKeys = ['first_name', 'last_name', 'username', 'email', 'password', 'confirm_password'];
        if (!$this->validateData($_POST, $requiredKeys)) {
            // set error message
            header('Location: /user/register');
            return;
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            // set error message
            header('Location: /user/register');
            return;
        }

        $user = User::getOne('email', $_POST['email']);

        if ($user->getId()) {
            // user already exists
            header('Location: /user/register');
            return;
        }

        User::insert([
            'first_name' => $_POST['first_name'] ?? null,
            'last_name' => $_POST['last_name'] ?? null,
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
        ]);

        header('Location: /user/login');
    }

    public function loginSubmitAction()
    {
        // only POST requests are allowed
        if (!$this->isPost() || $this->auth->isLoggedIn()) {
            header('Location: /');
            return;
        }

        $requiredKeys = ['email', 'password'];
        if (!$this->validateData($_POST, $requiredKeys)) {
            // set error message
            header('Location: /user/login');
            return;
        }

        $user = User::getOne('email', $_POST['email']);

        if (!$user->getId() || !password_verify($_POST['password'], $user->getPassword())) {
            // set error message
            header('Location: /user/login');
            return;
        }


        $this->auth->login($user);
        header('Location: /');
    }

    protected function validateData(array $data, array $keys): bool
    {
        foreach ($keys as $key) {
            $isValueValid = isset($data[$key]) && $data[$key];
            if (!$isValueValid) {
                return false;
            }
        }
        return true;
    }

    public function logoutAction()
    {
        if ($this->auth->isLoggedIn()) {
            $this->auth->logout();
            session_regenerate_id();
            //delete cart from db
        }

        header('Location: /');
    }
}