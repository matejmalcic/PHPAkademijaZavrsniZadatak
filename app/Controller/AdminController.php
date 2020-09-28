<?php

namespace App\Controller;

use App\Model\User;


class AdminController extends MainController
{
    private $viewDirUser = 'crud' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
    //private $viewDirCategory = 'crud' . DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR;

    public function __construct()
    {
        parent::__construct();
        if ($_SESSION['user']->status !== 'Admin') {
            $authentic = new UserController();
            $authentic->logoutAction();
            exit;
        }
    }

    public function userCrudAction()
    {
        $data = User::getAll();

        return $this->view->render($this->viewDirUser . 'users',[
            'data' => $data
        ]);
    }

    public function editUserAction()
    {
        $user = User::getOne('id', $_GET['id']);

        if(!$user){
            $this->userCrudAction();
            exit;
        }

        $this->view->render($this->viewDirUser . 'editUser', [
            'user' => $user
        ]);
    }

    public function submitEditUserAction()
    {
        if($_POST['password'] != ''){
            if($_POST['password'] !== $_POST['confirm_password']){
                //imap_alerts();
                header('Location: /admin/editUser?id='. $_GET['id']);
            }
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        } else {
            unset($_POST['password']);
        }

        unset($_POST['confirm_password']);

        User::update($_POST, 'id', $_GET['id']);
        return $this->userCrudAction();
    }

    public function addNewUserAction()
    {
        return $this->view->render($this->viewDirUser . 'addNew');
    }

    public function submitAddNewUserAction()
    {
        $u = new UserController();
        $u->registerSubmitAction();
    }

    public function deleteUserAction()
    {
        User::delete('id', $_GET['id']);

        return $this->userCrudAction();
    }
}