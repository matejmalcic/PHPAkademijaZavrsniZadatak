<?php

namespace App\Controller;

use App\Model\Order;
use App\Model\Product;
use App\Model\ProductCart;
use App\Model\User;

class OrderController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'order' . DIRECTORY_SEPARATOR;

    public function orderAction()
    {
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
        if ($_GET['price'] == '0.00'){
            //message
            header('Location: /cart/cart');
            return;
        }

        Order::insert([
            'user' => $_SESSION['user']->id,
            'cart' => $_GET['cartId'],
            'price' => $_GET['price']
        ]);
        session_regenerate_id();
        User::setSessionId($_SESSION['user']->id);

        return $this->orderAction();
    }
}