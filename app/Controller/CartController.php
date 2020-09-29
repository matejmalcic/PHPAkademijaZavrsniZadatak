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
        ProductCart::removeProduct($this->cart, $_GET['productId']);
        return $this->cartAction();
    }
}