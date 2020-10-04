<?php

namespace App\Controller;

use App\Core\Upload;
use App\Model\Cart;
use App\Model\Product;
use App\Model\ProductCart;

class ProductController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR;
    private $cart;

    public function __construct()
    {
        parent::__construct();
        $this->cart = Cart::getOne('sessionId', session_id());
    }

    public function menuAction()
    {
        $data = Product::getAll();
        return $this->view->render($this->viewDir . 'index',[
            'data' => $data
        ]);
    }

    public function putProductInCartAction()
    {
        $_GET['cartId'] = $this->cart->id;
        ProductCart::insert($_GET);
        return $this->menuAction();
    }

    //next 5 functions are product CRUD for admin
    public function editProductAction($productId = null, string $message = '')
    {
        if($_GET){
            $productId = $_GET['id'];
        }
        $product = Product::getOne('id', $productId);

        if(!$product){
            $this->menuAction();
            exit;
        }

        $this->view->render($this->viewDir . 'edit',[
            'product' => $product,
            'message' => $message
        ]);
    }

    public function submitEditProductAction()
    {
        if($this->auth->getCurrentUser()->status !== 'Admin'){
            //set error message
            header('Location: /~polaznik20');
        }

        $errors = [];
        if(isset($_FILES['image']) && $_FILES['image']['name']!='') {
            try {
                Upload::uploadImage();
            } catch(\Exception $e) {
                $errors[] = $e->getMessage();
            }
        } else {
            $_POST['image'] = Product::getOne('id', $_POST['id'])->image;
        }

        if ($errors){
            return $this->editProductAction($_POST['id'], $errors[0]);
        }

        Product::update($_POST,'id', $_POST['id']);
        return $this->menuAction();
    }

    public function addNewProductAction()
    {
        $this->view->render($this->viewDir . 'addNew');
    }

    public function submitAddNewProductAction()
    {
        if($this->auth->getCurrentUser()->status !== 'Admin'){
            //set error message
            header('Location: /~polaznik20');
        }

        $errors = [];
        if(isset($_FILES['image']) && $_FILES['image']['name']!='') {
            try {
                Upload::uploadImage();
            } catch(\Exception $e) {
                $errors = $e->getMessage();
            }
        } else {
            $_POST['image'] = 'unknownProduct.jpg';
        }

        if ($errors){
            return $this->menuAction($e->getMessage());
        }

        Product::insert($_POST);
        return $this->menuAction();
    }

    public function deleteProductAction()
    {
        if($this->auth->getCurrentUser()->status !== 'Admin'){
            //set error message
            header('Location: /~polaznik20');
        }

        Product::delete('id', $_GET['id']);
        return $this->menuAction();
    }
}