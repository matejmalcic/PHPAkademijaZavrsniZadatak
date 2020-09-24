<?php

namespace App\Controller;

use App\Model\Product;

class ProductController extends MainController
{
    private $viewDir = 'private' . DIRECTORY_SEPARATOR . 'menu' . DIRECTORY_SEPARATOR;

        public function menuAction()
        {
            $data = Product::getAll();
            return $this->view->render($this->viewDir . 'index',[
                'data' => $data
            ]);
        }
}