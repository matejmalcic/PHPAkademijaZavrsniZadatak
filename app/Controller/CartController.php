<?php

namespace App\Controller;

use App\Model\ProductCart;
use App\Model\Cart;

class CartController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'cart' . DIRECTORY_SEPARATOR;

    public function cartAction()
    {
        $cartId = Cart::getOne('sessionId', session_id());
        $data = ProductCart::getProducts( $cartId->id);
        return $this->view->render($this->viewDir . 'index',[
            'data' => $data
        ]);
    }
}