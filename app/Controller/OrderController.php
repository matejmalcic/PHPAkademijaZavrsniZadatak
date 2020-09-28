<?php

namespace App\Controller;

use App\Model\Order;
use App\Model\ProductCart;
use App\Model\User;

class OrderController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR;

    public function orderAction()
    {
        $orderData = Order::getOrderData();
        $productData = Order::productsByOrder();
        return $this->view->render($this->viewDir . 'index',[
            'orderData' => $orderData,
            'productData' => $productData
        ]);
    }

    public function cartOrderAction()
    {
        Order::insert([
            'user' => $_SESSION['user']->id,
            'cart' => $_GET['cartId']
        ]);
        session_regenerate_id();
        User::setSessionId();
        return $this->orderAction();
    }
}