<?php

namespace App\Controller;

use App\Model\ProductCart;
use App\Model\Cart;

class CartController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR;
    private $cartId;

    public function __construct()
    {
        parent::__construct();
        $this->cartId = Cart::getOne('sessionId', session_id())->id;
    }

    public function cartAction()
    {
        $data = ProductCart::getProducts($this->cartId);
        return $this->view->render($this->viewDir . 'index',[
            'data' => $data,
            'cartId' => $this->cartId
        ]);
    }

    public function removeProductFromCartAction()
    {
        ProductCart::removeProduct($this->cartId, $_GET['productId']);
        return $this->cartAction();
    }
}