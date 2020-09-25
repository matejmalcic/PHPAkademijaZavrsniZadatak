<?php

namespace App\Controller;

use App\Model\Cart;
use App\Model\Product;
use App\Model\ProductCart;

class ProductController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR;
    private $cartId;

    public function __construct()
    {
        parent::__construct();
        $this->cartId = Cart::getOne('sessionId', session_id())->id;
    }

    public function menuAction()
    {
        $data = Product::getAll();
        return $this->view->render($this->viewDir . 'index',[
            'data' => $data,
            'cartId' => $this->cartId
        ]);
    }

    public function putProductInCartAction()
    {
        $_GET['cartId'] = $this->cartId;
        ProductCart::insert($_GET);
        return $this->menuAction();
    }
}