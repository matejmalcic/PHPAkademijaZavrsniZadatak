<?php

namespace App\Controller;

use App\Model\Product;

class ProductController extends MainController
{
        public function menuAction()
        {
            $products = Product::getAll();
            return $this->view->render('menu',[
                'products' => $products
            ]);
        }
}