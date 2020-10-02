<?php

namespace App\Controller;

use App\Model\ProductCart;
use App\Model\Cart;

class CartController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR;
    private $cart;

    public function __construct()
    {
        parent::__construct();
        $this->cart = Cart::getOne('sessionId', session_id());
    }

    public function cartAction()
    {
        $data = ProductCart::getProducts($this->cart);

        return $this->view->render($this->viewDir . 'index',[
            'data' => $data,
            'cart' => $this->cart
        ]);
    }

    public function removeProductFromCartAction()
    {
        ProductCart::removeProduct($_GET['cartId'], $_GET['productId']);
        if ($_SESSION['user']->status === 'Guest'){
            return $this->cartAction();
        }

        $render = new OrderController();
        return $render->orderAction();
    }

    public function changeAmountAction()
    {
        $data = [
            'cartId' => $_GET['cartId'],
            'productId' => $_GET['productId'],
            'amount' => $_GET['amount']
        ];

        ProductCart::amount($data);

        if($_SESSION['user']->status === 'Guest'){
            return $this->cartAction();
        }

        $render = new OrderController();
        return $render->orderAction();
    }

    public function deleteCartAction(): void
    {
        $cart = Cart::getOne('sessionId', $_SESSION['user']->sessionId);

        if(!$cart->getOrdered()){
            Cart::delete('sessionId', $_SESSION['user']->sessionId);
        }
    }
}