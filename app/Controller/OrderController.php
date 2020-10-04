<?php

namespace App\Controller;

use App\Model\Cart;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductCart;
use App\Model\User;

class OrderController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR;

    public function orderAction()
    {
        if(!$this->auth->isLoggedIn()){
            header('Location: /~polaznik20');
        }

        $orderData = Order::getAll('time');

        $array=[];
        foreach ($orderData as $order){
            $array[] = ProductCart::getMultiple('cartId', $order->cart);
        }

        //Getting product name&image and adding to $productData[]
        $productData = [];
        foreach ($array as $arr){
            foreach ($arr as $ar){
                $prod = Product::getOne('id', $ar->productId);
                $ar->productImage = $prod->getImage();
                $ar->productName = $prod->getName();
                $productData[]= $ar;
            }
        }


        return $this->view->render($this->viewDir . 'index',[
            'orderData' => $orderData,
            'productData' => $productData
        ]);
    }

    public function cartOrderAction()
    {
        if (!(float)$_GET['price']){
            //message
            header('Location: /~polaznik20/cart/cart');
            return;
        }

        Order::insert([
            'user' => $_SESSION['user']->id,
            'cart' => $_GET['cartId'],
            'price' => $_GET['price'],
            'time' => date( 'H:i:s')
        ]);

        Cart::update(['ordered' => 1], 'id', $_GET['cartId']);

        session_regenerate_id();
        User::update(['sessionId' => session_id()], 'id', $_SESSION['user']->id);

        return $this->orderAction();
    }
}