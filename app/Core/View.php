<?php

namespace App\Core;

use App\Model\Subcategory;

class View
{
    private $layout;

    public function __construct($layout = "layout")
    {
        $this->layout = basename($layout);
    }

    public function render($name, $args = [])
    {
        ob_start();
        $currentUser = Auth::getInstance()->getCurrentUser();
        $subCategories = Subcategory::getAll();
        extract($args);
        include BP . DIRECTORY_SEPARATOR . "view/$name.phtml";
        $content = ob_get_clean();

        if ($this->layout) {
            include BP . DIRECTORY_SEPARATOR . "view/{$this->layout}.phtml";
        } else {
            echo $content;
        }
        return $this;
    }
}

