<?php

namespace App\Controller;

use App\Model\Cart;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductCart;
use App\Model\Status;

class StaffController extends MainController
{
    private $viewDirOrder = 'crud' . DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR;

    public function __construct()
    {
        parent::__construct();
        if ($_SESSION['user']->status !== 'Admin' && $_SESSION['user']->status !== 'Staff') {
            $authentic = new UserController();
            $authentic->logoutAction();
            exit;
        }
    }

    public function editOrderAction()
    {
        $cart = Cart::getOne('id', $_GET['id']);
        $data = ProductCart::getProducts($cart);

        return $this->view->render($this->viewDirOrder . 'edit', [
            'orderInfo' => $data,
            'cart' => $cart
        ]);
    }

    public function addNewProductAction()
    {
        $cartId = $_GET['cartId'];
        $products = Product::getAll();
        return $this->view->render($this->viewDirOrder . 'addNewProduct', [
            'products' => $products,
            'cartId' => $cartId
        ]);
    }

    public function submitAddNewProductAction()
    {
        ProductCart::insert($_GET);
        $render = new OrderController();
        return $render->orderAction();
    }

    public function changeStatusAction()
    {
        $method = 'get' . $_GET['direction'] . 'Status';

        $status = Status::$method($_GET['statusName']);

        if($status){
            Order::update( ['status' => $status['name']], 'id', $_GET['orderId']);
        }

        $render = new OrderController();
        return $render->orderAction();
    }

    public function deleteOrderAction()
    {
        Cart::delete('id', $_GET['cartId']);

        $render = new OrderController();
        return $render->orderAction();
    }
}