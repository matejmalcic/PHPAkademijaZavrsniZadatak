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

        header('Location: /~polaznik20');
    }

    public function registerAction()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->view->render('register');
        }

        header('Location: /~polaznik20');
    }

    public function changePassAction()
    {
        if (!$this->auth->isLoggedIn()) {
            return $this->view->render('login');
        }

        return $this->view->render('changePass');
    }

    public function submitChangePassAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20');
            return;
        }

        if($_POST['password'] === ''){
            return $this->view->render('changePass', [
                'message' => 'You need to enter new password!'
            ]);
        }

        if(!password_verify($_POST['old_password'], $_SESSION['user']->password)){
            return $this->view->render('changePass', [
                'message' => 'Old password is not correct!'
            ]);
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            return $this->view->render('changePass', [
                'message' => 'Confirm password don\'t match!'
            ]);
        }

        unset($_POST['confirm_password'], $_POST['old_password']);

        $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

        User::update($_POST, 'id', $_SESSION['user']->id);
        $_SESSION['user']->password = $_POST['password'];

        return $this->view->render('changePass', [
            'message' => 'Password changed successfully!'
        ]);
    }

    public function registerSubmitAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20');
            return;
        }

        $requiredKeys = ['first_name', 'last_name', 'email', 'password', 'confirm_password'];
        if (!$this->validateData($_POST, $requiredKeys)) {
            // set error message
            header('Location: /~polaznik20/user/register');
            return;
        }

        if ($_POST['password'] !== $_POST['confirm_password']) {
            // set error message
            if($_SESSION['user']->status === 'Admin'){
                header('Location: /~polaznik20/admin/addNewUser');
                return;
            }
            header('Location: /~polaznik20/user/register');
            return;
        }

        $user = User::getOne('email', $_POST['email']);

        if ($user->getId()) {
            // user already exists
            if($_SESSION['user']->status === 'Admin'){
                header('Location: /~polaznik20/admin/addNewUser');
                return;
            }
            header('Location: /~polaznik20/user/register');
            return;
        }

        User::insert([
            'first_name' => $_POST['first_name'] ?? null,
            'last_name' => $_POST['last_name'] ?? null,
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
        ]);

        if($_SESSION['user']->status === 'Admin'){
            header('Location: /~polaznik20/admin/userCrud');
            return;
        }
        header('Location: /~polaznik20/user/login');
    }

    public function withoutLoginAction()
    {
        $user = User::getOne('email', 'noLogin@user.com');

        $this->auth->login($user);
        header('Location: /~polaznik20');
    }

    public function loginSubmitAction()
    {
        // only POST requests are allowed
        if (!$this->isPost() || $this->auth->isLoggedIn()) {
            header('Location: /~polaznik20');
            return;
        }

        $requiredKeys = ['email', 'password'];
        if (!$this->validateData($_POST, $requiredKeys)) {
            // set error message
            header('Location: /~polaznik20/user/login');
            return;
        }

        $user = User::getOne('email', $_POST['email']);

        if (!$user->getId() || !password_verify($_POST['password'], $user->getPassword())) {
            // set error message
            header('Location: /~polaznik20/user/login');
            return;
        }


        $this->auth->login($user);
        header('Location: /~polaznik20');
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
        }

        header('Location: /~polaznik20/user/login');
    }
}