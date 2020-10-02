<?php

namespace App\Core;

class Upload
{
    public static function uploadImage()
    {
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp_name = $_FILES['image']['tmp_name'];

        //Determine filetype
        switch ($_FILES['image']['type']) {
            case 'image/jpeg': $ext = "jpeg"; break;
            case 'image/jpg': $ext = "jpg"; break;
            case 'image/png': $ext = "png"; break;
            default: $ext = ''; break;
        }

        if ($ext) {
            if ($file_size < 100000) {
                $path = DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR;
                $fullPath = BP . DIRECTORY_SEPARATOR . 'pub' . $path;

                if(file_exists($fullPath . $file_name)) {
                    $message = "Image with that name already exists";
                    throw new \Exception('Image with that name already exists');
                }

                move_uploaded_file($file_tmp_name, $fullPath . $file_name);
                $_POST['image'] = $path . $file_name;
            } else {
                throw new \Exception('Please ensure your chosen file is less than 100KB.');
            }
        } else {
            throw new \Exception('Please ensure your image is of filetype .jpg .jpeg or.png.');
        }
    }
}