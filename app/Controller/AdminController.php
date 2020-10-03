<?php

namespace App\Controller;

use App\Model\Category;
use App\Model\Status;
use App\Model\User;


class AdminController extends MainController
{
    private $viewDirUser = 'crud' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
    private $viewDirStatus = 'crud' . DIRECTORY_SEPARATOR . 'status' . DIRECTORY_SEPARATOR;
    private $viewDirCategory = 'crud' . DIRECTORY_SEPARATOR . 'category' . DIRECTORY_SEPARATOR;

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

        return $this->view->render($this->viewDirUser . 'users', [
            'data' => $data
        ]);
    }

    public function editUserAction()
    {
        $user = User::getOne('id', $_GET['id']);

        if (!$user) {
            $this->userCrudAction();
            exit;
        }

        $this->view->render($this->viewDirUser . 'editUser', [
            'user' => $user
        ]);
    }

    public function submitEditUserAction()
    {
        if ($_POST['password'] != '') {
            if ($_POST['password'] !== $_POST['confirm_password']) {
                //imap_alerts();
                header('Location: /~polaznik20/admin/editUser?id=' . $_GET['id']);
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

    public function statusAction()
    {
        $data = Status::getAll('id');
        return $this->view->render($this->viewDirStatus . 'list', [
            'status' => $data
        ]);
    }

    public function submitStatusEditAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20/admin/status');
            return;
        }

        foreach ($_POST as $id => $name) {
            Status::update(['name' => $name], 'id', $id);
        }

        return $this->statusAction();
    }

    public function addNewStatusAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20/admin/status');
            return;
        }

        if ($_POST['name'] === '') {
            //You didnt entry status name
            header('Location: /~polaznik20/admin/status');
            return;
        }

        Status::insert($_POST);

        return $this->statusAction();
    }

    public function deleteStatusAction()
    {
        Status::delete('id', $_GET['id']);

        return $this->statusAction();
    }

    public function categoryAction()
    {
        $data = Category::getAll('id');

        return $this->view->render($this->viewDirCategory .'list', [
            'categories' => $data
        ]);
    }

    public function submitCategoryEditAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20/admin/category');
            return;
        }

        foreach ($_POST as $id => $name) {
            Category::update(['name' => $name], 'id', $id);
        }

        return $this->categoryAction();
    }

    public function addNewCategoryAction()
    {
        if (!$this->isPost()) {
            // only POST requests are allowed
            header('Location: /~polaznik20/admin/category');
            return;
        }

        if ($_POST['name'] === '') {
            //You didnt entry status name
            header('Location: /~polaznik20/admin/category');
            return;
        }

        Category::insert($_POST);

        return $this->categoryAction();
    }

    public function deleteCategoryAction()
    {
        Category::delete('id', $_GET['id']);

        return $this->categoryAction();
    }
}