<?php

declare(strict_types=1);

namespace App\Controller;

class HomeController extends MainController
{
    public function indexAction()
    {
        return $this->view->render('home', [
            'data' => []
        ]);
    }

    public function aboutAction()
    {
        return $this->view->render('about');
    }
}