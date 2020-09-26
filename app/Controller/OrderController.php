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
        $orderData = Order::getMultiple('user',$_SESSION['user_id']);
        $productData = Order::productsByOrder();
        return $this->view->render($this->viewDir . 'index',[
            'orderData' => $orderData,
            'productData' => $productData
        ]);
    }

    public function cartOrderAction()
    {
        Order::insert([
            'user' => $_SESSION['user_id'],
            'cart' => $_GET['cartId']
        ]);
        session_regenerate_id();
        User::setSessionId();
        return $this->orderAction();
    }
}